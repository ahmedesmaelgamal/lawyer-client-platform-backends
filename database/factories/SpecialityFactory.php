<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\City;
use App\Models\Country;
use App\Models\Level;
use App\Models\MarketProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class SpecialityFactory extends Factory
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
            'level_id' => fake()->randomElement(Level::pluck('id')->toArray()),
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ];
    }
}
