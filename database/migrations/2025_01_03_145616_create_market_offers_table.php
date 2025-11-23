<?php

use App\Enums\StatusEnum;
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
        Schema::create('market_offers', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->foreignId('market_product_id')->constrained('market_products')->onDelete('cascade');
            $table->date('from');
            $table->date('to');
            $table->enum('status', StatusEnum::values())->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_offers');
    }
};
