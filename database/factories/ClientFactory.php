<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\City;
use App\Models\Country;
use App\Models\MarketProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'national_id' => $this->faker->numerify('##########'),
            'phone' => $this->faker->optional()->phoneNumber(),
            'country_code' => $this->faker->numerify('+###'),
            'points' => $this->faker->numberBetween(0, 1000),
            'city_id' => fake()->randomElement(City::pluck('id')->toArray()),
            'country_id' => fake()->randomElement(Country::pluck('id')->toArray()),
            'status' => $this->faker->randomElement(StatusEnum::values()),
            'image'=>'https://picsum.photos/200/300',
        ];
    }
}
