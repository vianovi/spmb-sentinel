<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_drafts', function (Blueprint $table) {
            $table->id(); // AUTO INCREMENT -> Ini KUNCI celah IDOR kamu nanti!

            // Kita simpan Gelombang berapa dia daftar
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->onDelete('set null');

            // STEP 1: Identitas
            $table->string('nama_lengkap');
            $table->string('nisn', 10)->nullable(); // Validasi panjang nanti di Controller
            $table->string('nik', 16)->nullable();  // Validasi panjang nanti di Controller
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);

            // STEP 2: Kontak & Wali (Nullable dlu karena diisi di step 2)
            $table->text('alamat_lengkap')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('no_hp', 15)->nullable(); // String, jangan Int biar angka 0 depan gak ilang
            $table->string('email')->nullable(); // Opsional di sini

            // STATUS TRACKING
            // 1=Identitas, 2=Kontak, 3=Review/Ready
            $table->integer('current_step')->default(1);

            $table->timestamps();
            $table->softDeletes(); // Data tidak benar2 hilang, buat audit trail
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_drafts');
    }
};