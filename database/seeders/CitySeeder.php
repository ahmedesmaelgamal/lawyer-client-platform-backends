<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // City::factory()->count(10)->create();

        City::create([
            'title' => [
                'en' => 'Cairo',
                'ar' => 'القاهرة'
            ],
            'country_id' => 1
        ]);

        City::create([
            'title' => [
                'en' => 'Alexandria',
                'ar' => 'الأسكندرية'
            ],
            'country_id' => 1
        ]);

        City::create([
            'title' => [
                'en' => 'Giza',
                'ar' => 'جيزة'
            ],
            'country_id' => 1
        ]);
    }
}
