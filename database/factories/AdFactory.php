<?php

namespace Database\Factories;

use App\Enums\AdConfirmationEnum;
use App\Enums\StatusEnum;
use App\Models\Country;
use App\Models\Lawyer;
use App\Models\OfferPackage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class AdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lawyer_id' => fake()->randomElement(Lawyer::pluck('id')->toArray()),
            'status' => fake()->randomElement(array_column(StatusEnum::cases(), 'value')),
            'from_date' => Carbon::now(),
            'to_date' => Carbon::now()->addDays(30),
            'image' => 'https://picsum.photos/200',
            'package_id' => fake()->randomElement(OfferPackage::pluck('id')->toArray()),
            'ad_confirmation' => fake()->randomElement(AdConfirmationEnum::values()),
        ];
    }
}
