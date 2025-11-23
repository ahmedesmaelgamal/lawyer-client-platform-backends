<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RefuseReason>
 */
class RefuseReasonFactory extends Factory
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
            "name" => [
                'en' => $this->faker->title(),
                'ar'=> $fakerAr->title(),
            ],
            'type' => $this->faker->randomElement(['cancel','complete']),
        ];
    }
}
