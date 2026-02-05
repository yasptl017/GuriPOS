<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('p_o_s_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('meta');
            $table->json('cart');
            $table->json('resolved_order');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('p_o_s_tables');
    }
};
