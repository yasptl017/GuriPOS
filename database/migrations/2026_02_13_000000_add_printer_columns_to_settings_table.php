<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'kitchen_printer')) {
                $table->string('kitchen_printer')->nullable();
            }

            if (!Schema::hasColumn('settings', 'desk_printer')) {
                $table->string('desk_printer')->nullable();
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('settings', 'kitchen_printer')) {
                $dropColumns[] = 'kitchen_printer';
            }

            if (Schema::hasColumn('settings', 'desk_printer')) {
                $dropColumns[] = 'desk_printer';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
