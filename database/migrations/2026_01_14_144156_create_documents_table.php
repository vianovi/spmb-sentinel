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

            /**
             * Kategori dokumen — untuk grouping di UI upload berkas
             * data_diri  : foto, akte lahir, KK, KTP ortu
             * akademik   : ijazah, NISN, raport
             * kesehatan  : surat sehat, golongan darah
             * pembayaran : bukti transfer biaya pendaftaran
             */
            $table->enum('category', [
                'data_diri',
                'akademik',
                'kesehatan',
                'pembayaran',
            ]);

            /**
             * Tipe dokumen spesifik di dalam kategori.
             * Contoh: 'foto', 'ijazah', 'kk', 'surat_sehat', 'bukti_bayar'
             */
            $table->string('type');

            /**
             * Status review oleh admin
             * pending  : baru diupload, belum direview
             * approved : dokumen diterima
             * rejected : dokumen ditolak (santri perlu upload ulang)
             */
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // Catatan admin saat reject (opsional)
            $table->string('rejection_note')->nullable();

            // Informasi file
            $table->string('mime_type')->nullable();     // image/jpeg, application/pdf
            $table->string('path');                      // lokasi di storage
            $table->string('original_name')->nullable(); // nama asli file user

            $table->timestamps();

            // Index untuk performa filter
            $table->index(['santri_id', 'category']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};