<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Santri;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'Admin SPMB',
            'email' => 'admin@spmb.com',
            'password' => bcrypt('password'), // Ganti password kuat di production!
            'role' => 'admin',
        ]);

        // 2. Create Silvia's Account (Pendaftar)
        $silvia = User::create([
            'name' => 'Silvia Hacker',
            'email' => 'silvia@spmb.com',
            'password' => bcrypt('password'),
            'role' => 'santri',
        ]);

        // Buat data santri kosong buat Silvia (ceritanya baru daftar)
        // Nanti kita isi lewat form aplikasi

        // 3. Create 10 Dummy Santris (Target Operasi)
        // Kita buat 10 user baru, yang masing-masing langsung punya data santri
        User::factory(10)->create(['role' => 'santri'])->each(function ($user) {
            Santri::factory()->create(['user_id' => $user->id]);
        });
    }
}