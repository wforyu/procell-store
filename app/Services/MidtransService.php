<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class MidtransService
{
    public function configure(): void
    {
        Config::$serverKey = Setting::getValue('midtrans_server_key');
        Config::$clientKey = Setting::getValue('midtrans_client_key');
        Config::$isProduction = (bool) Setting::getValue('midtrans_is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function isConfigured(): bool
    {
        $serverKey = Setting::getValue('midtrans_server_key');

        return ! empty($serverKey);
    }

    public function getSnapToken(Order $order): string
    {
        $this->configure();

        $items = $order->items->map(fn ($item) => [
            'id' => (string) $item->product_id,
            'price' => (int) $item->price,
            'quantity' => $item->quantity,
            'name' => substr($item->product?->name ?? 'Produk', 0, 50),
        ])->toArray();

        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
            ];
        }

        if ($order->discount_amount > 0) {
            $items[] = [
                'id' => 'DISCOUNT',
                'price' => (int) (-$order->discount_amount),
                'quantity' => 1,
                'name' => 'Diskon Kupon',
            ];
        }

        if ($order->points_discount > 0) {
            $items[] = [
                'id' => 'POINTS',
                'price' => (int) (-$order->points_discount),
                'quantity' => 1,
                'name' => 'Diskon Poin',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->grand_total,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->customer?->name ?? 'Pembeli',
                'email' => $order->customer?->email ?? '',
                'phone' => $order->customer?->phone ?? '',
            ],
            'callbacks' => [
                'finish' => route('midtrans.finish', $order),
                'error' => route('midtrans.finish', $order),
                'pending' => route('midtrans.finish', $order),
            ],
        ];

        return Snap::getSnapToken($params);
    }

    public function getRedirectUrl(Order $order): string
    {
        $this->configure();
        $token = $this->getSnapToken($order);

        return Snap::getRedirectUrl($token);
    }

    public function handleNotification(array $notification): ?Order
    {
        $this->configure();

        $notif = new Notification;

        $transactionStatus = $notif->transaction_status;
        $fraudStatus = $notif->fraud_status;
        $orderId = $notif->order_id;

        $order = Order::where('order_number', $orderId)->first();

        if (! $order) {
            return null;
        }

        $status = $order->status;

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $status = 'processing';
            } elseif ($fraudStatus === 'accept') {
                $status = 'processing';
            }
        } elseif ($transactionStatus === 'settlement') {
            $status = 'processing';
        } elseif ($transactionStatus === 'pending') {
            $status = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            $status = 'cancelled';
        } elseif ($transactionStatus === 'refund' || $transactionStatus === 'partial_refund') {
            $status = 'cancelled';
        }

        $order->update([
            'status' => $status,
            'midtrans_transaction_id' => $notif->transaction_id,
            'midtrans_payment_type' => $notif->payment_type,
            'payment_verified_at' => in_array($status, ['processing', 'completed'], true) ? now() : $order->payment_verified_at,
        ]);

        return $order;
    }
}
