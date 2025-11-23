<?php

namespace Database\Seeders;


use App\Models\CourtCaseUpdate;
use App\Models\LawyerSpeciality;
use Illuminate\Database\Seeder;

class LawyerSpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LawyerSpeciality::factory()->count(10)->create();
    }

}
