<?php

namespace Database\Seeders;


use App\Models\CourtCaseUpdate;
use App\Models\Lawyer;
use App\Models\LawyerSpeciality;
use App\Models\LawyerTime;
use Illuminate\Database\Seeder;
use App\Models\Speciality;
use App\Enums\WeekDaysEnum;
use App\Enums\StatusEnum;
use Faker\Factory as FakerFactory;


class LawyerTimeSeeder extends Seeder
{
    public function run()
    {
        $lawyers = Lawyer::all();
        $faker = FakerFactory::create();
        foreach($lawyers as $lawyer){
        $data = [
            [
                'lawyer_id' => $lawyer->id,
                'day' => WeekDaysEnum::SATURDAY,
                'from' => $faker->time(),
                'to' => $faker->time(),
                'status' => $faker->randomElement(StatusEnum::values()),
            ],
            [
                'lawyer_id' => $lawyer->id,
                'day' => WeekDaysEnum::SUNDAY,
                'from' => $faker->time(),
                'to' => $faker->time(),
                'status' => $faker->randomElement(StatusEnum::values()),
            ],
            [
                'lawyer_id' => $lawyer->id,
                'day' => WeekDaysEnum::MONDAY,
                'from' => $faker->time(),
                'to' => $faker->time(),
                'status' => $faker->randomElement(StatusEnum::values()),
            ],
            [
                'lawyer_id' => $lawyer->id,
                'day' => WeekDaysEnum::TUESDAY,
                'from' => $faker->time(),
                'to' => $faker->time(),
                'status' => $faker->randomElement(StatusEnum::values()),
            ],
            [
                'lawyer_id' => $lawyer->id,
                'day' => WeekDaysEnum::WEDNESDAY,
                'from' => $faker->time(),
                'to' => $faker->time(),
                'status' => $faker->randomElement(StatusEnum::values()),
            ],
            [
                'lawyer_id' => $lawyer->id,
                'day' => WeekDaysEnum::THURSDAY,
                'from' => $faker->time(),
                'to' => $faker->time(),
                'status' => $faker->randomElement(StatusEnum::values()),
            ],
            [
                'lawyer_id' => $lawyer->id,
                'day' => WeekDaysEnum::FRIDAY,
                'from' => $faker->time(),
                'to' => $faker->time(),
                'status' => $faker->randomElement(StatusEnum::values()),
            ],
        ];

        LawyerTime::insert($data);
    }
    }
}
