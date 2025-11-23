<?php

namespace Database\Seeders;


use App\Models\MarketProduct;
use Illuminate\Database\Seeder;

class MarketProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MarketProduct::factory()->count(10)->create();
    }

}
