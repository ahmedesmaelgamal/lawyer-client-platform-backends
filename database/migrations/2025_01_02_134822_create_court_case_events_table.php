<?php

use App\Enums\EventStatusEnum;
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
        Schema::create('court_case_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained('lawyers')->cascadeOnDelete();
            $table->enum('status',EventStatusEnum::values());
            $table->double('price');
            $table->foreignId(column: 'court_case_id')->constrained('court_cases')->cascadeOnDelete();
            $table->foreignId('refuse_reason_id')->nullable()->constrained('refuse_reasons')->cascadeOnDelete();
            $table->text('refuse_note')->nullable();
            $table->foreignId( 'cancel_reason_id')->nullable()->constrained('refuse_reasons')->cascadeOnDelete();
            $table->text('cancel_note')->nullable();
            $table->foreignId('transfer_lawyer_id')->nullable()->constrained('lawyers');
            $table->tinyInteger('transfer_client_status')->default(0)->comment('new -> 0 , accepted -> 1 , rejected -> 2');
            $table->tinyInteger('transfer_lawyer_status')->default(0)->comment('new -> 0 , accepted -> 1 , rejected -> 2');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_case_events');
    }
};
