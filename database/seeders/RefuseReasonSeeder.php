<?php

namespace Database\Seeders;

use App\Models\RefuseReason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefuseReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RefuseReason::create([
            'name' => 'بسبب اسباب فنية',
            'type' => 'complete',
        ]);
        RefuseReason::create([
            'name' => 'لعدم اكتمال الاجراءات الجنائية ',
            'type' => 'cancel',
        ]);
        RefuseReason::create([
            'name' => 'لعدم اكتمال الاجراءات القانونية ',
            'type' => 'complete',
        ]);

        // RefuseReason::factory(20)->create();
        //  return [
        //     "name" => [
        //         'en' => $this->faker->title(),
        //         'ar'=> $fakerAr->title(),
        //     ],
        //     'type' => $this->faker->randomElement(['cancel','complete']),
        // ];
    }
}
