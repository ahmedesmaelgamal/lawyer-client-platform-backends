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
class ClientPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => $this->faker->unique()->numberBetween(1, 10),
            'points' => 0,
            'commercial_code' => $this->faker->lexify('??????????'),
            'entered_with_code' => null,
        ];
    }
}
