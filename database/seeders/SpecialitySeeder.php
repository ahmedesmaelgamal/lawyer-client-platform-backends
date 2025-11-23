<?php

namespace Database\Seeders;



use App\Models\Speciality;
use Illuminate\Database\Seeder;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Speciality::create([
            'title' => [
                'en' => 'Security State',
                'ar' => 'امن دولة',
            ],
            'level_id' => 1,
            'status' => 'active',
        ]);
        Speciality::create([
            'title' => [
                'en' => 'Justice',
                'ar' => 'جنائي',
            ],
            'level_id' => 2,
            'status' => 'inactive',
        ]);
        Speciality::create([
            'title' => [
                'en' => 'Misdemeanors',
                'ar' => 'جنح',
            ],
            'level_id' => 3,
            'status' => 'active',
        ]);
        Speciality::create([
            'title' => [
                'en' => 'Executive and Legislative Council',
                'ar' => 'إدارية ومجلس الدوله	',
            ],
            'level_id' => 2,
            'status' => 'active',
        ]);
        Speciality::create([
            'title' => [
                'en' => 'Commercial',
                'ar' => 'تجاري',
            ],
            'level_id' => 1,
            'status' => 'inactive',
        ]);
        Speciality::create([
            'title' => [
                'en' => 'Civil',
                'ar' => 'مدني',
            ],
            'level_id' => 3,
            'status' => 'active',
        ]);
        Speciality::create([
            'title' => [
                'en' => 'family',
                'ar' => 'اسره',
            ],
            'level_id' => 2,
            'status' => 'active',
        ]);
    }
}
