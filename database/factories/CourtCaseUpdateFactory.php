<?php

namespace Database\Factories;

use App\Models\CourtCase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CourtCaseUpdateFactory extends Factory
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
            'court_case_id' => $this->faker->randomElement(CourtCase::pluck('id')->toArray()),
            'details' => $this->faker->paragraph,
            'date' => $this->faker->date,
        ];
    }
}
