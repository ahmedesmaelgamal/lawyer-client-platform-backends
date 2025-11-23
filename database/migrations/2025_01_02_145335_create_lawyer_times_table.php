<?php

use App\Enums\LawyerStatusEnum;
use App\Enums\StatusEnum;
use App\Enums\WeekDaysEnum;
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
        Schema::create('lawyer_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained()->cascadeOnDelete();
            $table->enum('day',WeekDaysEnum::values())->comment("work day (all the days will be assigned to the lawyer after registration)");
            $table->time('from')->comment("start of work time");
            $table->time('to')->comment("end of work time");
            $table->enum('status',StatusEnum::values())->comment("status of the lawyer time active / inactive in this day");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyer_times');
    }
};
