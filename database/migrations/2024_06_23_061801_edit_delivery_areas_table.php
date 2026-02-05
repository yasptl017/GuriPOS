<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('delivery_areas', function (Blueprint $table) {
            $table->dropColumn('area_name');
            $table->float('min_range');
            $table->float('max_range');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_areas', function (Blueprint $table) {
            $table->string('area_name')->nullable();
            $table->dropColumn('min_range');
            $table->dropColumn('max_range');
        });
    }
};
