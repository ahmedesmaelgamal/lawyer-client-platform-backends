<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Admin;
use App\Models\Level;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Level::create([
            'title' => [
                'en'=> 'Annulment ',
                'ar'=> 'نقض',
            ],
            'salary' => 500,
        ]);

        Level::create([
            'title' => [
                'en'=> 'Appeal  ',
                'ar'=> 'استئناف',
            ],
            'salary' => 100,
        ]);

        Level::create([
            'title' => [
                'en'=> 'Primary ',
                'ar'=> 'ابتدائي',
            ],
            'salary' => 800,
        ]);

        Level::create([
            'title' => [
                'en'=> 'General',
                'ar'=> 'جدول عام',
            ],
            'salary' => 900,
        ]);

    }

}
