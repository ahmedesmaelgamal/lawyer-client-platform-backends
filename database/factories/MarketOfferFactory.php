<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\MarketProduct;
use App\Models\MarketProductCategory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class MarketOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => 'https://picsum.photos/200',
            'market_product_id' => fake()->randomElement(MarketProduct::pluck('id')->toArray()),
            'from' => Carbon::now(),
            'to' => Carbon::now()->addDays(30),
            'status' => fake()->randomElement(StatusEnum::values()),
        ];
    }
}
