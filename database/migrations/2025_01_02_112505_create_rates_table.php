<?php

use App\Enums\ReasonEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_case_id')->constrained('court_cases')->cascadeOnDelete();

            $table->unsignedBigInteger('from_user_id');
            $table->enum('from_user_type', UserTypeEnum::values());

            $table->unsignedBigInteger('to_user_id');
            $table->enum('to_user_type', UserTypeEnum::values());

            $table->tinyInteger(column: 'rate');
            $table->unsignedBigInteger('reason_id');
            $table->string('comment');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
