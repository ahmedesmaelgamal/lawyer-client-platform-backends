<?php

use App\Enums\UserTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_comment_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('user_type',UserTypeEnum::values());
            $table->foreignId('comment_id')->nullable()->constrained('blog_comments')->cascadeOnDelete();
            $table->foreignId('reply_id')->nullable()->constrained('blog_comment_replies')->cascadeOnDelete();
            $table->text('reply');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comment_replies');
    }
};
