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
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('home_sort_order')->default(0)->after('show_homepage');
            $table->integer('pos_sort_order')->default(0)->after('home_sort_order');
            $table->integer('receipt_sort_order')->default(0)->after('pos_sort_order');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['home_sort_order', 'pos_sort_order', 'receipt_sort_order']);
        });
    }
};
