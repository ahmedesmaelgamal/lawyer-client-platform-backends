<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Enums\StatusEnum;
use App\Models\Lawyer;
use App\Models\MarketProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'market_product_id' => $this->faker->randomElement(MarketProduct::pluck('id')->toArray()),
            'lawyer_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'qty' => $this->faker->numberBetween(1, 100),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'status'=> fake()->randomElement(OrderStatusEnum::values()),
            'total_price' => $this->faker->randomFloat(2, 10, 1000), // Replace 10 and 1000 with the appropriate range
            'created_at'=>fake()->dateTimeBetween(now()->subMonth(12),now())
        ];
    }
}
