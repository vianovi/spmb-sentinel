<?php

namespace App\Http\Controllers;

use App\Models\RegistrationDraft;
use App\Models\Schedule;
use App\Models\Santri;
use App\Services\RegistrationDraftTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PreRegistrationController extends Controller
{
    /**
     * TTL token draft = 24 jam.
     * Nilainya mengikuti konstanta di RegistrationDraftTokenService agar selalu sinkron.
     */
    private int $tokenHours = RegistrationDraftTokenService::TOKEN_TTL_HOURS;

    public function __construct(
        private readonly RegistrationDraftTokenService $tokenService
    ) {}

    /**
     * Tampilkan halaman wizard.
     * Step aktif ditentukan dari current_step di draft (via cookie).
     */
    public function show(Request $request)
    {
        $activeWave = Schedule::where('is_active', true)->first();

        if (!$activeWave) {
            return redirect('/')->with('error', 'Pendaftaran sedang ditutup.');
        }

        $draft = $this->tokenService->loadDraftFromRequest($request);

        // Bersihkan error "draft expired" kalau ternyata draft masih valid
        if ($draft) {
            $err = (string) session('error');
            if (
                Str::contains($err, 'Draft pendaftaran') ||
                Str::contains($err, 'tidak ditemukan') ||
                Str::contains($err, 'kedaluwarsa') ||
                Str::contains($err, 'Sesi draft kamu sudah berakhir')
            ) {
                session()->forget('error');
            }
        }

        $viewStep = 1;

        if ($draft) {
            if ($request->get('mode') === 'edit') {
                $viewStep = 1;
            } else {
                $viewStep = (int) ($draft->current_step ?? 1);
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
            'nama_lengkap'  => ['required', 'string', 'min:3', 'max:80', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'nisn'          => ['required', 'digits:10'],
            'nik'           => ['required', 'digits:16'],
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => ['required', 'string', 'min:2', 'max:60', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'tanggal_lahir' => 'required|date',
        ], [
            'required'  => ':attribute wajib diisi.',
            'digits'    => ':attribute harus berjumlah :digits digit angka.',
            'regex'     => 'Format :attribute tidak valid.',
            'in'        => 'Pilihan :attribute tidak valid.',
        ], [
            'schedule_id'   => 'Gelombang Pendaftaran',
            'nama_lengkap'  => 'Nama Lengkap',
            'nisn'          => 'NISN',
            'nik'           => 'NIK',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tempat_lahir'  => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
        ]);

        // Hard stop: kalau NIK/NISN sudah jadi santri resmi
        $alreadySantri = Santri::where('nik', $validated['nik'])
            ->orWhere('nisn', $validated['nisn'])
            ->exists();

        if ($alreadySantri) {
            return back()
                ->withErrors(['nik' => 'NIK / NISN sudah terdaftar. Silakan login dan lengkapi data di dashboard.'])
                ->withInput();
        }

        // Resume dari cookie atau NIK/NISN (kalau cookie hilang)
        $draft = $this->tokenService->loadDraftFromRequest($request)
            ?: RegistrationDraft::where('nik', $validated['nik'])
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

        if (!$draft) {
            $draft = RegistrationDraft::create($data);
        } else {
            $draft->update($data);
        }

        $cookie = $this->tokenService->issueCookieForDraft($draft);

        return redirect()
            ->route('pendaftaran.cek')
            ->withCookie($cookie)
            ->with('success', 'Data identitas berhasil disimpan.');
    }

    /* ==========================================================
     * STEP 2 — ALAMAT & KONTAK
     * ========================================================== */
    public function storeStep2(Request $request, $id)
    {
        $draft = $this->tokenService->loadDraftFromRequest($request);

        if (!$draft) {
            return redirect()
                ->route('pendaftaran.cek')
                ->withCookie($this->tokenService->forgetCookie())
                ->with('error', 'Sesi draft kamu sudah berakhir. Silakan mulai lagi dari Step 1.');
        }

        // Guard anti-tamper: id URL harus cocok dengan draft milik cookie
        if ((int) $id !== (int) $draft->id) {
            abort(403, 'Akses tidak valid.');
        }

        if ((int) $draft->current_step < 2) {
            return redirect()
                ->route('pendaftaran.cek')
                ->with('error', 'Silakan lengkapi Step 1 terlebih dahulu.');
        }

        $validated = $request->validate([
            'nama_ibu'   => ['required', 'string', 'min:3', 'max:80'],
            'no_hp'      => ['required', 'digits_between:9,14'],
            'email'      => 'nullable|email',
            'addr_jalan' => 'required|string|min:5|max:150',
            'addr_rt'    => 'required|digits_between:1,3',
            'addr_rw'    => 'required|digits_between:1,3',
            'addr_desa'  => 'required|string|min:2|max:60',
            'addr_kec'   => 'required|string|min:2|max:60',
            'addr_kab'   => 'required|string|min:2|max:60',
            'addr_prov'  => 'required|string|min:2|max:60',
        ], [
            'required'       => ':attribute wajib diisi.',
            'digits_between' => ':attribute harus antara :min sampai :max digit.',
            'email'          => 'Format email tidak valid.',
            'min'            => ':attribute minimal :min karakter.',
            'max'            => ':attribute maksimal :max karakter.',
        ], [
            'nama_ibu'   => 'Nama Ibu Kandung',
            'no_hp'      => 'Nomor WhatsApp',
            'email'      => 'Email',
            'addr_jalan' => 'Jalan / Dusun / Perumahan',
            'addr_rt'    => 'RT',
            'addr_rw'    => 'RW',
            'addr_desa'  => 'Kelurahan/Desa',
            'addr_kec'   => 'Kecamatan',
            'addr_kab'   => 'Kabupaten/Kota',
            'addr_prov'  => 'Provinsi',
        ]);

        // Normalisasi nomor HP → 62xxxxxxxx
        $hp = preg_replace('/\D+/', '', $validated['no_hp']);
        $hp = ltrim($hp, '0');
        if (!Str::startsWith($hp, '62')) {
            $hp = '62' . $hp;
        }

        // RT/RW → 3 digit
        $rt = str_pad((string) $validated['addr_rt'], 3, '0', STR_PAD_LEFT);
        $rw = str_pad((string) $validated['addr_rw'], 3, '0', STR_PAD_LEFT);

        $jalan = $this->normalizeSpaces($validated['addr_jalan']);
        $desa  = $this->normalizeTitleName($validated['addr_desa']);
        $kec   = $this->normalizeTitleName($validated['addr_kec']);
        $kab   = $this->normalizeTitleName($validated['addr_kab']);
        $prov  = $this->normalizeTitleName($validated['addr_prov']);

        $email = (isset($validated['email']) && trim((string) $validated['email']) !== '')
            ? strtolower(trim((string) $validated['email']))
            : null;

        $alamatGabung = sprintf(
            '%s, RT %s / RW %s, Kel. %s, Kec. %s, %s, Prov. %s',
            $jalan, $rt, $rw, $desa, $kec, $kab, $prov
        );

        $draft->update([
            'nama_ibu'      => $this->normalizeTitleName($validated['nama_ibu']),
            'no_hp'         => $hp,
            'email'         => $email,
            'addr_jalan'    => $jalan,
            'addr_rt'       => $rt,
            'addr_rw'       => $rw,
            'addr_desa'     => $desa,
            'addr_kec'      => $kec,
            'addr_kab'      => $kab,
            'addr_prov'     => $prov,
            'alamat_lengkap'=> $alamatGabung,
            'current_step'  => 3,
        ]);

        $cookie = $this->tokenService->issueCookieForDraft($draft);

        return redirect()
            ->route('pendaftaran.cek')
            ->withCookie($cookie)
            ->with('success', 'Data kontak dan alamat berhasil disimpan.');
    }

    /* ==========================================================
     * STEP 3 — LANJUT KE /registrasi
     * ========================================================== */
    public function nextToRegistrasi(Request $request, $id)
    {
        $draft = $this->tokenService->loadDraftFromRequest($request);

        if (!$draft) {
            return redirect()
                ->route('pendaftaran.cek')
                ->withCookie($this->tokenService->forgetCookie())
                ->with('error', 'Sesi draft tidak ditemukan. Silakan mulai dari Step 1.');
        }

        // Guard anti-tamper
        if ((int) $id !== (int) $draft->id) {
            abort(403, 'Akses tidak valid.');
        }

        // Guard: step belum sampai 3
        if ((int) $draft->current_step < 3) {
            return redirect()
                ->route('pendaftaran.cek')
                ->with('error', 'Silakan lengkapi Step 1 & 2 terlebih dahulu.');
        }

        $validated = $request->validate([
            'asal_sekolah' => ['required', 'string', 'min:3', 'max:120'],
            'agreement'    => ['accepted'],
            'email'        => empty($draft->email)
                ? ['required', 'email']
                : ['nullable', 'email'],
        ], [
            'asal_sekolah.required' => 'Asal sekolah wajib diisi.',
            'asal_sekolah.min'      => 'Asal sekolah minimal 3 karakter.',
            'agreement.accepted'    => 'Silakan centang pernyataan persetujuan.',
            'email.required'        => 'Email wajib diisi untuk membuat akun.',
            'email.email'           => 'Format email tidak valid.',
        ]);

        // Set email dari step 3 kalau draft belum punya
        if (empty($draft->email)) {
            $draft->email = strtolower(trim((string) ($validated['email'] ?? '')));
        }

        if (empty($draft->email)) {
            return redirect()
                ->route('pendaftaran.cek')
                ->with('error', 'Email belum tersimpan. Silakan isi email untuk lanjut registrasi.');
        }

        $draft->asal_sekolah = $this->normalizeSpaces($validated['asal_sekolah']);

        // Double-check race condition NIK/NISN
        $existsSantri = Santri::where('nik', $draft->nik)
            ->orWhere('nisn', $draft->nisn)
            ->exists();

        if ($existsSantri) {
            return redirect()
                ->route('pendaftaran.cek')
                ->with('error', 'NIK / NISN sudah terdaftar. Silakan login dan lengkapi data di dashboard.');
        }

        if (empty($draft->registration_code)) {
            $draft->registration_code = $this->makeRegistrationCode($draft->id);
        }

        $draft->save();

        $cookie = $this->tokenService->issueCookieForDraft($draft);

        return redirect()
            ->route('register')
            ->withCookie($cookie);
    }

    /* ==========================================================
     * PRIVATE HELPERS
     * ========================================================== */

    private function makeRegistrationCode(int $internalId): string
    {
        $year = date('Y');
        $seq  = str_pad((string) $internalId, 6, '0', STR_PAD_LEFT);

        return "SPMB-{$year}-{$seq}";
    }

    private function normalizeTitleName(string $value): string
    {
        return Str::title(strtolower($this->normalizeSpaces($value)));
    }

    private function normalizeSpaces(string $value): string
    {
        return preg_replace('/\s+/', ' ', trim($value));
    }
}