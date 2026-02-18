<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Registration\ConvertDraftToSantriAction;
use App\Http\Controllers\Controller;
use App\Models\RegistrationDraft;
use App\Models\Santri;
use App\Models\User;
use App\Services\RegistrationDraftTokenService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly RegistrationDraftTokenService $tokenService
    ) {}

    /**
     * Tampilkan halaman buat password.
     * Email & ringkasan data diambil dari draft via cookie.
     */
    public function create(Request $request)
    {
        $draft = $this->tokenService->loadDraftFromRequest($request);

        if (!$draft) {
            return redirect()
                ->route('pendaftaran.cek')
                ->withCookie($this->tokenService->forgetCookie())
                ->with('error', 'Draft pendaftaran tidak ditemukan atau sudah kedaluwarsa. Silakan mulai dari Step 1.');
        }

        // Guard: draft belum selesai step 3
        if ((int) $draft->current_step < 3) {
            return redirect()
                ->route('pendaftaran.cek')
                ->with('error', 'Silakan selesaikan Step 1–3 terlebih dahulu.');
        }

        // Guard: email wajib ada di draft sebelum bikin akun
        if (empty($draft->email)) {
            return redirect()
                ->route('pendaftaran.cek')
                ->with('error', 'Email belum tersimpan. Silakan lengkapi email pada Step 2 atau 3.');
        }

        // Guard: kalau NIK/NISN sudah jadi santri, arahkan login
        $sudahTerdaftar = Santri::where('nik', $draft->nik)
            ->orWhere('nisn', $draft->nisn)
            ->exists();

        if ($sudahTerdaftar) {
            return redirect()
                ->route('login')
                ->with('error', 'Data kamu sudah terdaftar. Silakan login.');
        }

        return view('auth.register', compact('draft'));
    }

    /**
     * Proses pembuatan akun:
     * 1. Validasi password
     * 2. Guard duplikasi
     * 3. Jalankan ConvertDraftToSantriAction dalam transaksi
     * 4. Login otomatis → redirect dashboard
     */
    public function store(Request $request, ConvertDraftToSantriAction $action)
    {
        $draft = $this->tokenService->loadDraftFromRequest($request);

        if (!$draft) {
            return redirect()
                ->route('pendaftaran.cek')
                ->withCookie($this->tokenService->forgetCookie())
                ->with('error', 'Draft pendaftaran tidak ditemukan atau sudah kedaluwarsa. Silakan mulai dari Step 1.');
        }

        $finalEmail = strtolower(trim((string) $draft->email));

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Guard: email tidak boleh sudah dipakai
        if (User::where('email', $finalEmail)->exists()) {
            return back()
                ->withErrors(['email' => 'Email ini sudah terdaftar. Silakan login.'])
                ->withInput();
        }

        // Guard: NIK/NISN tidak boleh dobel (race condition prevention)
        $sudahTerdaftar = Santri::where('nik', $draft->nik)
            ->orWhere('nisn', $draft->nisn)
            ->exists();

        if ($sudahTerdaftar) {
            return redirect()
                ->route('login')
                ->with('error', 'Akun sudah pernah dibuat. Silakan login.');
        }

        // Jalankan konversi dalam transaksi — kalau ada yang gagal, semua rollback
        $user = DB::transaction(
            fn () => $action->execute($draft, $request->password)
        );

        event(new Registered($user));
        Auth::login($user);

        return redirect()
            ->route('dashboard')
            ->withCookie($this->tokenService->forgetCookie())
            ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');
    }
}