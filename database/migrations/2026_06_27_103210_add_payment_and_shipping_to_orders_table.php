<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'waiting_confirmation' to status enum
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','waiting_confirmation','processing','shipped','completed','cancelled') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('notes');
            $table->string('payment_proof')->nullable()->after('payment_method');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_proof');
            $table->string('courier')->nullable()->after('payment_verified_at');
            $table->string('courier_service')->nullable()->after('courier');
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('courier_service');
            $table->string('tracking_number')->nullable()->after('shipping_cost');
            $table->timestamp('shipped_at')->nullable()->after('tracking_number');
        });
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','processing','shipped','completed','cancelled') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_proof',
                'payment_verified_at',
                'courier',
                'courier_service',
                'shipping_cost',
                'tracking_number',
                'shipped_at',
            ]);
        });
    }
};
