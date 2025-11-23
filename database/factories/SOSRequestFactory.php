<?php

namespace Database\Factories;

use App\Enums\SosRequestStatusEnum;
use App\Models\Client;
use App\Models\Lawyer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SOSRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'problem' => $this->faker->sentence,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'lat' => $this->faker->randomFloat(2, 0, 100),
            'long' => $this->faker->randomFloat(2, 0, 100),
            'status' => $this->faker->randomElement(SosRequestStatusEnum::values()),
            'lawyer_id' => fake()->randomElement(Lawyer::pluck('id')->toArray()),
            'client_id'=>fake()->randomElement(Client::pluck('id')->toArray())
        ];
    }
}
