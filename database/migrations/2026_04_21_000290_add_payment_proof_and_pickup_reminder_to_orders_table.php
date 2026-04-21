<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_proof_path')->nullable()->after('tracking_number');
            $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof_path');
            $table->timestamp('pickup_ready_reminded_at')->nullable()->after('ready_for_pickup_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_proof_path',
                'payment_proof_uploaded_at',
                'pickup_ready_reminded_at',
            ]);
        });
    }
};
