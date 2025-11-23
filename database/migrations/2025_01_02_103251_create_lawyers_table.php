<?php

use App\Enums\LawyerStatusEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('lawyers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('password');
            $table->string('email')->unique();
            $table->enum('status',StatusEnum::values() );
            $table->string('image')->nullable();
            $table->string('national_id', 191);
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->string('phone', 15);
            $table->string('country_code')->nullable();
            $table->string('lawyer_id', 191)->comment('رقم قيد المحامي');
            $table->enum('type',LawyerStatusEnum::values())->comment(' individual /  office');
            $table->foreignId('level_id')->constrained('levels')->cascadeOnDelete();
            $table->foreignId('office_id')->nullable()->constrained('lawyers');
            $table->double('wallet',10,2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyers');
    }
};
