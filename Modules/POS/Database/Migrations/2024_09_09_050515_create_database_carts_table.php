<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('database_carts', function (Blueprint $table) {
            $table->id();
            $table->string('instance')->unique();
            $table->json('value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('database_carts');
    }
};
