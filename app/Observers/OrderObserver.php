<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\LoyaltyService;

class OrderObserver
{
    public function created(Order $order): void
    {
        if ($order->points_used > 0 && $order->user->loyaltyPoint) {
            $order->user->loyaltyPoint->deductPoints(
                $order->points_used,
                'spent_on_order',
                'order',
                $order->id,
                "Poin digunakan untuk pesanan #{$order->order_number}"
            );
        }
    }

    public function updated(Order $order): void
    {
        if ($order->wasChanged('status') && $order->status === 'completed') {
            app(LoyaltyService::class)->awardOrderPoints($order);
        }

        if ($order->wasChanged('status') && in_array($order->status, ['cancelled']) && $order->points_used > 0 && $order->user->loyaltyPoint) {
            $order->user->loyaltyPoint->addPoints(
                $order->points_used,
                'earned_from_refund',
                'order',
                $order->id,
                "Refund poin pesanan #{$order->order_number} (dibatalkan)"
            );
        }
    }

    public function deleted(Order $order): void
    {
        //
    }

    public function restored(Order $order): void
    {
        //
    }

    public function forceDeleted(Order $order): void
    {
        //
    }
}
