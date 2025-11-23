<?php

use App\Enums\OfficeRequestEnum;
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
        Schema::create('office_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('lawyers')->references('id')->cascadeOnDelete();
            $table->foreignId('lawyer_id')->constrained('lawyers')->references('id')->cascadeOnDelete();
            $table->enum('status', OfficeRequestEnum::values())->default(OfficeRequestEnum::NEW->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_requests');
    }
};
