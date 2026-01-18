<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_drafts', function (Blueprint $table) {
            // ID internal (boleh auto increment), tapi JANGAN dipakai untuk akses publik/URL
            $table->id();

            // ✅ ID publik anti-tebak (dipakai di URL)
            $table->uuid('public_id')->unique();

            // Gelombang pendaftaran
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->onDelete('set null');

            // =========================
            // STEP 1: IDENTITAS
            // =========================
            $table->string('nama_lengkap');
            $table->string('nisn', 10)->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);

            /**
             * ✅ Anti-duplikasi draft (biar resume, bukan bikin entry baru)
             * - nullable tetap boleh (MySQL: multiple NULL ok)
             * - kalau sudah terisi, harus unik
             */
            $table->unique('nik');
            $table->unique('nisn');

            // =========================
            // STEP 2: KONTAK & ALAMAT
            // =========================
            $table->text('alamat_lengkap')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('email')->nullable();

            // Detail alamat terstruktur (sesuai input Step 2 di Blade)
            $table->string('addr_jalan')->nullable();
            $table->string('addr_rt', 3)->nullable();
            $table->string('addr_rw', 3)->nullable();
            $table->string('addr_desa')->nullable();
            $table->string('addr_kec')->nullable();
            $table->string('addr_kab')->nullable();
            $table->string('addr_prov')->nullable();

            // =========================
            // STEP 3: REVIEW / READY
            // =========================
            $table->string('asal_sekolah')->nullable();

            // Kode pendaftaran yang human-friendly (sequential)
            $table->string('registration_code')->nullable()->unique();

            // Token akses (DISIMPAN, TIDAK DITAMPILKAN DI UI)
            // Dipakai untuk validasi akses step lanjut /registrasi
            $table->string('registration_token', 128)->nullable()->unique();
            $table->timestamp('registration_token_expires_at')->nullable();

            // Tracking step
            $table->unsignedTinyInteger('current_step')->default(1);

            $table->timestamps();
            $table->softDeletes();

            // Index tambahan untuk performa pencarian
            $table->index('schedule_id');
            $table->index('email');
            $table->index('current_step');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_drafts');
    }
};