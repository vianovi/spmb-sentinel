<?php

namespace App\Http\Controllers;

use App\Models\RegistrationDraft;
use App\Models\Schedule;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PreRegistrationController extends Controller
{
    public function show(Request $request)
    {
        $activeWave = Schedule::where('is_active', true)->first();
        if (!$activeWave) {
            return redirect('/')->with('error', 'Pendaftaran sedang ditutup.');
        }

        $draft = null;
        $viewStep = 1;

        if ($request->has('draft_id')) {
            $draft = RegistrationDraft::find($request->draft_id);
            if ($draft) {
                // ✅ FIX: Jangan kunci step ke 2.
                // Ikuti current_step sebenarnya (1/2/3), kecuali mode edit.
                if ($request->get('mode') === 'edit') {
                    $viewStep = 1;
                } else {
                    $viewStep = (int)($draft->current_step ?? 1);
                    if ($viewStep < 1) $viewStep = 1;
                    if ($viewStep > 3) $viewStep = 3;
                }
            }
        }

        return view('pendaftaran.form-wizard', compact('activeWave', 'draft', 'viewStep'));
    }

    public function storeStep1(Request $request)
    {
        $rules = [
            'schedule_id'   => 'required|exists:schedules,id',
            'nama_lengkap'  => ['required', 'string', 'min:3', 'max:80', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'nisn'          => ['required', 'regex:/^[0-9]+$/', 'digits:10'],
            'nik'           => ['required', 'regex:/^[0-9]+$/', 'digits:16'],
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => ['required', 'string', 'min:2', 'max:60', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'tanggal_lahir' => 'required|date',
        ];

        $messages = [
            'required'    => ':attribute wajib diisi.',
            'digits'      => ':attribute harus berjumlah :digits digit angka.',
            'in'          => 'Pilihan :attribute tidak valid.',
            'regex'       => 'Format :attribute tidak valid.',
            'max'         => ':attribute maksimal :max karakter.',
            'min'         => ':attribute minimal :min karakter.',

            'schedule_id.exists' => 'Gelombang pendaftaran tidak ditemukan.',
            'nama_lengkap.regex' => 'Nama Lengkap hanya boleh berisi huruf, spasi, titik, tanda petik, atau tanda hubung.',
            'tempat_lahir.regex' => 'Tempat Lahir hanya boleh berisi huruf, spasi, titik, tanda petik, atau tanda hubung.',
            'nisn.regex'  => 'NISN harus berisi angka saja.',
            'nik.regex'   => 'NIK harus berisi angka saja.',
        ];

        $attributes = [
            'schedule_id'   => 'Gelombang Pendaftaran',
            'nama_lengkap'  => 'Nama Lengkap',
            'nisn'          => 'NISN',
            'nik'           => 'NIK',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tempat_lahir'  => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        $nama  = $this->normalizeTitleName($validated['nama_lengkap']);
        $lahir = $this->normalizeTitleName($validated['tempat_lahir']);

        $dataToSave = [
            'schedule_id'   => $validated['schedule_id'],
            'nama_lengkap'  => $nama,
            'nisn'          => $validated['nisn'],
            'nik'           => $validated['nik'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir'  => $lahir,
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'current_step'  => 2,
        ];

        if (!empty($request->existing_draft_id)) {
            $draft = RegistrationDraft::find($request->existing_draft_id);
            if ($draft) {
                $draft->update($dataToSave);
            } else {
                $draft = RegistrationDraft::create($dataToSave);
            }
        } else {
            $draft = RegistrationDraft::create($dataToSave);
        }

        return redirect()->route('pendaftaran.cek', ['draft_id' => $draft->id])
            ->with('success', 'Data diri berhasil disimpan.');
    }

    public function storeStep2(Request $request, $id)
    {
        $rules = [
            'nama_ibu'   => ['required', 'string', 'min:3', 'max:80', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'no_hp'      => ['required', 'regex:/^[0-9]+$/', 'digits_between:9,14'],
            'email'      => 'nullable|email',

            'addr_jalan' => 'required|string|min:5|max:150',
            'addr_rt'    => ['required', 'regex:/^[0-9]+$/', 'digits_between:1,3'],
            'addr_rw'    => ['required', 'regex:/^[0-9]+$/', 'digits_between:1,3'],
            'addr_desa'  => ['required', 'string', 'min:2', 'max:60', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'addr_kec'   => ['required', 'string', 'min:2', 'max:60', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'addr_kab'   => ['required', 'string', 'min:2', 'max:60', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'addr_prov'  => ['required', 'string', 'min:2', 'max:60', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
        ];

        $messages = [
            'required' => ':attribute wajib diisi.',
            'regex' => 'Format :attribute tidak valid.',
            'digits_between' => ':attribute harus antara :min sampai :max digit.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maksimal :max karakter.',
            'email.email' => 'Format email tidak valid (contoh: nama@email.com).',

            'nama_ibu.regex' => 'Nama Ibu Kandung hanya boleh berisi huruf, spasi, titik, tanda petik, atau tanda hubung.',
            'no_hp.regex' => 'Nomor WhatsApp harus berisi angka saja tanpa spasi/tanda.',
            'addr_desa.regex' => 'Kelurahan/Desa hanya boleh berisi huruf dan spasi.',
            'addr_kec.regex' => 'Kecamatan hanya boleh berisi huruf dan spasi.',
            'addr_kab.regex' => 'Kabupaten/Kota hanya boleh berisi huruf dan spasi.',
            'addr_prov.regex' => 'Provinsi hanya boleh berisi huruf dan spasi.',
            'addr_jalan.min' => 'Jalan/Dusun minimal 5 karakter agar alamat jelas.',
        ];

        $attributes = [
            'nama_ibu' => 'Nama Ibu Kandung',
            'no_hp'    => 'Nomor WhatsApp',
            'email'    => 'Email',

            'addr_jalan' => 'Jalan / Dusun / Perumahan',
            'addr_rt' => 'RT',
            'addr_rw' => 'RW',
            'addr_desa' => 'Kelurahan/Desa',
            'addr_kec' => 'Kecamatan',
            'addr_kab' => 'Kabupaten/Kota',
            'addr_prov' => 'Provinsi',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        $namaIbu = $this->normalizeTitleName($validated['nama_ibu']);
        $jalan = $this->normalizeSpaces($validated['addr_jalan']);

        $rt = str_pad($validated['addr_rt'], 3, '0', STR_PAD_LEFT);
        $rw = str_pad($validated['addr_rw'], 3, '0', STR_PAD_LEFT);

        $desa = $this->normalizeTitleName($validated['addr_desa']);
        $kec  = $this->normalizeTitleName($validated['addr_kec']);
        $kab  = $this->normalizeTitleName($validated['addr_kab']);
        $prov = $this->normalizeTitleName($validated['addr_prov']);

        // No HP konsisten 62xxxxxxxx
        $hp = preg_replace('/\D+/', '', $validated['no_hp']);
        $hp = ltrim($hp, '0');
        if (!Str::startsWith($hp, '62')) {
            $hp = '62' . $hp;
        }

        // Email aman kalau kosong
        $email = (isset($validated['email']) && trim((string)$validated['email']) !== '')
            ? strtolower(trim($validated['email']))
            : null;

        $alamatGabung = sprintf(
            "%s, RT %s / RW %s, Kel. %s, Kec. %s, %s, Prov. %s",
            $jalan, $rt, $rw, $desa, $kec, $kab, $prov
        );

        $draft = RegistrationDraft::findOrFail($id);

        $draft->update([
            'nama_ibu' => $namaIbu,
            'no_hp' => $hp,
            'email' => $email,

            // ✅ Simpan detail supaya tidak hilang saat reload
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

        return redirect()->route('pendaftaran.cek', ['draft_id' => $draft->id])
            ->with('success', 'Data kontak dan alamat berhasil disimpan.');
    }

    /**
     * FINALISASI (STEP 3):
     * - bikin user
     * - bikin santri (sesuai migration + fillable Santri.php)
     * - hapus draft
     * - auto login
     */
    public function storeFinalize(Request $request, $id)
    {
        $draft = RegistrationDraft::findOrFail($id);

        // Email final: dari draft kalau ada, atau dari input step 3
        $finalEmail = $draft->email ?: $request->input('email');
        $finalEmail = $finalEmail ? strtolower(trim($finalEmail)) : null;

        $rules = [
            'asal_sekolah' => ['required', 'string', 'min:3', 'max:120'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'agreement' => ['accepted'],
        ];

        // Kalau draft belum punya email, wajib input dan unique di users
        if (empty($draft->email)) {
            $rules['email'] = ['required', 'email', Rule::unique('users', 'email')];
        } else {
            // input email di blade readonly, tapi tetap kita cek unique manual biar aman
            $rules['email'] = ['nullable'];
        }

        $messages = [
            'email.required' => 'Email wajib diisi untuk login.',
            'email.email' => 'Format email tidak valid (contoh: nama@email.com).',
            'email.unique' => 'Email sudah terdaftar. Gunakan email lain.',

            'asal_sekolah.required' => 'Asal sekolah wajib diisi.',
            'asal_sekolah.min' => 'Asal sekolah minimal 3 karakter.',
            'asal_sekolah.max' => 'Asal sekolah maksimal 120 karakter.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Ulangi password harus sama dengan password.',

            'agreement.accepted' => 'Silakan centang pernyataan persetujuan terlebih dahulu.',
        ];

        $validated = $request->validate($rules, $messages);

        // Jika draft punya email, pastikan email itu belum dipakai user lain
        if (!empty($draft->email)) {
            $existsEmail = User::where('email', $finalEmail)->exists();
            if ($existsEmail) {
                return back()
                    ->withErrors(['email' => 'Email sudah terdaftar. Gunakan email lain.'])
                    ->withInput();
            }
        }

        // Sesuai migration santris: nik UNIQUE
        $existsNik = Santri::where('nik', $draft->nik)->exists();
        if ($existsNik) {
            return back()->with('error', 'Data gagal diproses: NIK sudah terdaftar.')->withInput();
        }

        try {
            DB::beginTransaction();

            // ✅ Sesuai migration users kamu: hanya name, email, password
            $user = User::create([
                'name' => $draft->nama_lengkap,
                'email' => $finalEmail,
                'password' => Hash::make($validated['password']),
            ]);

            // ✅ Sesuai Santri.php fillable + migration santris
            Santri::create([
                'user_id' => $user->id,
                'nik' => $draft->nik,
                'nama_lengkap' => $draft->nama_lengkap,
                'jenis_kelamin' => $draft->jenis_kelamin,
                'tempat_lahir' => $draft->tempat_lahir,
                'tanggal_lahir' => $draft->tanggal_lahir,
                'alamat_lengkap' => $draft->alamat_lengkap,
                'asal_sekolah' => $this->normalizeSpaces($validated['asal_sekolah']),
                'status' => 'submitted',
            ]);

            // hapus draft agar tidak dobel
            $draft->delete();

            DB::commit();

            Auth::login($user);

            return redirect('/dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat akun. Silakan coba lagi.')->withInput();
        }
    }

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
