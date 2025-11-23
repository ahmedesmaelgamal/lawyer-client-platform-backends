<?php

namespace Database\Seeders;


use App\Models\MarketOffer;
use Database\Factories\MarketProductCategoryFactory;
use Illuminate\Database\Seeder;

class MarketOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MarketOffer::factory()->count(10)->create();
    }

}
