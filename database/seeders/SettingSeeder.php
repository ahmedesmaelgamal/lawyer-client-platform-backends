<?php

namespace Database\Seeders;

use App\Models\OfferPackage;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Testing\Fakes\Fake;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Setting::factory()->count(50)->create();
        $settings = [
            [
                'key' => 'app_name',
                'value' => 'ataaby',
            ],
            [
                'key' => 'app_version_android',
                'value' => '1.0.0',
            ],
            [
                'key' => 'app_version_ios',
                'value' => '1.0.0',
            ],
            [
                'key' => 'logo',
                'value' => 'logo.png',
            ],
            [
                'key' => 'fav_icon',
                'value' => 'logo.png',
            ],
            [
                'key' => 'loader',
                'value' => 'logo.png',
            ],
            [
                'key' => 'app_mentainance',
                'value' => 'false',
            ],
            [
                'key' => 'about_en',
                'value' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,",
            ],
            [
                'key' => 'about_ar',
                'value' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,",
            ],
            [
                'key' => 'court_case_vat',
                'value' => '0',
            ],
            [
                'key' => 'referral_sender_points',
                'value' => '50',
            ],
            [
                'key' => 'referral_receiver_points',
                'value' => '50',
            ],
            [
                //إلى جنيه point من
                'key' => 'point_to_cash',
                'value' => '100',  // جنيه = 100 نقطة
            ],
            [
                'key' => 'filePrice',
                'value' => '1',
            ]

        ];
        Setting::insert($settings);
    }
}
