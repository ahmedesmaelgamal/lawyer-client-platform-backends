<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\ClientPoint;
use Illuminate\Database\Seeder;

class ClientPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientPoint::factory()->count(10)->create();
    }
}
