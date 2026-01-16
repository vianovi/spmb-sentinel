<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('batch_name'); // Contoh: "Gelombang 2"
            $table->string('academic_year'); // Contoh: "TP 2026/2027"
            $table->text('description')->nullable();

            // Tanggal Utama
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            // --- KOLOM BARU (Untuk Popup Jadwal) ---
            // Pakai nullable() supaya admin tidak wajib isi saat awal buat gelombang
            $table->dateTime('exam_date')->nullable();           // Tanggal Ujian
            $table->dateTime('announcement_date')->nullable();   // Tanggal Pengumuman
            $table->dateTime('reregistration_date')->nullable(); // Tanggal Daftar Ulang

            $table->decimal('price', 12, 2)->default(0);
            $table->integer('quota')->default(0);
            $table->integer('quota_filled')->default(0);

            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};