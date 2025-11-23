<?php

use App\Enums\MarketProductStatusEnum;
use App\Enums\OrderStatusEnum;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_product_id')->constrained(table: 'market_products')->cascadeOnDelete();
            $table->foreignId('lawyer_id')->constrained(table: 'lawyers')->cascadeOnDelete();
            $table->integer('qty');
            $table->string('phone');
            $table->string('address');
            $table->double('total_price');
            $table->enum('status', OrderStatusEnum::values());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
