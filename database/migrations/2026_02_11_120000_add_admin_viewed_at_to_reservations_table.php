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
        if (!Schema::hasColumn('reservations', 'admin_viewed_at')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->timestamp('admin_viewed_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('reservations', 'admin_viewed_at')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropColumn('admin_viewed_at');
            });
        }
    }
};

