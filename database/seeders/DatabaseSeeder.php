<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Santri;
use Illuminate\Database\Seeder;

// Pastikan class ini terpanggil (kalau beda namespace)
// use Database\Seeders\ScheduleSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------
        // 0. MASTER DATA (Jalankan Seeder Jadwal Duluan)
        // ---------------------------------------------------
        $this->call([
            ScheduleSeeder::class,
        ]);

        // ---------------------------------------------------
        // 1. Create Admin
        // ---------------------------------------------------
        User::create([
            'name' => 'Admin SPMB',
            'email' => 'admin@spmb.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // ---------------------------------------------------
        // 2. Create Silvia's Account (Pendaftar)
        // ---------------------------------------------------
        $silvia = User::create([
            'name' => 'Silvia Hacker',
            'email' => 'silvia@spmb.com',
            'password' => bcrypt('password'),
            'role' => 'santri',
        ]);

        // ---------------------------------------------------
        // 3. Create 10 Dummy Santris (Target Operasi)
        // ---------------------------------------------------
        User::factory(10)->create(['role' => 'santri'])->each(function ($user) {
            Santri::factory()->create(['user_id' => $user->id]);
        });
    }
}