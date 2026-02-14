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
            if (!Schema::hasColumn('settings', 'print_mode')) {
                // 'poll' = agent polls every N seconds (default)
                // 'push' = server pushes directly to agent's local HTTP server
                $table->string('print_mode', 10)->default('poll')->after('desk_printer');
            }

            if (!Schema::hasColumn('settings', 'agent_port')) {
                // Local port the print agent listens on (push mode only)
                $table->unsignedSmallInteger('agent_port')->default(5757)->after('print_mode');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $drop = [];
            if (Schema::hasColumn('settings', 'print_mode')) {
                $drop[] = 'print_mode';
            }
            if (Schema::hasColumn('settings', 'agent_port')) {
                $drop[] = 'agent_port';
            }
            if ($drop) {
                $table->dropColumn($drop);
            }
        });
    }
};
