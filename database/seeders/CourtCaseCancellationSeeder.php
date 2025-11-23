<?php

namespace Database\Seeders;


use App\Models\CourtCaseCancellation;
use App\Models\CourtCaseDue;
use App\Models\CourtCaseEvent;
use Illuminate\Database\Seeder;

class CourtCaseCancellationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        CourtCaseCancellation::create([
            'court_case_id' => 1,
            'accept_lawyer' => 0,
            'accept_client' => 1,
        ]);

        CourtCaseCancellation::create([
            'court_case_id' => 2,
            'accept_lawyer' => 1,
            'accept_client' => 0,
        ]);

        CourtCaseCancellation::create([
            'court_case_id' => 2,
            'accept_lawyer' => 1,
            'accept_client' => 1,
        ]);
        // CourtCaseCancellation::factory()->count(10)->create();
        //    return [
        //     'court_case_id' => $this->faker->randomElement(CourtCase::pluck('id')->toArray()),
        //     'accept_lawyer' => $this->faker->boolean,
        //     'accept_client' => $this->faker->boolean,
        // ];
    }

}
