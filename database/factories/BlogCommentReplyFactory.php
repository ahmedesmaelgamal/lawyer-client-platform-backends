<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\BlogComment;
use App\Models\BlogCommentReply;
use App\Models\Country;
use App\Models\Lawyer;
use App\Models\OfferPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class BlogCommentReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomElement(Lawyer::pluck('id')->toArray()),
            'user_type' => $this->faker->randomElement(UserTypeEnum::values()),
            'comment_id' => $this->faker->randomElement(BlogComment::pluck('id')->toArray()),
            'reply_id' => $this->faker->optional()->randomElement(BlogCommentReply::pluck('id')->toArray()),
            'reply' => $this->faker->sentence(),
        ];
}
}
