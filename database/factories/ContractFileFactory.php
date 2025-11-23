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
class ContractFileFactory extends Factory
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

            'file_path'=>fake()->name(),
            'file_name' => [
                'en' => fake()->name(),
                'ar' => $fakerAr->name(),
            ],
            'file_extension'=>fake()->name(),
            'contract_category_id'=>fake()->numberBetween(1,10)
        ];
    }
}
