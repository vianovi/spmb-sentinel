<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SantriFactory extends Factory
{
    public function definition(): array
    {
        return [
            // override user_id saat seeding bila diperlukan
            'user_id' => User::factory(),

            // 16 digit angka
            'nik' => $this->faker->unique()->numerify('################'),

            // 10 digit angka (NISN)
            'nisn' => $this->faker->unique()->numerify('##########'),

            'nama_lengkap' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tempat_lahir' => $this->faker->city(),

            // Tanggal lahir santri (mis: umur 10-17 tahun)
            // Faker dateTimeBetween lebih stabil untuk range umur
            'tanggal_lahir' => $this->faker->dateTimeBetween('-17 years', '-10 years')->format('Y-m-d'),

            'alamat_lengkap' => $this->faker->address(),
            'asal_sekolah' => 'SMP ' . $this->faker->company(),

            'status' => $this->faker->randomElement(['draft', 'submitted', 'verified']),
        ];
    }
}
