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
        Schema::create('court_case_update_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_update_id')->constrained('court_case_updates')->cascadeOnDelete();
            $table->string('type');
            $table->string('file');
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_case_update_files');
    }
};
