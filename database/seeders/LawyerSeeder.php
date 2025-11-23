<?php

namespace Database\Seeders;

use App\Enums\LawyerStatusEnum;
use App\Enums\StatusEnum;
use App\Models\Admin;
use App\Models\Country;
use App\Models\Lawyer;
use App\Models\Level;
use Database\Factories\MarketProductfactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LawyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Lawyer::create([
            'name' => 'المحامى / احمد ياسر',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email' => 'hR1tI@example.com',
            'status' => 'active',
            'image' => 'https://picsum.photos/200/300',
            'national_id' => '123456789',
            'country_id' => 1,
            'city_id' => 1,
            'phone' => '01234567890',
            'country_code' => '+20',
            'lawyer_id' => '123456789',
            'type' => 'office',
            'level_id' => 1,
        ]);
        Lawyer::create([
            'name' => 'المحامى / احمد اسماعيل',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email' => 'fdf23423@example.com',
            'status' => 'inactive',
            'image' => 'https://picsum.photos/200/300',
            'national_id' => '43354534553',
            'country_id' => 1,
            'city_id' => 3,
            'phone' => '01234543256',
            'country_code' => '+20',
            'lawyer_id' => '321454267',
            'type' => 'office',
            'level_id' => 1,
        ]);
        Lawyer::create([
            'name' => 'المحامى / محمد  رضا',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email' => 'rbd24@example.com',
            'status' => 'active',
            'image' => 'https://picsum.photos/200/300',
            'national_id' => '467234627',
            'country_id' => 1,
            'city_id' => 2,
            'phone' => '01022650895',
            'country_code' => '+20',
            'lawyer_id' => '345453423',
            'type' => 'individual',
            'level_id' => 1,
        ]);
    }
}
