<?php

namespace Database\Factories;

use App\Models\CourtCase;
use App\Models\Lawyer;
use App\Models\Speciality;
use Illuminate\Database\Eloquent\Factories\Factory;


class LawyerSpecialityFactory extends Factory
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
            'speciality_id' => $this->faker->randomElement(Speciality::pluck('id')->toArray()),
        ];
    }
}
