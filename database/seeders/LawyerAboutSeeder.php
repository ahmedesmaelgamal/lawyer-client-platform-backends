<?php

namespace Database\Seeders;

use App\Models\LawyerAbout;
use App\Models\LawyerPackage;
use Illuminate\Database\Seeder;

class LawyerAboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 3; $i++) {
            LawyerAbout::create([
                'lawyer_id' => $i + 1,
                'about' => fake()->paragraph(),
                'consultation_fee' => fake()->numberBetween(50, 10000),
                'attorney_fee' => fake()->numberBetween(50, 10000),
                'office_address' => fake()->address(),
            ]);
        }
    }
}
