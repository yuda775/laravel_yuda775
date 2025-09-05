<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'hospital_id' => \App\Models\Hospitals::factory(), // buat hospital secara otomatis jika belum ada
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'phone' => $this->faker->optional()->phoneNumber(),
        ];
    }
}
