<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\Blog;
use App\Models\Country;
use App\Models\Lawyer;
use App\Models\OfferPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class BlogCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'blog_id' => $this->faker->randomElement(Blog::pluck('id')->toArray()),
            'user_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'user_type' => $this->faker->randomElement(UserTypeEnum::values()),
            'comment' => $this->faker->paragraph(),
        ];
    }
}
