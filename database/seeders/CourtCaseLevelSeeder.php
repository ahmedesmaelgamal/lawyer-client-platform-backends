<?php

namespace Database\Seeders;


use App\Models\CourtCaseLevel;
use Illuminate\Database\Seeder;

class CourtCaseLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourtCaseLevel::create([
            'title' => [
                'en' => 'Annulment ',
                'ar' => 'نقض',
            ],
            'status' => 'inactive',
        ]);
        CourtCaseLevel::create([
            'title' => [
                'en' => 'Appeal  ',
                'ar' => 'استئناف',
            ],
            'status' => 'active',
        ]);
        CourtCaseLevel::create([
            'title' => [
                'en' => 'Primary ',
                'ar' => 'ابتدائي',
            ],
            'status' => 'inactive',
        ]);
        CourtCaseLevel::create([
            'title' => [
                'en' => 'General',
                'ar' => 'جدول عام',
            ],
            'status' => 'active',
        ]);
        CourtCaseLevel::create([
            'title' => '  معارضة فى الداخل ',
            'status' => 'active',
        ]);
        CourtCaseLevel::create([
            'title' => '  معارضة فى الخارج ',
            'status' => 'inactive',
        ]);
    }
}
