<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommunityCategory>
 */
class CommunityCategoryFactory extends Factory
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
            'status' => fake()->randomElement(StatusEnum::values()),
        ];
    }
}
