<?php

namespace Database\Seeders;

use App\Enums\CourtCaseStatusEnum;

use App\Models\CourtCase;
use App\Enums\CourtCaseTypeEnum;
use App\Models\Client;

use Illuminate\Database\Seeder;
use App\Models\Speciality;


class CourtCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create sample court cases with specific data
        CourtCase::create([
            // 'type' => 'legal_advice',
            'type' => CourtCaseTypeEnum::LEGAL_ADVICE->value,
            'title' =>
                'نزاع عقد تجاري', // Add Arabic translation here ,
            'case_estimated_price' => 5000,
            'case_number' => 100000001,
            'details' => 'Dispute over breach of commercial contract terms',
            'status' => 'offered',
            'speciality_id' => 2,
            'client_id' => 2,
        ]);

        CourtCase::create([
            'type' => CourtCaseTypeEnum::ADVOCACY->value,
            'title' => 'قضية سرقة',
            'case_estimated_price' => 3000,
            'case_number' => 100000002,
            'details' => 'Alleged theft of valuable items from private property',
            'status' => 'new',
            'speciality_id' => 1,
            'client_id' => 1,
        ]);
        // Create additional random cases if needed
        // CourtCase::factory()->count(10)->create();
    }
}
