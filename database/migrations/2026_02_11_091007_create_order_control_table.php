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
        Schema::create('order_control', function (Blueprint $table) {
            $table->id();
            $table->boolean('pickup_enabled')->default(1);
            $table->text('pickup_disabled_message')->nullable();
            $table->boolean('delivery_enabled')->default(1);
            $table->text('delivery_disabled_message')->nullable();
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
        Schema::dropIfExists('order_control');
    }
};
