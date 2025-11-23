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
        Schema::create('court_case_dues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('from_user_id');
            $table->unsignedBigInteger('to_user_id');
            $table->enum('from_user_type', UserTypeEnum::values());
            $table->enum('to_user_type', UserTypeEnum::values());
            $table->foreignId('court_case_id')->constrained('court_cases')->cascadeOnDelete();
            $table->foreignId('court_case_event_id')->constrained('court_case_events')->cascadeOnDelete();
            $table->date('date');
            $table->double('price',10,2);
            $table->tinyInteger('paid')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_case_dues');
    }
};
