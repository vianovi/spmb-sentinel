<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RegistrationDraft;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Aku samakan persis dengan PreRegistrationController
     * supaya token-only cookie flow konsisten end-to-end.
     */
    private string $cookieName = 'draft_token';

    public function create(Request $request)
    {
        $draft = $this->getDraftFromCookie($request);

        if (!$draft) {
            return redirect()
                ->route('pendaftaran.cek')
                ->withCookie(Cookie::forget($this->cookieName))
                ->with('error', 'Draft pendaftaran tidak ditemukan / sudah kedaluwarsa. Silakan ulang dari Step 1.');
        }

        // Guard: jangan izinkan masuk halaman register kalau draft belum step 3
        if ((int) $draft->current_step < 3) {
            return redirect()
                ->route('pendaftaran.cek')
                ->with('error', 'Silakan selesaikan Step 1–3 terlebih dahulu.');
        }

        // Guard: draft harus punya email sebelum bikin akun
        if (empty($draft->email)) {
            return redirect()
                ->route('pendaftaran.cek')
                ->with('error', 'Email belum tersimpan. Silakan isi email pada Step 3.');
        }

        // Guard ekstra: kalau NIK/NISN sudah jadi santri, arahkan login
        $existsSantri = Santri::where('nik', $draft->nik)
            ->orWhere('nisn', $draft->nisn)
            ->exists();

        if ($existsSantri) {
            return redirect()
                ->route('login')
                ->with('error', 'Data kamu sudah terdaftar. Silakan login.');
        }

        return view('auth.register', compact('draft'));
    }

    public function store(Request $request)
    {
        $draft = $this->getDraftFromCookie($request);

        if (!$draft) {
            return redirect()
                ->route('pendaftaran.cek')
                ->withCookie(Cookie::forget($this->cookieName))
                ->with('error', 'Draft pendaftaran tidak ditemukan / sudah kedaluwarsa. Silakan ulang dari Step 1.');
        }

        // Email final: aku pakai dari draft (token-only)
        $finalEmail = strtolower(trim((string) $draft->email));

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Ulangi password harus sama dengan password.',
        ]);

        // Guard: email tidak boleh sudah dipakai user lain
        $emailExists = User::where('email', $finalEmail)->exists();
        if ($emailExists) {
            return back()
                ->withErrors(['email' => 'Email sudah terdaftar. Silakan login.'])
                ->withInput();
        }

        // Guard: NIK/NISN tidak boleh dobel jadi santri
        $existsSantri = Santri::where('nik', $draft->nik)
            ->orWhere('nisn', $draft->nisn)
            ->exists();

        if ($existsSantri) {
            return redirect()
                ->route('login')
                ->with('error', 'Akun kamu sudah pernah dibuat. Silakan login.');
        }

        DB::transaction(function () use ($draft, $finalEmail, $request) {
            $user = User::create([
                'name' => $draft->nama_lengkap,
                'email' => $finalEmail,
                'password' => Hash::make($request->password),
            ]);

            Santri::create([
                'user_id' => $user->id,
                'nik' => $draft->nik,
                'nisn' => $draft->nisn,
                'nama_lengkap' => $draft->nama_lengkap,
                'jenis_kelamin' => $draft->jenis_kelamin,
                'tempat_lahir' => $draft->tempat_lahir,
                'tanggal_lahir' => $draft->tanggal_lahir,
                'alamat_lengkap' => $draft->alamat_lengkap,
                'asal_sekolah' => $draft->asal_sekolah,
                'status' => 'submitted',
            ]);

            // Draft tidak dibutuhkan lagi setelah akun dibuat
            $draft->delete();

            event(new Registered($user));
            Auth::login($user);
        });

        return redirect()
            ->route('dashboard')
            ->withCookie(Cookie::forget($this->cookieName))
            ->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }

    /**
     * Aku bikin helper kecil biar create() dan store() konsisten.
     */
    private function getDraftFromCookie(Request $request): ?RegistrationDraft
    {
        $token = (string) $request->cookie($this->cookieName);
        if (!$token) return null;

        $draft = RegistrationDraft::where('registration_token', $token)->first();
        if (!$draft) return null;

        // Token expired = dianggap tidak valid
        if (empty($draft->registration_token_expires_at)) return null;
        if (Carbon::now()->greaterThan(Carbon::parse($draft->registration_token_expires_at))) return null;

        return $draft;
    }
}
