<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
    Schema::create('documents', function (Blueprint $table) {
        $table->id();

        // Relasi ke Santri
        $table->foreignId('santri_id')->constrained()->onDelete('cascade');

        // Jenis Dokumen (Bisa ditambah nanti)
        $table->string('type'); // Contoh: 'kk', 'ijazah', 'foto', 'bukti_bayar'

        // Informasi File
        $table->string('mime_type')->nullable(); // image/jpeg, application/pdf (Buat validasi security nanti)
        $table->string('path'); // Lokasi file di storage
        $table->string('original_name')->nullable(); // Nama asli file user

        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
