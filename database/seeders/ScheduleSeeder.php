<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Kita ambil waktu sekarang
        $now = Carbon::now();

        // ==========================================
        // 1. DATA GELOMBANG 1 (SUDAH TUTUP / NON-AKTIF)
        // ==========================================
        // Ceritanya gelombang ini mulai 3 bulan lalu dan tutup 1 bulan lalu
        $wave1Close = $now->copy()->subMonth();

        Schedule::create([
            'batch_name' => 'Gelombang 1',
            'academic_year' => 'TP 2026/2027',
            'description' => 'Jalur Prestasi & Early Bird.',

            // Waktu Lampau
            'start_date' => $now->copy()->subMonths(3),
            'end_date' => $wave1Close,

            // Jadwal Rinci (Juga Lampau)
            'exam_date' => $wave1Close->copy()->addDays(5)->setTime(8, 0),
            'announcement_date' => $wave1Close->copy()->addDays(8)->setTime(10, 0),
            'reregistration_date' => $wave1Close->copy()->addDays(9)->setTime(8, 0),

            'price' => 250000, // Lebih murah (early bird)
            'quota' => 50,
            'quota_filled' => 50, // Anggap saja penuh
            'is_active' => false, // WAJIB FALSE KARENA SUDAH LEWAT
        ]);


        // ==========================================
        // 2. DATA GELOMBANG 2 (SEDANG BUKA / AKTIF)
        // ==========================================
        // Ceritanya buka hari ini sampai 2 bulan ke depan
        $wave2Close = $now->copy()->addMonths(2);

        Schedule::create([
            'batch_name' => 'Gelombang 2',
            'academic_year' => 'TP 2026/2027',
            'description' => 'Pendaftaran jalur reguler gelombang kedua.',

            // Waktu Sekarang & Masa Depan
            'start_date' => $now,
            'end_date' => $wave2Close,

            // Jadwal Rinci
            'exam_date' => $wave2Close->copy()->addDays(5)->setTime(8, 0),
            'announcement_date' => $wave2Close->copy()->addDays(8)->setTime(10, 0),
            'reregistration_date' => $wave2Close->copy()->addDays(9)->setTime(8, 0),

            'price' => 300000,
            'quota' => 70,
            'quota_filled' => 12, // Ceritanya baru ada 12 pendaftar
            'is_active' => true, // WAJIB TRUE BIAR MUNCUL DI WEB
        ]);
    }
}

/**
 * --- CATATAN COMMAND UNTUK TERMINAL ---
 *
 * Untuk mereset database total (hapus semua tabel & data) lalu isi ulang:
 * Jalankan: php artisan migrate:fresh --seed
 *
 * Untuk hanya mengisi data dari file ini saja (tanpa hapus tabel):
 * Jalankan: php artisan db:seed --class=ScheduleSeeder
 */