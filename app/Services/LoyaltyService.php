<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use App\Models\User;

class LoyaltyService
{
    public function getEarnRate(): int
    {
        return (int) (Setting::getValue('points_earn_rate', '1000'));
    }

    public function getRedeemRate(): int
    {
        return (int) (Setting::getValue('points_redeem_rate', '100'));
    }

    public function getReferralBonus(): int
    {
        return (int) (Setting::getValue('points_referral_bonus', '500'));
    }

    public function getMinRedeem(): int
    {
        return (int) (Setting::getValue('min_points_redeem', '100'));
    }

    public function calculateEarnablePoints(int $totalAmount): int
    {
        $rate = $this->getEarnRate();

        return $rate > 0 ? (int) floor($totalAmount / $rate) : 0;
    }

    public function calculateRedeemDiscount(int $points, int $orderTotal): int
    {
        $rate = $this->getRedeemRate();
        $maxDiscount = $orderTotal * 0.5;

        $discount = $points * $rate;

        return (int) min($discount, $maxDiscount);
    }

    public function awardOrderPoints(Order $order): void
    {
        if ($order->status !== 'completed') {
            return;
        }

        $points = $this->calculateEarnablePoints((int) $order->total_amount);

        if ($points <= 0) {
            return;
        }

        $user = $order->user;
        if (! $user->loyaltyPoint) {
            $user->initReferralCode();
        }

        $user->loyaltyPoint->addPoints(
            $points,
            'earned_from_order',
            'order',
            $order->id,
            "Poin dari pesanan #{$order->order_number}"
        );

        $order->updateQuietly(['points_earned' => $points]);
    }

    public function awardReferralBonus(User $newUser): void
    {
        $bonus = $this->getReferralBonus();
        $referrer = User::find($newUser->referred_by);

        if (! $referrer || ! $referrer->loyaltyPoint) {
            return;
        }

        $referrer->loyaltyPoint->addPoints(
            $bonus,
            'earned_from_referral',
            'user',
            $newUser->id,
            "Bonus referral dari {$newUser->name}"
        );

        $referrer->referralCode->increment('total_referrals');
        $referrer->referralCode->increment('total_points_earned', $bonus);
    }
}
