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
        Schema::table('client_points', function (Blueprint $table) {
            $table->string('commercial_code');
            $table->string('entered_with_code')->nullable();
            $table->integer('points')->default(0)->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_points', function (Blueprint $table) {
            $table->integer('points')->change();
            $table->dropColumn('commercial_code');
            $table->dropColumn('entered_with_code');
        });
    }
};
