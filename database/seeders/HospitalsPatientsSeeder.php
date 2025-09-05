<?php

namespace Database\Seeders;

use App\Models\Hospitals;
use App\Models\Patients;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HospitalsPatientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Buat 5 rumah sakit
        Hospitals::factory(5)->create()->each(function ($hospital) {
            // Untuk setiap rumah sakit, buat 10 pasien
            Patients::factory(10)->create(['hospital_id' => $hospital->id]);
        });
    }
}
