<?php

namespace Database\Seeders;

use App\Models\Ad;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Ad::create([
            'lawyer_id' => 1,
            'status' => 'active',
            'from_date' => Carbon::now(),
            'to_date' => Carbon::now()->addDays(30),
            'image' => 'https://picsum.photos/200',
            'package_id' => 1,
            'ad_confirmation' => 'confirmed',
        ]);
        Ad::create([
            'lawyer_id' => 1,
            'status' => 'inactive',
            'from_date' => Carbon::now(),
            'to_date' => Carbon::now()->addDays(25),
            'image' => 'https://picsum.photos/200',
            'package_id' => 1,
            'ad_confirmation' => 'rejected',
        ]);
        Ad::create([
            'lawyer_id' => 1,
            'status' => 'active',
            'from_date' => Carbon::now(),
            'to_date' => Carbon::now()->addDays(20),
            'image' => 'https://picsum.photos/200',
            'package_id' => 1,
            'ad_confirmation' => 'requested',
        ]);
    }
}
