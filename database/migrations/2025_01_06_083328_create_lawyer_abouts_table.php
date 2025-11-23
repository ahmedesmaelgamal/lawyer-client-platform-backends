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
        Schema::create('lawyer_abouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained('lawyers')->cascadeOnDelete();
            $table->longText('about')->nullable();
            $table->double('consultation_fee')->nullable()->comment('سعر الإستشارة المجانيه');
            $table->double('attorney_fee')->nullable()->comment('سعر التوكيل في القضيه');
            $table->text('office_address')->nullable();
            $table->text('lat')->nullable();
            $table->text('lng')->nullable();
            $table->bigInteger('success_case')->default(0)->comment('القضايا الناجحة');
            $table->bigInteger('failed_case')->default(0)->comment('القضايا الخاسرة');
            $table->enum('public_work',StatusEnum::values())->default(StatusEnum::INACTIVE->value)->comment('العمل العام');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyer_abouts');
    }
};
