<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class InitReferralCodes extends Command
{
    protected $signature = 'loyalty:init';

    protected $description = 'Generate referral codes and loyalty points for all existing users';

    public function handle(): int
    {
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            $user->initReferralCode();
            $count++;
        }

        $this->info("Initialized referral codes and loyalty points for {$count} users.");

        return self::SUCCESS;
    }
}
