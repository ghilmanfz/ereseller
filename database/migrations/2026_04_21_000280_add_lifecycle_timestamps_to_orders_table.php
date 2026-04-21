<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('processing_at')->nullable()->after('paid_at');
            $table->timestamp('shipped_at')->nullable()->after('processing_at');
            $table->timestamp('ready_for_pickup_at')->nullable()->after('shipped_at');
            $table->timestamp('completed_at')->nullable()->after('ready_for_pickup_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'processing_at',
                'shipped_at',
                'ready_for_pickup_at',
                'completed_at',
            ]);
        });
    }
};
