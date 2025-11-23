<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\Lawyer;
use App\Models\MarketProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'user_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'user_type' => $this->faker->randomElement(UserTypeEnum::values()),
        ];
    }
}
