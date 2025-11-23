<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name')->unique()->whereNull('deleted_at');
            $table->string('code')->unique()->whereNull('deleted_at');
            $table->string('name');
            $table->string('email')->nullable()->unique()->whereNull('deleted_at');
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('otp')->nullable();
            $table->string('otp_expire_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
