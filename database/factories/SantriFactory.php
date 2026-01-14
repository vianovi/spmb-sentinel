<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SantriFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Kita akan override user_id saat seeding nanti
            'user_id' => User::factory(),
            // FIX: Gunakan numerify untuk generate 16 digit angka random (NIK Style)
            'nik' => $this->faker->unique()->numerify('################'),
            'nama_lengkap' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2010-01-01'), // Umur santri wajar
            'alamat_lengkap' => $this->faker->address(),
            'asal_sekolah' => 'SMP ' . $this->faker->company(),
            'status' => $this->faker->randomElement(['draft', 'submitted', 'verified']),
        ];
    }
}