<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\ContractCategory;
use App\Models\ContractFile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContractFile::factory()->count(10)->create();
    }
}
