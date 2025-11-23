<?php

namespace Database\Seeders;


use App\Models\CourtCaseUpdate;
use Illuminate\Database\Seeder;

class CourtCaseUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        CourtCaseUpdate::create([
            'title' => 'نزاع عقد تجاري',
            'court_case_id' => 1,
            'details' => 'قضية نزع عقد تجارى خاص بهذا العمل   ',
            'date' => now(),
        ]);
        CourtCaseUpdate::create([
            'title' => 'تصالح فى المبانى',
            'court_case_id' => 2,
            'details' => 'قضية تصالح فى المبانى خاص بهذا العمل   ',
            'date' => now(),
        ]);
        CourtCaseUpdate::create([
            'title' => '  قضية سلاح ابيض',
            'court_case_id' => 1,
            'details' => 'قضية تشاجر بسلاح ابيض ',
            'date' => now(),
        ]);
        // CourtCaseUpdate::factory()->count(10)->create();

    }

}
