<?php

namespace Database\Factories;

use App\Enums\ReactionEnum;
use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\Blog;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class BlogReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => $this->faker->randomElement(Client::pluck('id')->toArray()),
            "user_type" => $this->faker->randomElement(UserTypeEnum::values()),
            'blog_id' => $this->faker->randomElement(Blog::pluck('id')->toArray()),
            'reaction' => $this->faker->randomElement(ReactionEnum::values()),
        ];
    }
}
