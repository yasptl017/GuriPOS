<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Additional fields based on your schema
            $table->string('phone')->unique();
            $table->string('address');
            $table->integer('status')->default(0);
            $table->integer('email_verified')->default(0);
            $table->string('forget_password_token')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('provider')->nullable();
            $table->string('image')->nullable();
            $table->string('verify_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};