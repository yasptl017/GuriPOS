<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('orders', 'order_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('order_type')->after('order_status')->default('Pickup');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('orders', 'order_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('order_type');
            });
        }
    }
};
