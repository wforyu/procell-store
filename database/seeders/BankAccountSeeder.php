<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        BankAccount::create([
            'bank_name' => 'Bank Mandiri',
            'account_number' => '1234567890',
            'account_holder' => 'PT ProCell Store',
            'is_active' => true,
            'sort' => 1,
        ]);

        BankAccount::create([
            'bank_name' => 'Bank BCA',
            'account_number' => '0987654321',
            'account_holder' => 'PT ProCell Store',
            'is_active' => true,
            'sort' => 2,
        ]);

        BankAccount::create([
            'bank_name' => 'Bank BRI',
            'account_number' => '5556667777',
            'account_holder' => 'PT ProCell Store',
            'is_active' => true,
            'sort' => 3,
        ]);
    }
}
