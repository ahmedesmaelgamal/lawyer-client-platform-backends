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
//            $table->decimal('contribution_price', 10, 2)->nullable()->after('partner_id')->comment('Contribution price');
            $table->text('contribution_comment')->nullable()->after('partner_id')->comment('Contribution comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('court_case_events', function (Blueprint $table) {
//            $table->dropColumn('contribution_price');
            $table->dropColumn('contribution_comment');
        });
    }
};
