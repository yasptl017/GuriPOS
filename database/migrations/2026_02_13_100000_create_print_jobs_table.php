<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('print_jobs')) {
            return; // Table already exists (created by a previous migration)
        }
        Schema::create('print_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->string('printer');          // 'kitchen' or 'desk'
            $table->longText('content');        // formatted receipt text
            $table->tinyInteger('status')->default(0); // 0=pending, 1=printed, 2=failed
            $table->timestamp('printed_at')->nullable();
            $table->text('error')->nullable();  // error message if failed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_jobs');
    }
};
