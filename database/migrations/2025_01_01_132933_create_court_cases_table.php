<?php

use App\Enums\CourtCaseStatusEnum;
use App\Enums\CourtCaseTypeEnum;
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
        Schema::create('court_cases', function (Blueprint $table) {
            $table->id();
            $table->enum('type', CourtCaseTypeEnum::values())->default(CourtCaseTypeEnum::DEFAULT->value);
            $table->string('title');
            $table->double('case_estimated_price')->default(0);
            $table->unsignedBigInteger('case_number');
            $table->longText('details');
            $table->enum('status', CourtCaseStatusEnum::values())->comment("");
            $table->boolean('seen')->default(true)->comment("updates seen by the client");
            $table->double('case_final_price')->default(0);
            $table->foreignId('speciality_id')->constrained('specialities')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('case_speciality_id')->nullable()->constrained('case_specializations')->cascadeOnDelete();
            $table->foreignId('sub_case_speciality_id')->nullable()->constrained('sub_case_specializations')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_cases');
    }
};
