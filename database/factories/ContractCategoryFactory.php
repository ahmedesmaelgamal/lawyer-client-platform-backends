<?php

namespace Database\Factories;

use App\Enums\AdConfirmationEnum;
use App\Enums\StatusEnum;
use App\Models\Country;
use App\Models\Lawyer;
use App\Models\OfferPackage;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class ContractCategoryFactory extends Factory
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
                'en'=> fake()->name(),
                'ar'=> $fakerAr->name(),
            ],
        ];
    }
}
