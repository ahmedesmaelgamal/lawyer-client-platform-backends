<?php

namespace Database\Factories;

use App\Enums\CourtCaseStatusEnum;
use App\Enums\StatusEnum;
use App\Models\Client;
use App\Models\CourtCase;
use App\Models\Speciality;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CourtCaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'client_id' => $this->faker->randomElement(Client::pluck('id')->toArray()),
            'case_estimated_price' => $this->faker->randomFloat(2, 1000, 10000),
            'case_number'=> $this->faker->randomNumber(9),
            'details' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(CourtCaseStatusEnum::values()),
            'speciality_id' => $this->faker->randomElement(Speciality::pluck('id')->toArray()),
            'created_at'=>fake()->dateTimeBetween(now()->subMonth(12),now())
        ];
    }
}
