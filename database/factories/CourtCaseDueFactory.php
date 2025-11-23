<?php

namespace Database\Factories;

use App\Enums\UserTypeEnum;
use App\Models\Client;
use App\Models\CourtCase;
use App\Models\CourtCaseEvent;
use App\Models\Lawyer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CourtCaseDueFactory extends Factory
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
            'from_user_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'to_user_id' => $this->faker->randomElement(Client::pluck('id')->toArray()),
            'from_user_type' => $this->faker->randomElement(UserTypeEnum::values()),
            'to_user_type' => $this->faker->randomElement(UserTypeEnum::values()),
            'court_case_id' => $this->faker->randomElement(CourtCase::pluck('id')->toArray()),
            'court_case_event_id' => $this->faker->randomElement(CourtCaseEvent::pluck('id')->toArray()),
            'date' => $this->faker->date(),
            'paid' => $this->faker->boolean(),
            'price'=> rand(1000, 99999),
        ];
    }
}
