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
        Schema::table('court_case_events', function (Blueprint $table) {
            $table->foreignId('lawyer_transferred_to_status')->after('transfer_lawyer_status')->nullable()->constrained('lawyers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('court_case_events', function (Blueprint $table) {
            $table->dropColumn('lawyer_transferred_to_status');
        });
    }
};
