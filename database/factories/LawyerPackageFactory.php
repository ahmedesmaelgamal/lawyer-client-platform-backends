<?php

namespace Database\Factories;

use App\Enums\ExpireEnum;
use App\Enums\LawyerStatusEnum;
use App\Enums\StatusEnum;
use App\Models\Country;
use App\Models\City;
use App\Models\Lawyer;
use App\Models\Level;
use App\Models\OfferPackage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Testing\Fakes\Fake;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lawyer>
 */
class LawyerPackageFactory extends Factory
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
                'package_id' => fake()->randomElement(OfferPackage::pluck('id')->toArray()),
                'start_date' => fake()->date('Y-m-d'),
                'end_date' => fake()->date('Y-m-d'),
                'number_of_bumps' => fake()->numberBetween(),
                'status' => fake()->randomElement(StatusEnum::values()),
                'is_expired' => fake()->randomElement(ExpireEnum::values()),
            ];
        }

}

