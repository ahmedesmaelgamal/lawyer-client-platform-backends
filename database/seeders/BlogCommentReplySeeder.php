<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogCommentReply;
use Illuminate\Database\Seeder;

class BlogCommentReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogCommentReply::factory()->count(50)->create();
    }
}
