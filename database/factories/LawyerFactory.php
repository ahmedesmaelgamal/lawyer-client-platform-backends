<?php

namespace Database\Factories;

use App\Enums\LawyerStatusEnum;
use App\Enums\StatusEnum;
use App\Models\Country;
use App\Models\City;
use App\Models\Level;
use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lawyer>
 */
class LawyerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email' => fake()->unique()->safeEmail(),
            'status' => fake()->randomElement(StatusEnum::values()),
            // 'image' => fake()->text(maxNbChars: 20),
            'image' => 'https://picsum.photos/200/300',
            'national_id' => fake()->unique()->uuid(),
//            'country_id' => fake()->numberBetween(0, 9),
            'country_id' => fake()->randomElement(Country::pluck('id')->toArray()),
            'city_id' => fake()->randomElement(City::pluck('id')->toArray()),
            'phone' => fake()->numerify('###############'),
            'country_code' => $this->faker->numerify('+###'),
            'lawyer_id' => fake()->unique()->uuid(),
            'type' => fake()->randomElement(LawyerStatusEnum::values()),
//            'level_id' => fake()->numberBetween(0, 9),
            'level_id' => fake()->randomElement(Level::pluck('id')->toArray()),
        ];
    }
}
