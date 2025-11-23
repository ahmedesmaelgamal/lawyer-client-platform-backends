<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\CommunitySubCategory;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommunityService>
 */
class CommunityServiceFactory extends Factory
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
            'community_sub_category_id' => CommunitySubCategory::factory(),
            'body' => [
                'en'=> fake()->paragraph(),
                'ar'=> $fakerAr->paragraph(),
            ],
            'status' => fake()->randomElement(StatusEnum::values()),
        ];
    }
}
