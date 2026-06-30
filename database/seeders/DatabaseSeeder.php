<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@procell.com',
            'password' => bcrypt('admin123'),
            'phone' => '081234567890',
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@procell.com',
            'password' => bcrypt('customer123'),
            'phone' => '081234567891',
            'is_admin' => false,
        ]);

        $categories = [
            ['name' => 'LCD & Display', 'slug' => 'lcd-display', 'description' => 'Layar LCD dan touchscreen untuk berbagai merek HP'],
            ['name' => 'Battery', 'slug' => 'battery', 'description' => 'Baterai original dan replacement'],
            ['name' => 'Flexible Cable', 'slug' => 'flexible-cable', 'description' => 'Kabel fleksibel untuk berbagai komponen HP'],
            ['name' => 'Mainboard & IC', 'slug' => 'mainboard-ic', 'description' => 'Motherboard, IC power, IC charging dan komponen board lainnya'],
            ['name' => 'Button & Switch', 'slug' => 'button-switch', 'description' => 'Tombol power, volume, dan switch ON/OFF'],
            ['name' => 'Charger & Adapter', 'slug' => 'charger-adapter', 'description' => 'Travel charger, adapter daya dan komponen charging'],
            ['name' => 'Data Cable', 'slug' => 'data-cable', 'description' => 'Kabel data USB Type-C, Micro USB, dan Lightning'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Aksesoris HP lainnya seperti casing, tempered glass, dan lain-lain'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $settings = [
            ['key' => 'store_name', 'value' => 'ProCell Store'],
            ['key' => 'store_description', 'value' => 'Toko Sparepart & Aksesoris HP Terlengkap'],
            ['key' => 'store_email', 'value' => 'info@procell.com'],
            ['key' => 'store_phone', 'value' => '081234567890'],
            ['key' => 'store_address', 'value' => 'Jl. Contoh No. 123, Jakarta'],
            ['key' => 'meta_description', 'value' => 'Toko sparepart dan aksesoris HP terlengkap. LCD, baterai, flex cable, charger dan aksesoris smartphone berkualitas harga terjangkau.'],
            ['key' => 'meta_keywords', 'value' => 'sparepart hp, aksesoris hp, lcd hp, baterai hp, charger hp, service hp, toko sparepart'],
            ['key' => 'footer_text', 'value' => '© 2026 ProCell Store. All rights reserved.'],
            ['key' => 'footer_description', 'value' => 'Toko online terpercaya untuk sparepart dan aksesoris handphone. Kami menyediakan berbagai komponen berkualitas dengan harga terjangkau.'],
            ['key' => 'whatsapp_number', 'value' => '6281234567890'],
            ['key' => 'store_hours', 'value' => 'Sen-Sab 08:00 - 17:00'],
            ['key' => 'store_is_closed', 'value' => 'false'],
            ['key' => 'store_closed_message', 'value' => 'Toko sedang libur, kembali buka Senin pukul 08:00'],
            ['key' => 'flash_sale_text', 'value' => 'Flash Sale Akhir Pekan!'],
            ['key' => 'mail_mailer', 'value' => 'log'],
            ['key' => 'mail_host', 'value' => ''],
            ['key' => 'mail_port', 'value' => '587'],
            ['key' => 'mail_username', 'value' => ''],
            ['key' => 'mail_password', 'value' => ''],
            ['key' => 'mail_encryption', 'value' => 'tls'],
            ['key' => 'mail_from_address', 'value' => ''],
            ['key' => 'mail_from_name', 'value' => ''],
            ['key' => 'rajaongkir_api_key', 'value' => ''],
            ['key' => 'store_origin_city', 'value' => ''],
            ['key' => 'points_earn_rate', 'value' => '1000'],
            ['key' => 'points_redeem_rate', 'value' => '100'],
            ['key' => 'points_referral_bonus', 'value' => '500'],
            ['key' => 'min_points_redeem', 'value' => '100'],
            ['key' => 'midtrans_server_key', 'value' => ''],
            ['key' => 'midtrans_client_key', 'value' => ''],
            ['key' => 'midtrans_is_production', 'value' => '0'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        $this->call([
            ProductSeeder::class,
            BankAccountSeeder::class,
            PageSeeder::class,
            CouponSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
