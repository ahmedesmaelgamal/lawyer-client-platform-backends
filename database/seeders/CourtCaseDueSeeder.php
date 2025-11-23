<?php

namespace Database\Seeders;


use App\Models\CourtCaseDue;
use App\Models\CourtCaseEvent;
use Illuminate\Database\Seeder;

class CourtCaseDueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourtCaseDue::create([
            'title' => 'المحكمة الابتدائية',
            'from_user_id' => 1,
            'to_user_id' => 2,
            'from_user_type' => 'lawyer',
            'to_user_type' => 'client',
            'court_case_id' => 1,
            'court_case_event_id' => 1,
            'date' => now(),
            'paid' => false,
            'price' => rand(1000, 99999),
        ]);
        CourtCaseDue::create([
            'title' => 'محكمة الاسرة ',
            'from_user_id' => 2,
            'to_user_id' => 1,
            'from_user_type' => 'client',
            'to_user_type' => 'lawyer',
            'court_case_id' => 2,
            'court_case_event_id' => 2,
            'date' => now(),
            'paid' => true,
            'price' => rand(1000, 99999),
        ]);
        CourtCaseDue::create([
            'title' => ' محكمة النقض',
            'from_user_id' => 2,
            'to_user_id' => 1,
            'from_user_type' => 'client',
            'to_user_type' => 'lawyer',
            'court_case_id' => 2,
            'court_case_event_id' => 2,
            'date' => now(),
            'paid' => false,
            'price' => rand(1000, 99999),
        ]);
    }
}
