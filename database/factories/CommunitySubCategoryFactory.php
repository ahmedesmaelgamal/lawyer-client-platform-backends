<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\CommunityCategory;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommunitySubCategory>
 */
class CommunitySubCategoryFactory extends Factory
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
            'community_category_id' => fake()->randomElement(CommunityCategory::pluck('id')->toArray()),
            'title' => [
                'en'=> fake()->word(),
                'ar'=> $fakerAr->word(),
            ],
            'status' => fake()->randomElement(StatusEnum::values()),
        ];
    }
}
