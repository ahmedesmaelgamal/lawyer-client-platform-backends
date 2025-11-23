<?php

namespace Database\Factories;

use App\Enums\MarketProductStatusEnum;
use App\Enums\StatusEnum;
use App\Models\MarketProduct;
use App\Models\MarketProductCategory;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class MarketProductFactory extends Factory
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
                'en'=> fake()->city(),
                'ar'=> $fakerAr->city(),
            ],
            'image' => 'https://picsum.photos/200/300',
            'description' => fake()->sentence(),
            'location' => fake()->address(),
            'stock' => fake()->numberBetween(1, 100),
            'price' => fake()->randomFloat(2, 1, 1000),
            'market_product_category_id' => fake()->randomElement(MarketProductCategory::pluck('id')->toArray()),
            'status' => fake()->randomElement(StatusEnum::values()),
        ];
    }
}
