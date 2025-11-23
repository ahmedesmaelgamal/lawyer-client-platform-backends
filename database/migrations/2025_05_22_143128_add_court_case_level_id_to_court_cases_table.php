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
        Schema::table('court_cases', function (Blueprint $table) {
            $table->unsignedBigInteger('court_case_level_id')->constrained('court_case_levels')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('court_cases', function (Blueprint $table) {
            $table->dropColumn('court_case_level_id');
        });
    }
};
