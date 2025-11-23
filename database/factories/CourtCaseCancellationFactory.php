<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\CourtCase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CourtCaseCancellationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'court_case_id' => $this->faker->randomElement(CourtCase::pluck('id')->toArray()),
            'accept_lawyer' => $this->faker->boolean,
            'accept_client' => $this->faker->boolean,
        ];
    }
}
