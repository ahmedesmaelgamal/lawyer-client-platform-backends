<?php

use App\Enums\AdConfirmationEnum;
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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained('lawyers')->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('lawyer_packages')->references('id')->cascadeOnDelete();
            $table->enum('status', array_column(StatusEnum::cases(), 'value'));
            $table->date('from_date')->comment("Start date of the ad");
            $table->date('to_date')->comment("End date of the ad");
            $table->text('image');
            $table->enum('ad_confirmation', AdConfirmationEnum::values())->default(AdConfirmationEnum::REQUESTED->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
