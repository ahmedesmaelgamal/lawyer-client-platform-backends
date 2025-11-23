<?php

namespace Database\Seeders;

use App\Models\OfferPackage;
use App\Models\Order;
use App\Models\Rate;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rate::factory()->count(50)->create();
    }
}
