<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogReaction;
use Illuminate\Database\Seeder;

class BlogReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogReaction::factory()->count(50)->create();
    }
}
