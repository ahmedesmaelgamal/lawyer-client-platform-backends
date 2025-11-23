<?php

namespace Database\Seeders;

use App\Enums\CurrencyEnum;
use App\Enums\StatusEnum;
use App\Models\Admin;
use App\Models\Country;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create([
            'title' => [
                'en' => 'Egypt',
                'ar' => 'مصر'
            ],
            'currency' => CurrencyEnum::EGP,
            'status' => StatusEnum::ACTIVE
        ]);
        Country::create([
            'title' => [
                'en' => 'United States',
                'ar' => 'الولايات المتحدة الأمريكية',
            ],
            'currency' => CurrencyEnum::USD,
            'status' => StatusEnum::ACTIVE
        ]);
        Country::create([
            'title' => [
                'en' => 'Saudi Arabia',
                'ar' => 'السعودية',
            ],
            'currency' => CurrencyEnum::SAR,
            'status' => StatusEnum::ACTIVE
        ]);
        Country::create([
            'title' => [
                'en' => 'sudan ',
                'ar' => 'السودان',
            ],
            'currency' => CurrencyEnum::SUD,
            'status' => StatusEnum::INACTIVE
        ]);

      
    }
}
