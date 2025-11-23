<?php

namespace Database\Factories;

use App\Enums\CurrencyEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {



        return [
            'title' => [
                'en' => $this->faker->country,
                'ar' => 'بلد', // Placeholder Arabic translation for country
            ],
            'currency' => CurrencyEnum::EGP->value,
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ];
    }

    public function egypt(): array
    {
        return [
            'title' => [
                'en' => 'Egypt',
                'ar' => 'مصر',
            ],
            'currency' => CurrencyEnum::EGP->value,
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ];
    }

    public function qatar(): array
    {
        return [
            'title' => [
                'en' => 'Qatar',
                'ar' => 'قطر',
            ],
            'currency' => CurrencyEnum::QAR->value,
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ];
    }

    public function morocco(): array
    {
        return [
            'title' => [
                'en' => 'Morocco',
                'ar' => 'المغرب',
            ],
            'currency' => CurrencyEnum::MAD->value,
            'status' => $this->faker->randomElement(StatusEnum::values()),
        ];
    }
}
