<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\CommunityCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommunityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CommunityCategory::factory()->count(10)->create();
    }
}
