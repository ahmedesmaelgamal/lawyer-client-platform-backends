<?php

namespace Database\Seeders;


use App\Models\Client;
use App\Models\MarketOffer;
use Database\Factories\MarketProductCategoryFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::create([
            'name' => ' حسام احمد',
            'email' => 'bNw0P@example.com',
            'password' => Hash::make('admin'),
            'national_id' => '123456789',
            'phone' => '0123456789',
            'country_code' => '+20',
            'points' => 0,
            'city_id' => 1,
            'country_id' => 1,
            'status' => 'active',
            'image' => 'https://picsum.photos/200/300',

        ]);
        Client::create([
            'name' => 'محمد ممدوح',
            'email' => 'bdfdw0P@example.com',
            'password' => Hash::make('admin222'),
            'national_id' => '1234457433',
            'phone' => '0123452568',
            'country_code' => '+20',
            'points' => 0,
            'city_id' => 2,
            'country_id' => 1,
            'status' => 'inactive',
            'image' => 'https://picsum.photos/200/300',

        ]);
        Client::create([
            'name' => 'سامى ابراهيم',
            'email' => 'bNsdffw0P@example.com',
            'password' => Hash::make('admin'),
            'national_id' => '1234522223',
            'phone' => '0125456789',
            'country_code' => '+20',
            'points' => 0,
            'city_id' => 1,
            'country_id' => 1,
            'status' => 'active',
            'image' => 'https://picsum.photos/200/300',

        ]);
        Client::create([
            'name' => 'احمد اسماعيل',
            'email' => 'bNaaadw0P@example.com',
            'password' => Hash::make('admin'),
            'national_id' => '123437889',
            'phone' => '0123428456',
            'country_code' => '+20',
            'points' => 330,
            'city_id' => 3,
            'country_id' => 1,
            'status' => 'inactive',
            'image' => 'https://picsum.photos/200/300',

        ]);
        Client::create([
            'name' => 'ثناء عادل ',
            'email' => 'breNw0P@example.com',
            'password' => Hash::make('admin'),
            'national_id' => '2457642347',
            'phone' => '01257345784',
            'country_code' => '+20',
            'points' => 343,
            'city_id' => 2,
            'country_id' => 1,
            'status' => 'active',
            'image' => 'https://picsum.photos/200/300',

        ]);
    }

    // Client::factory()->count(10)->create();


}
