<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::create([
            'code' => 'Pro-Diskon 30%',
            'type' => 'percentage',
            'value' => 30,
            'min_order' => 0,
            'max_discount' => 500000,
            'starts_at' => now(),
            'expires_at' => now()->addYear(),
            'usage_limit' => 0,
            'used_count' => 0,
            'is_active' => true,
        ]);
    }
}
