<?php

namespace Database\Factories;

use App\Enums\UserTypeEnum;

use App\Models\CourtCase;
use App\Models\Lawyer;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class WalletTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
//            'case_id' => fake()->randomElement(CourtCase::pluck('id')->toArray()),
            'user_type' => fake()->randomElement(UserTypeEnum::values()),
            'user_id' => fake()->randomElement(Lawyer::pluck('id')->toArray()),
            'credit' => fake()->numberBetween(0,150000),
            'debit' => fake()->numberBetween(0,150000),
            'comment'=> 'تم تحويل المبلغ الى حسابك بنجاح',
        ];
    }
}
