<?php

use App\Enums\SosRequestStatusEnum;
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
        Schema::create('s_o_s__requests', function (Blueprint $table) {
            $table->id();
            $table->string('problem');
            $table->string('phone');
            $table->string('address');
            $table->text('lat');
            $table->text('long');
            $table->enum('status', SosRequestStatusEnum::values());
            $table->string('voice')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete();
            $table->foreignId('lawyer_id')->nullable()->constrained('lawyers')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_o_s__requests');
    }
};
