<?php

namespace Database\Seeders;

use App\Enums\EventStatusEnum;
use App\Enums\StatusEnum;
use App\Models\CourtCaseEvent;
use App\Models\Client;
use App\Models\CourtCase;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Database\Seeder;

class CourtCaseEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourtCaseEvent::create([
            'lawyer_id' => 1,
            'status' => EventStatusEnum::values()[1],
            'price' => 5000,
            'court_case_id' => 1
        ]);
        CourtCaseEvent::create([
            'lawyer_id' => 2,
            'status' => EventStatusEnum::values()[1],
            'price' => 3321,
            'court_case_id' => 2
        ]);
        CourtCaseEvent::create([
            'lawyer_id' => 3,
            'status' => EventStatusEnum::values()[1],
            'price' => 4343,
            'court_case_id' => 1
        ]);
    }
}
