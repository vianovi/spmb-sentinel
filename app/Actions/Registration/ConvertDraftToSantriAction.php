<?php

namespace App\Actions\Registration;

use App\Models\RegistrationDraft;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * ConvertDraftToSantriAction
 *
 * Single Responsibility: HANYA mengurus pembuatan User + Santri dari Draft.
 * Dipanggil dari RegisteredUserController di dalam DB::transaction().
 * Tidak ada logic lain di sini — tidak ada redirect, tidak ada cookie.
 */
class ConvertDraftToSantriAction
{
    /**
     * Eksekusi konversi draft → User + Santri.
     *
     * @param  RegistrationDraft  $draft    Data draft yang sudah tervalidasi
     * @param  string             $password Password plain-text dari input user
     * @return User                         User yang baru dibuat (sudah include relasi santri)
     */
    public function execute(RegistrationDraft $draft, string $password): User
    {
        // 1. Buat akun User
        $user = User::create([
            'name'     => $draft->nama_lengkap,
            'email'    => strtolower(trim($draft->email)),
            'password' => Hash::make($password),
            'role'     => 'santri',
            'phone'    => $draft->no_hp,
        ]);

        // 2. Pindahkan data identitas draft → tabel santris
        Santri::create([
            'user_id'       => $user->id,
            'nik'           => $draft->nik,
            'nisn'          => $draft->nisn,
            'nama_lengkap'  => $draft->nama_lengkap,
            'jenis_kelamin' => $draft->jenis_kelamin,
            'tempat_lahir'  => $draft->tempat_lahir,
            'tanggal_lahir' => $draft->tanggal_lahir,
            'alamat_lengkap'=> $draft->alamat_lengkap,
            'asal_sekolah'  => $draft->asal_sekolah,
            'status'        => 'submitted',
        ]);

        // 3. Matikan draft (soft delete — data tetap ada untuk audit log)
        $draft->delete();

        return $user;
    }
}