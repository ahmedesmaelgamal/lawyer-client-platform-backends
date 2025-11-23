<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Enums\ReasonEnum;
use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\CourtCase;
use App\Models\Lawyer;
use App\Models\MarketProduct;
use App\Models\RefuseReason;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class RateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'court_case_id' => $this->faker->randomElement(CourtCase::pluck('id')->toArray()),
            'from_user_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'from_user_type' => $this->faker->randomElement(UserTypeEnum::values()),
            'to_user_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'to_user_type' => $this->faker->randomElement(UserTypeEnum::values()),
            'rate' => $this->faker->numberBetween(1, 5),
            'reason_id' => $this->faker->numberBetween(1, 10),
            'comment' => $this->faker->text(20),
        ];
    }
}
