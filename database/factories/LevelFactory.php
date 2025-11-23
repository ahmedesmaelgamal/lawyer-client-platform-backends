<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class LevelFactory extends Factory
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
            'salary' => fake()->numerify('#####'),
        ];
    }
}
