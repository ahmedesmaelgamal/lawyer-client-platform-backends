<?php

namespace Database\Seeders;

use App\Models\OfferPackage;
use Illuminate\Database\Seeder;

class OfferPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OfferPackage::factory()->count(10)->create();
    }
}
