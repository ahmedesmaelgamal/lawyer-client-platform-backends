<?php

namespace Database\Seeders;


use App\Models\LawyerPackage;
use Illuminate\Database\Seeder;

class LawyerPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        LawyerPackage::factory()->count(50)->create();

    }

}
