<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * Aku pakai waktu sekarang sebagai acuan utama,
         * supaya seeder ini selalu relevan kapan pun dijalankan.
         */
        $now = Carbon::now();

        /**
         * Sebelum insert data baru, aku pastikan
         * tidak ada lebih dari satu gelombang yang aktif.
         *
         * Ini penting supaya:
         * - Hero section tidak bingung
         * - Logic pendaftaran konsisten
         */
        Schedule::query()->update(['is_active' => false]);

        // ======================================================
        // 1. GELOMBANG 1 (SUDAH TUTUP / NON-AKTIF)
        // ======================================================
        /**
         * Cerita bisnis:
         * - Gelombang ini dibuka 3 bulan lalu
         * - Ditutup 1 bulan lalu
         * - Kuota sudah penuh
         */
        $wave1Close = $now->copy()->subMonth();

        Schedule::updateOrCreate(
            [
                // Aku pakai kombinasi ini sebagai "natural key"
                'batch_name' => 'Gelombang 1',
                'academic_year' => 'TP 2026/2027',
            ],
            [
                'description' => 'Jalur Prestasi & Early Bird.',

                // Periode pendaftaran (lampau)
                'start_date' => $now->copy()->subMonths(3),
                'end_date' => $wave1Close,

                // Jadwal lanjutan (semua sudah lewat)
                'exam_date' => $wave1Close->copy()->addDays(5)->setTime(8, 0),
                'announcement_date' => $wave1Close->copy()->addDays(8)->setTime(10, 0),
                'reregistration_date' => $wave1Close->copy()->addDays(9)->setTime(8, 0),

                'price' => 250000, // Early bird lebih murah
                'quota' => 50,
                'quota_filled' => 50, // Anggap penuh
                'is_active' => false, // WAJIB FALSE (historical data)
            ]
        );

        // ======================================================
        // 2. GELOMBANG 2 (SEDANG BUKA / AKTIF)
        // ======================================================
        /**
         * Cerita bisnis:
         * - Gelombang ini aktif sekarang
         * - Ditutup 2 bulan ke depan
         * - Masih tersedia kuota
         */
        $wave2Close = $now->copy()->addMonths(2);

        Schedule::updateOrCreate(
            [
                'batch_name' => 'Gelombang 2',
                'academic_year' => 'TP 2026/2027',
            ],
            [
                'description' => 'Pendaftaran jalur reguler gelombang kedua.',

                // Periode aktif
                'start_date' => $now,
                'end_date' => $wave2Close,

                // Jadwal ke depan
                'exam_date' => $wave2Close->copy()->addDays(5)->setTime(8, 0),
                'announcement_date' => $wave2Close->copy()->addDays(8)->setTime(10, 0),
                'reregistration_date' => $wave2Close->copy()->addDays(9)->setTime(8, 0),

                'price' => 300000,
                'quota' => 70,
                'quota_filled' => 12, // Simulasi: sudah ada 12 pendaftar
                'is_active' => true, // INI YANG AKAN TERBACA OLEH SISTEM
            ]
        );
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
