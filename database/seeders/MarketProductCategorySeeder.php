<?php

namespace Database\Seeders;


use App\Models\MarketProductCategory;
use Illuminate\Database\Seeder;

class MarketProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MarketProductCategory::factory()->count(10)->create();
    }

}
