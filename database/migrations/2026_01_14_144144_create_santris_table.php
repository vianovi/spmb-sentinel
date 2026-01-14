<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('santris', function (Blueprint $table) {
        $table->id(); // Auto-increment (Target IDOR Hacking!)

        // Relasi ke User (Cascade delete: Hapus user = Hapus data santri)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // Data Pribadi
        $table->string('nik', 16)->unique(); // NIK wajib unik
        $table->string('nama_lengkap');
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->string('tempat_lahir');
        $table->date('tanggal_lahir');
        $table->text('alamat_lengkap');
        $table->string('asal_sekolah');

        // Status Pendaftaran
        // draft: baru isi form
        // submitted: sudah kirim, nunggu verifikasi
        // verified: lolos administrasi
        // rejected: ditolak
        $table->enum('status', ['draft', 'submitted', 'verified', 'rejected'])->default('draft');

        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
