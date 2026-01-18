<?php

namespace App\Http\Controllers;

use App\Models\RegistrationDraft;
use App\Models\Schedule;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class PreRegistrationController extends Controller
{
    /**
     * Nama cookie untuk menyimpan token draft di browser.
     * Aku buat konsisten supaya gampang dicari kalau debug.
     */
    private string $cookieName = 'draft_token';

    /**
     * TTL token draft = 24 jam (sesuai keputusan kamu).
     * Ini TTL cookie dan TTL token di database.
     */
    private int $tokenHours = 24;

    /**
     * Aku pakai method ini untuk:
     * - menampilkan wizard
     * - menentukan step aktif berdasarkan current_step
     * - mengambil draft berdasarkan cookie token (token-only)
     */
    public function show(Request $request)
    {
        // Ambil gelombang aktif
        $activeWave = Schedule::where('is_active', true)->first();
        if (!$activeWave) {
            return redirect('/')->with('error', 'Pendaftaran sedang ditutup.');
        }

        $draft = $this->getDraftFromCookie($request);

        // Behavior paling simpel:
        // kalau cookie ada tapi draft tidak valid/expired => hapus cookie, mulai dari step 1
        if ($request->cookie($this->cookieName) && !$draft) {
            return redirect()
                ->route('pendaftaran.cek')
                ->withCookie(Cookie::forget($this->cookieName))
                ->with('error', 'Draft pendaftaran kamu sudah kedaluwarsa. Silakan mulai lagi dari awal.');
        }

        $viewStep = 1;

        if ($draft) {
            if ($request->get('mode') === 'edit') {
                $viewStep = 1;
            } else {
                $viewStep = (int)($draft->current_step ?? 1);
                $viewStep = max(1, min(3, $viewStep));
            }
        }

        return view('pendaftaran.form-wizard', compact('activeWave', 'draft', 'viewStep'));
    }

    /* ==========================================================
     * STEP 1 — IDENTITAS SANTRI
     * ========================================================== */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'schedule_id'   => 'required|exists:schedules,id',
            'nama_lengkap'  => ['required','string','min:3','max:80','regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'nisn'          => ['required','digits:10'],
            'nik'           => ['required','digits:16'],
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => ['required','string','min:2','max:60','regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'tanggal_lahir' => 'required|date',
        ], [
            'required' => ':attribute wajib diisi.',
            'digits' => ':attribute harus berjumlah :digits digit angka.',
            'regex' => 'Format :attribute tidak valid.',
            'in' => 'Pilihan :attribute tidak valid.',
        ], [
            'schedule_id' => 'Gelombang Pendaftaran',
            'nama_lengkap' => 'Nama Lengkap',
            'nisn' => 'NISN',
            'nik' => 'NIK',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
        ]);

        /**
         * HARD STOP:
         * Kalau NIK/NISN sudah ada di santris => sudah terdaftar resmi.
         */
        $alreadySantri = Santri::where('nik', $validated['nik'])
            ->orWhere('nisn', $validated['nisn'])
            ->exists();

        if ($alreadySantri) {
            return back()
                ->withErrors([
                    'nik' => 'NIK / NISN sudah terdaftar. Silakan login dan lengkapi data di dashboard.'
                ])
                ->withInput();
        }

        /**
         * Token-only cookie:
         * Aku ambil draft dari cookie dulu (kalau ada).
         * Tapi demi kasus “mulai lagi” atau “cookie hilang”, aku juga support resume berbasis NIK/NISN
         * (karena tabel draft sudah unique NIK/NISN).
         */
        $draftFromCookie = $this->getDraftFromCookie($request);

        $draft = $draftFromCookie ?: RegistrationDraft::where('nik', $validated['nik'])
            ->orWhere('nisn', $validated['nisn'])
            ->latest('id')
            ->first();

        $data = [
            'schedule_id'   => $validated['schedule_id'],
            'nama_lengkap'  => $this->normalizeTitleName($validated['nama_lengkap']),
            'nisn'          => $validated['nisn'],
            'nik'           => $validated['nik'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir'  => $this->normalizeTitleName($validated['tempat_lahir']),
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'current_step'  => 2,
        ];

        // kalau belum ada draft => create
        if (!$draft) {
            $draft = RegistrationDraft::create($data);
        } else {
            $draft->update($data);
        }

        /**
         * Aku pastikan draft punya token cookie yang valid selama 24 jam.
         * Ini inti token-only.
         */
        $this->issueOrRefreshToken($draft);

        return redirect()
            ->route('pendaftaran.cek')
            ->withCookie($this->makeTokenCookie($draft->registration_token))
            ->with('success', 'Data identitas berhasil disimpan.');
    }

    /* ==========================================================
     * STEP 2 — ALAMAT & KONTAK
     * ========================================================== */
    public function storeStep2(Request $request, $id)
    {
        $draft = $this->getDraftFromCookie($request);

        if (!$draft) {
            return redirect()
                ->route('pendaftaran.cek')
                ->withCookie(Cookie::forget($this->cookieName))
                ->with('error', 'Sesi draft kamu sudah berakhir. Silakan mulai lagi dari Step 1.');
        }

        /**
         * Guard anti-tamper:
         * Aku terima {id} dari URL supaya Blade kamu minim berubah,
         * tapi aku tidak percaya itu. Aku wajib pastikan id URL = id draft milik token cookie.
         */
        if ((int)$id !== (int)$draft->id) {
            abort(403, 'Akses tidak valid.');
        }

        // Step 2 tidak boleh kalau Step 1 belum beres
        if ((int)$draft->current_step < 2) {
            return redirect()
                ->route('pendaftaran.cek')
                ->with('error', 'Silakan lengkapi Step 1 terlebih dahulu.');
        }

        $validated = $request->validate([
            'nama_ibu'   => ['required','string','min:3','max:80'],
            'no_hp'      => ['required','digits_between:9,14'],
            'email'      => 'nullable|email',

            'addr_jalan' => 'required|string|min:5|max:150',
            'addr_rt'    => 'required|digits_between:1,3',
            'addr_rw'    => 'required|digits_between:1,3',
            'addr_desa'  => 'required|string|min:2|max:60',
            'addr_kec'   => 'required|string|min:2|max:60',
            'addr_kab'   => 'required|string|min:2|max:60',
            'addr_prov'  => 'required|string|min:2|max:60',
        ], [
            'required' => ':attribute wajib diisi.',
            'digits_between' => ':attribute harus antara :min sampai :max digit.',
            'email' => 'Format email tidak valid.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maksimal :max karakter.',
        ], [
            'nama_ibu' => 'Nama Ibu Kandung',
            'no_hp' => 'Nomor WhatsApp',
            'email' => 'Email',
            'addr_jalan' => 'Jalan / Dusun / Perumahan',
            'addr_rt' => 'RT',
            'addr_rw' => 'RW',
            'addr_desa' => 'Kelurahan/Desa',
            'addr_kec' => 'Kecamatan',
            'addr_kab' => 'Kabupaten/Kota',
            'addr_prov' => 'Provinsi',
        ]);

        // Normalisasi nomor HP -> 62xxxxxxxx
        $hp = preg_replace('/\D+/', '', $validated['no_hp']);
        $hp = ltrim($hp, '0');
        if (!Str::startsWith($hp, '62')) $hp = '62' . $hp;

        // RT/RW -> 3 digit
        $rt = str_pad((string)$validated['addr_rt'], 3, '0', STR_PAD_LEFT);
        $rw = str_pad((string)$validated['addr_rw'], 3, '0', STR_PAD_LEFT);

        // Rapikan alamat (spasi doang)
        $jalan = $this->normalizeSpaces($validated['addr_jalan']);

        $desa = $this->normalizeTitleName($validated['addr_desa']);
        $kec  = $this->normalizeTitleName($validated['addr_kec']);
        $kab  = $this->normalizeTitleName($validated['addr_kab']);
        $prov = $this->normalizeTitleName($validated['addr_prov']);

        $email = (isset($validated['email']) && trim((string)$validated['email']) !== '')
            ? strtolower(trim((string)$validated['email']))
            : null;

        $alamatGabung = sprintf(
            "%s, RT %s / RW %s, Kel. %s, Kec. %s, %s, Prov. %s",
            $jalan, $rt, $rw, $desa, $kec, $kab, $prov
        );

        $draft->update([
            'nama_ibu' => $this->normalizeTitleName($validated['nama_ibu']),
            'no_hp' => $hp,
            'email' => $email,

            // ✅ ini yang kemarin sering null kalau controller lupa simpan
            'addr_jalan' => $jalan,
            'addr_rt' => $rt,
            'addr_rw' => $rw,
            'addr_desa' => $desa,
            'addr_kec' => $kec,
            'addr_kab' => $kab,
            'addr_prov' => $prov,

            'alamat_lengkap' => $alamatGabung,
            'current_step' => 3,
        ]);

        // refresh token expiry biar 24 jam dihitung dari aktivitas terakhir (lebih nyaman)
        $this->issueOrRefreshToken($draft);

        return redirect()
            ->route('pendaftaran.cek')
            ->withCookie($this->makeTokenCookie($draft->registration_token))
            ->with('success', 'Data kontak dan alamat berhasil disimpan.');
    }

    /* ==========================================================
     * STEP 3 — NEXT KE /registrasi (belum kita bangun)
     * ========================================================== */
    public function nextToRegistrasi(Request $request, $id)
    {
        $draft = $this->getDraftFromCookie($request);

        if (!$draft) {
            return redirect()
                ->route('pendaftaran.cek')
                ->withCookie(Cookie::forget($this->cookieName))
                ->with('error', 'Sesi draft kamu sudah berakhir. Silakan mulai lagi dari Step 1.');
        }

        if ((int)$id !== (int)$draft->id) {
            abort(403, 'Akses tidak valid.');
        }

        if ((int)$draft->current_step < 3) {
            return redirect()
                ->route('pendaftaran.cek')
                ->with('error', 'Silakan lengkapi Step 1 & 2 terlebih dahulu.');
        }

        $validated = $request->validate([
            'asal_sekolah' => ['required', 'string', 'min:3', 'max:120'],
            'agreement' => ['accepted'],
            'email' => ['nullable', 'email'], // kalau draft email kosong, blade akan tampilkan input ini
        ], [
            'asal_sekolah.required' => 'Asal sekolah wajib diisi.',
            'asal_sekolah.min' => 'Asal sekolah minimal 3 karakter.',
            'asal_sekolah.max' => 'Asal sekolah maksimal 120 karakter.',
            'agreement.accepted' => 'Silakan centang pernyataan persetujuan terlebih dahulu.',
            'email.email' => 'Format email tidak valid.',
        ]);

        // kalau draft belum punya email, pakai email input
        if (empty($draft->email) && !empty($validated['email'])) {
            $draft->email = strtolower(trim((string)$validated['email']));
        }

        $draft->asal_sekolah = $this->normalizeSpaces($validated['asal_sekolah']);

        // Double-check: jangan sampai sudah jadi santri
        $existsSantri = Santri::where('nik', $draft->nik)
            ->orWhere('nisn', $draft->nisn)
            ->exists();

        if ($existsSantri) {
            return redirect()
                ->route('pendaftaran.cek')
                ->with('error', 'NIK / NISN sudah terdaftar. Silakan login dan lengkapi data di dashboard.');
        }

        // bikin kode pendaftaran kalau belum ada
        if (empty($draft->registration_code)) {
            $draft->registration_code = $this->makeRegistrationCode($draft->id);
        }

        $draft->save();

        // refresh token expiry (karena user aktif sampai step 3)
        $this->issueOrRefreshToken($draft);

        // redirect ke /registrasi pakai CODE saja (token tidak ikut URL)
        // halaman ini memang belum dibuat, jadi nanti 404 itu wajar untuk sekarang.
        return redirect('/registrasi?code=' . urlencode($draft->registration_code))
            ->withCookie($this->makeTokenCookie($draft->registration_token));
    }

    /* ==========================================================
     * TOKEN ONLY - HELPERS
     * ========================================================== */

    private function getDraftFromCookie(Request $request): ?RegistrationDraft
    {
        $token = (string) $request->cookie($this->cookieName);
        if (!$token) return null;

        $draft = RegistrationDraft::where('registration_token', $token)->first();
        if (!$draft) return null;

        // expired => dianggap tidak valid
        if (!$draft->isTokenValid()) {
            return null;
        }

        return $draft;
    }

    private function issueOrRefreshToken(RegistrationDraft $draft): void
    {
        $draft->registration_token = $draft->registration_token ?: Str::random(64);
        $draft->registration_token_expires_at = Carbon::now()->addHours($this->tokenHours);
        $draft->save();
    }

    private function makeTokenCookie(string $token)
    {
        // cookie 24 jam, HttpOnly supaya JS tidak bisa baca token
        // SameSite=Lax cukup aman untuk flow normal.
        return Cookie::make(
            $this->cookieName,
            $token,
            $this->tokenHours * 60, // menit
            null,
            null,
            false,
            true, // HttpOnly
            false,
            'Lax'
        );
    }

    private function makeRegistrationCode(int $internalId): string
    {
        $year = date('Y');
        $seq = str_pad((string)$internalId, 6, '0', STR_PAD_LEFT);
        return "SPMB-{$year}-{$seq}";
    }

    /* ==========================================================
     * FORMAT INPUT HELPERS
     * ========================================================== */

    private function normalizeTitleName(string $value): string
    {
        $value = $this->normalizeSpaces($value);
        $value = strtolower($value);
        return Str::title($value);
    }

    private function normalizeSpaces(string $value): string
    {
        return preg_replace('/\s+/', ' ', trim($value));
    }
}
