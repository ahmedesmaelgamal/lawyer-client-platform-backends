<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class OfferPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerAr = FakerFactory::create('ar_EG'); // create a Faker instance for Arabic
        return [
            'title' => [
                'en'=> fake()->word(),
                'ar'=> $fakerAr->word(),
            ],
            'number_of_days' => $this->faker->randomNumber(),
            'number_of_ads' => $this->faker->randomNumber(),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'discount' => $this->faker->randomNumber(),
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ];
    }
}
