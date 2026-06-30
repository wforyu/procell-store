<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('points_used')->default(0)->after('discount_amount');
            $table->unsignedBigInteger('points_discount')->default(0)->after('points_used');
            $table->integer('points_earned')->default(0)->after('points_discount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['points_used', 'points_discount', 'points_earned']);
        });
    }
};
