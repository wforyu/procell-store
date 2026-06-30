<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $products = [
            // === LCD & Display ===
            ['category_slug' => 'lcd-display', 'brand' => 'Samsung', 'name' => 'LCD Samsung Galaxy A12', 'sku' => 'SAM-LCD-A12', 'buying_price' => 180000, 'selling_price' => 280000, 'stock' => 15],
            ['category_slug' => 'lcd-display', 'brand' => 'Samsung', 'name' => 'LCD Samsung Galaxy A52', 'sku' => 'SAM-LCD-A52', 'buying_price' => 350000, 'selling_price' => 500000, 'stock' => 10],
            ['category_slug' => 'lcd-display', 'brand' => 'iPhone', 'name' => 'LCD iPhone XR Original Hard OLED', 'sku' => 'IP-LCD-XR', 'buying_price' => 600000, 'selling_price' => 900000, 'stock' => 8],
            ['category_slug' => 'lcd-display', 'brand' => 'iPhone', 'name' => 'LCD iPhone 11 Pro Max OLED', 'sku' => 'IP-LCD-11PM', 'buying_price' => 750000, 'selling_price' => 1100000, 'stock' => 5],
            ['category_slug' => 'lcd-display', 'brand' => 'Xiaomi', 'name' => 'LCD Xiaomi Redmi Note 10', 'sku' => 'XM-LCD-RN10', 'buying_price' => 250000, 'selling_price' => 380000, 'stock' => 20],
            ['category_slug' => 'lcd-display', 'brand' => 'OPPO', 'name' => 'LCD OPPO A54', 'sku' => 'OP-LCD-A54', 'buying_price' => 220000, 'selling_price' => 350000, 'stock' => 12],
            ['category_slug' => 'lcd-display', 'brand' => 'Vivo', 'name' => 'LCD Vivo Y21', 'sku' => 'VO-LCD-Y21', 'buying_price' => 200000, 'selling_price' => 320000, 'stock' => 10],
            ['category_slug' => 'lcd-display', 'brand' => 'Realme', 'name' => 'LCD Realme C21', 'sku' => 'RM-LCD-C21', 'buying_price' => 170000, 'selling_price' => 280000, 'stock' => 14],

            // === Battery ===
            ['category_slug' => 'battery', 'brand' => 'Samsung', 'name' => 'Baterai Samsung Galaxy A12 EB-BA125ABY', 'sku' => 'SAM-BAT-A12', 'buying_price' => 80000, 'selling_price' => 140000, 'stock' => 25],
            ['category_slug' => 'battery', 'brand' => 'iPhone', 'name' => 'Baterai iPhone 11 Original 3110mAh', 'sku' => 'IP-BAT-11', 'buying_price' => 180000, 'selling_price' => 300000, 'stock' => 10],
            ['category_slug' => 'battery', 'brand' => 'iPhone', 'name' => 'Baterai iPhone XR Original 2942mAh', 'sku' => 'IP-BAT-XR', 'buying_price' => 160000, 'selling_price' => 270000, 'stock' => 8],
            ['category_slug' => 'battery', 'brand' => 'Xiaomi', 'name' => 'Baterai Xiaomi Redmi Note 10 BN67', 'sku' => 'XM-BAT-RN10', 'buying_price' => 70000, 'selling_price' => 130000, 'stock' => 30],
            ['category_slug' => 'battery', 'brand' => 'OPPO', 'name' => 'Baterai OPPO A54 BLP797', 'sku' => 'OP-BAT-A54', 'buying_price' => 75000, 'selling_price' => 135000, 'stock' => 20],
            ['category_slug' => 'battery', 'brand' => 'Samsung', 'name' => 'Baterai Samsung Galaxy A52 EB-BA525ABY', 'sku' => 'SAM-BAT-A52', 'buying_price' => 100000, 'selling_price' => 170000, 'stock' => 15],

            // === Flexible Cable ===
            ['category_slug' => 'flexible-cable', 'brand' => 'Samsung', 'name' => 'Flex LCD Samsung Galaxy A12', 'sku' => 'SAM-FLX-A12', 'buying_price' => 35000, 'selling_price' => 65000, 'stock' => 40],
            ['category_slug' => 'flexible-cable', 'brand' => 'iPhone', 'name' => 'Flex Charging iPhone XR/11/12', 'sku' => 'IP-FLX-CHG', 'buying_price' => 25000, 'selling_price' => 50000, 'stock' => 50],
            ['category_slug' => 'flexible-cable', 'brand' => 'Xiaomi', 'name' => 'Flex Volume + Power Xiaomi Redmi Note 10', 'sku' => 'XM-FLX-VP', 'buying_price' => 20000, 'selling_price' => 40000, 'stock' => 35],
            ['category_slug' => 'flexible-cable', 'brand' => 'OPPO', 'name' => 'Flex LCD OPPO A54', 'sku' => 'OP-FLX-A54', 'buying_price' => 30000, 'selling_price' => 55000, 'stock' => 25],
            ['category_slug' => 'flexible-cable', 'brand' => 'Samsung', 'name' => 'Flex Charging Samsung Galaxy A52', 'sku' => 'SAM-FLX-CHG', 'buying_price' => 28000, 'selling_price' => 52000, 'stock' => 30],

            // === Mainboard & IC ===
            ['category_slug' => 'mainboard-ic', 'brand' => 'iPhone', 'name' => 'IC Power Taptic Engine iPhone X/11/12', 'sku' => 'IP-IC-PWR', 'buying_price' => 120000, 'selling_price' => 200000, 'stock' => 10],
            ['category_slug' => 'mainboard-ic', 'brand' => 'iPhone', 'name' => 'IC Charging U3300 iPhone 11', 'sku' => 'IP-IC-CHG', 'buying_price' => 45000, 'selling_price' => 85000, 'stock' => 15],
            ['category_slug' => 'mainboard-ic', 'brand' => 'Samsung', 'name' => 'IC Power Samsung Galaxy A12', 'sku' => 'SAM-IC-PWR', 'buying_price' => 35000, 'selling_price' => 70000, 'stock' => 20],
            ['category_slug' => 'mainboard-ic', 'brand' => 'Xiaomi', 'name' => 'IC Charging Xiaomi Redmi Note 10', 'sku' => 'XM-IC-CHG', 'buying_price' => 30000, 'selling_price' => 60000, 'stock' => 18],
            ['category_slug' => 'mainboard-ic', 'brand' => 'Samsung', 'name' => 'IC Audio Samsung Galaxy A52', 'sku' => 'SAM-IC-AUD', 'buying_price' => 25000, 'selling_price' => 55000, 'stock' => 12],

            // === Button & Switch ===
            ['category_slug' => 'button-switch', 'brand' => 'Samsung', 'name' => 'Tombol Power + Volume Samsung A12', 'sku' => 'SAM-BTN-A12', 'buying_price' => 15000, 'selling_price' => 35000, 'stock' => 50],
            ['category_slug' => 'button-switch', 'brand' => 'iPhone', 'name' => 'Tombol Volume iPhone XR/11/12 Set', 'sku' => 'IP-BTN-VOL', 'buying_price' => 25000, 'selling_price' => 50000, 'stock' => 30],
            ['category_slug' => 'button-switch', 'brand' => 'Xiaomi', 'name' => 'Tombol Power Redmi Note 10', 'sku' => 'XM-BTN-PWR', 'buying_price' => 10000, 'selling_price' => 25000, 'stock' => 60],
            ['category_slug' => 'button-switch', 'brand' => 'OPPO', 'name' => 'Flex Tombol Volume OPPO A54', 'sku' => 'OP-BTN-A54', 'buying_price' => 12000, 'selling_price' => 28000, 'stock' => 40],
            ['category_slug' => 'button-switch', 'brand' => 'Vivo', 'name' => 'Flex Tombol Power Vivo Y21', 'sku' => 'VO-BTN-Y21', 'buying_price' => 12000, 'selling_price' => 28000, 'stock' => 35],

            // === Charger & Adapter ===
            ['category_slug' => 'charger-adapter', 'brand' => 'Samsung', 'name' => 'Charger Samsung 15W Type-C Original', 'sku' => 'SAM-CHG-15W', 'buying_price' => 55000, 'selling_price' => 100000, 'stock' => 25],
            ['category_slug' => 'charger-adapter', 'brand' => 'iPhone', 'name' => 'Charger iPhone 20W USB-C Original', 'sku' => 'IP-CHG-20W', 'buying_price' => 110000, 'selling_price' => 200000, 'stock' => 15],
            ['category_slug' => 'charger-adapter', 'brand' => 'Xiaomi', 'name' => 'Charger Xiaomi 33W Turbo Fast Charge', 'sku' => 'XM-CHG-33W', 'buying_price' => 65000, 'selling_price' => 120000, 'stock' => 20],
            ['category_slug' => 'charger-adapter', 'brand' => 'OPPO', 'name' => 'Charger OPPO 33W VOOC Flash Charge', 'sku' => 'OP-CHG-33W', 'buying_price' => 70000, 'selling_price' => 130000, 'stock' => 18],
            ['category_slug' => 'charger-adapter', 'brand' => 'Samsung', 'name' => 'Charger Samsung 25W Super Fast Charge', 'sku' => 'SAM-CHG-25W', 'buying_price' => 75000, 'selling_price' => 140000, 'stock' => 22],

            // === Data Cable ===
            ['category_slug' => 'data-cable', 'brand' => 'Samsung', 'name' => 'Kabel Data Samsung Type-C 1M Original', 'sku' => 'SAM-CBL-TC', 'buying_price' => 25000, 'selling_price' => 50000, 'stock' => 40],
            ['category_slug' => 'data-cable', 'brand' => 'iPhone', 'name' => 'Kabel Data iPhone Lightning 1M Original', 'sku' => 'IP-CBL-LT', 'buying_price' => 35000, 'selling_price' => 70000, 'stock' => 35],
            ['category_slug' => 'data-cable', 'brand' => 'Xiaomi', 'name' => 'Kabel Data Xiaomi Type-C 1M Original', 'sku' => 'XM-CBL-TC', 'buying_price' => 15000, 'selling_price' => 35000, 'stock' => 50],
            ['category_slug' => 'data-cable', 'brand' => 'OPPO', 'name' => 'Kabel Data OPPO Type-C VOOC 1M', 'sku' => 'OP-CBL-VOOC', 'buying_price' => 20000, 'selling_price' => 45000, 'stock' => 30],
            ['category_slug' => 'data-cable', 'brand' => 'Vivo', 'name' => 'Kabel Data Vivo Type-C 1M Original', 'sku' => 'VO-CBL-TC', 'buying_price' => 15000, 'selling_price' => 35000, 'stock' => 45],

            // === Accessories ===
            ['category_slug' => 'accessories', 'brand' => 'Samsung', 'name' => 'Tempered Glass Samsung Galaxy A12', 'sku' => 'SAM-TG-A12', 'buying_price' => 8000, 'selling_price' => 20000, 'stock' => 100],
            ['category_slug' => 'accessories', 'brand' => 'iPhone', 'name' => 'Tempered Glass iPhone 11/XR Anti Spy', 'sku' => 'IP-TG-11', 'buying_price' => 12000, 'selling_price' => 30000, 'stock' => 80],
            ['category_slug' => 'accessories', 'brand' => 'Xiaomi', 'name' => 'Soft Case Xiaomi Redmi Note 10 Transparent', 'sku' => 'XM-CASE-RN10', 'buying_price' => 10000, 'selling_price' => 25000, 'stock' => 60],
            ['category_slug' => 'accessories', 'brand' => 'Samsung', 'name' => 'Hard Case Samsung Galaxy A52 Silicone', 'sku' => 'SAM-CASE-A52', 'buying_price' => 15000, 'selling_price' => 35000, 'stock' => 45],
            ['category_slug' => 'accessories', 'brand' => 'iPhone', 'name' => 'Soft Case iPhone 12/12 Pro Transparent', 'sku' => 'IP-CASE-12', 'buying_price' => 20000, 'selling_price' => 45000, 'stock' => 50],
            ['category_slug' => 'accessories', 'brand' => 'OPPO', 'name' => 'Tempered Glass OPPO A54', 'sku' => 'OP-TG-A54', 'buying_price' => 8000, 'selling_price' => 20000, 'stock' => 70],
        ];

        foreach ($products as $data) {
            $category = $categories->get($data['category_slug']);
            if (! $category) {
                continue;
            }

            Product::create([
                'category_id' => $category->id,
                'name' => $data['name'],
                'brand' => $data['brand'],
                'slug' => Str::slug($data['name']),
                'sku' => $data['sku'],
                'buying_price' => $data['buying_price'],
                'selling_price' => $data['selling_price'],
                'stock' => $data['stock'],
                'min_stock' => 5,
                'unit' => 'pcs',
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ '.count($products).' sample products seeded!');
    }
}
