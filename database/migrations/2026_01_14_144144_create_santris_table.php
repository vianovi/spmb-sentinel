<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('santris', function (Blueprint $table) {
            /**
             * ID INTERNAL
             * Auto-increment (sengaja tetap angka untuk kebutuhan lab IDOR)
             * ❗ TIDAK dipakai sebagai identifier publik
             */
            $table->id();

            /**
             * Relasi ke users
             * - 1 user = 1 santri
             * - hapus user -> hapus santri (aman)
             */
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // =========================
            // IDENTITAS UTAMA
            // =========================

            /**
             * NIK & NISN
             * - Wajib unik (final data)
             * - Dipakai untuk validasi sebelum registrasi
             */
            $table->string('nik', 16)->unique();
            $table->string('nisn', 10)->unique();

            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');

            // =========================
            // ALAMAT & SEKOLAH
            // =========================
            $table->text('alamat_lengkap');
            $table->string('asal_sekolah');

            // =========================
            // STATUS PENDAFTARAN
            // =========================
            /**
             * draft     : data awal (belum final)
             * submitted : sudah submit dari preregistration
             * verified  : lolos administrasi
             * rejected  : ditolak
             */
            $table->enum('status', [
                'draft',
                'submitted',
                'verified',
                'rejected'
            ])->default('submitted');

            $table->timestamps();

            // =========================
            // INDEX TAMBAHAN
            // =========================
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
