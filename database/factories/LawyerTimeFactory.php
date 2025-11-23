<?php

namespace Database\Factories;

use App\Enums\LawyerStatusEnum;
use App\Enums\StatusEnum;
use App\Enums\WeekDaysEnum;
use App\Models\CourtCase;
use App\Models\Lawyer;
use App\Models\Speciality;
use Illuminate\Database\Eloquent\Factories\Factory;


class LawyerTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            [
            'lawyer_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'speciality_id' => $this->faker->randomElement(Speciality::pluck('id')->toArray()),
            'day' => WeekDaysEnum::SATURDAY,
            'from' => $this->faker->time(),
            'to' => $this->faker->time(),
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ],
        [
            'lawyer_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'speciality_id' => $this->faker->randomElement(Speciality::pluck('id')->toArray()),
            'day' => WeekDaysEnum::SUNDAY,
            'from' => $this->faker->time(),
            'to' => $this->faker->time(),
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ],
        [
            'lawyer_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'speciality_id' => $this->faker->randomElement(Speciality::pluck('id')->toArray()),
            'day' => WeekDaysEnum::MONDAY,
            'from' => $this->faker->time(),
            'to' => $this->faker->time(),
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ],
        [
            'lawyer_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'speciality_id' => $this->faker->randomElement(Speciality::pluck('id')->toArray()),
            'day' => WeekDaysEnum::TUESDAY,
            'from' => $this->faker->time(),
            'to' => $this->faker->time(),
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ],
        [
            'lawyer_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'speciality_id' => $this->faker->randomElement(Speciality::pluck('id')->toArray()),
            'day' => WeekDaysEnum::WEDNESDAY,
            'from' => $this->faker->time(),
            'to' => $this->faker->time(),
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ],
        [
            'lawyer_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'speciality_id' => $this->faker->randomElement(Speciality::pluck('id')->toArray()),
            'day' => WeekDaysEnum::THURSDAY,
            'from' => $this->faker->time(),
            'to' => $this->faker->time(),
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ],
        [
            'lawyer_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'speciality_id' => $this->faker->randomElement(Speciality::pluck('id')->toArray()),
            'day' => WeekDaysEnum::FRIDAY,
            'from' => $this->faker->time(),
            'to' => $this->faker->time(),
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ],
    ];
    }
}
