<?php

namespace Database\Factories;

use App\Enums\EventStatusEnum;
use App\Enums\StatusEnum;
use App\Models\CourtCase;
use App\Models\Lawyer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CourtCaseEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lawyer_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'status' => $this->faker->randomElement(EventStatusEnum::values()),
            'price' => $this->faker->randomFloat(2, 1000, 10000),
            'court_case_id' => $this->faker->randomElement(CourtCase::pluck('id')->toArray()),
        ];
    }
}
