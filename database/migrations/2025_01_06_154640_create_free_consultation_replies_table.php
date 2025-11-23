<?php

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
        Schema::create('free_consultation_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('free_consultation_request_id')->constrained('free_consultation_requests')->cascadeOnDelete();
            $table->longText('body');
            $table->foreignId('lawyer_id')->constrained('lawyers')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('free_consultation_replies');
    }
};
