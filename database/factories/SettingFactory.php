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
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'key' => $this->faker->word(),
            'value' => $this->faker->word(),
       ];
    }
}
