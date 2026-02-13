<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            // Add columns only if they don't exist yet
            if (!Schema::hasColumn('print_jobs', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->index()->after('id');
            }
            if (!Schema::hasColumn('print_jobs', 'status')) {
                $table->tinyInteger('status')->default(0)->after('content'); // 0=pending,1=printed,2=failed
            }
            if (!Schema::hasColumn('print_jobs', 'printed_at')) {
                $table->timestamp('printed_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('print_jobs', 'error')) {
                $table->text('error')->nullable()->after('printed_at');
            }
            // If old 'printed' boolean column exists, we keep it for safety (don't drop)
        });
    }

    public function down(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->dropColumn(['status', 'printed_at', 'error']);
            if (Schema::hasColumn('print_jobs', 'order_id')) {
                $table->dropIndex(['order_id']);
                $table->dropColumn('order_id');
            }
        });
    }
};
