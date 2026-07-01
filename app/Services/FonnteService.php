<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Returns;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class FonnteService
{
    protected ?string $apiKey;

    protected ?string $senderName;

    public function __construct()
    {
        $this->apiKey = Setting::getValue('fonnte_api_key');
        $this->senderName = Setting::getValue('fonnte_sender_name', 'ProCell Store');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    public function send(string $target, string $message): bool
    {
        if (! $this->isConfigured() || empty($target)) {
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
            'countryCode' => '62',
        ]);

        return $response->successful();
    }

    public function sendOrderStatus(Order $order, string $oldStatus, string $newStatus): void
    {
        $user = $order->user;
        if (! $user) {
            return;
        }

        $phone = $user->phone;
        if (empty($phone)) {
            $customer = $order->customer;
            $phone = $customer?->phone;
        }

        if (empty($phone)) {
            return;
        }

        $phone = $this->normalizePhone($phone);

        $statusLabels = [
            'pending' => 'Menunggu Pembayaran',
            'waiting_confirmation' => 'Menunggu Konfirmasi',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $newLabel = $statusLabels[$newStatus] ?? $newStatus;
        $storeName = config('app.name');

        $message = "*{$storeName}*\n\n";
        $message .= "Halo {$user->name}! Status pesanan Anda telah diperbarui.\n\n";
        $message .= "📦 *No. Pesanan:* #{$order->order_number}\n";
        $message .= "📋 *Status:* {$newLabel}\n";

        if ($newStatus === 'shipped' && $order->tracking_number) {
            $message .= "📮 *Resi:* {$order->tracking_number} ({$order->courier})\n";
        }

        $url = route('orders.show', $order);
        $message .= "\n🔗 {$url}";
        $message .= "\n\nTerima kasih telah berbelanja di {$storeName}.";

        $this->send($phone, $message);
    }

    public function sendReturnStatus(Returns $return, string $oldStatus, string $newStatus): void
    {
        $user = $return->user;
        if (! $user) {
            return;
        }

        $phone = $user->phone;
        if (empty($phone)) {
            return;
        }

        $phone = $this->normalizePhone($phone);

        $statusLabels = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];

        $newLabel = $statusLabels[$newStatus] ?? $newStatus;
        $storeName = config('app.name');

        $message = "*{$storeName}*\n\n";
        $message .= "Halo {$user->name}! Status retur Anda telah diperbarui.\n\n";
        $message .= "🔄 *No. Retur:* #{$return->return_number}\n";
        $message .= "📋 *Status:* {$newLabel}\n";

        if ($newStatus === 'rejected' && $return->admin_note) {
            $message .= "📝 *Alasan:* {$return->admin_note}\n";
        }

        $url = route('orders.show', $return->order);
        $message .= "\n🔗 {$url}";
        $message .= "\n\nTerima kasih telah berbelanja di {$storeName}.";

        $this->send($phone, $message);
    }

    public function notifyAdmins(string $title, string $body): void
    {
        $adminPhones = \App\Models\User::role(['Super Admin', 'Stok', 'Keuangan'])
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->pluck('phone')
            ->map(fn ($p) => $this->normalizePhone($p))
            ->unique()
            ->values()
            ->toArray();

        if (empty($adminPhones)) {
            return;
        }

        $storeName = config('app.name');
        $message = "*{$storeName} — Notifikasi Admin*\n\n";
        $message .= "*{$title}*\n{$body}";

        foreach ($adminPhones as $phone) {
            $this->send($phone, $message);
        }
    }

    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '62')) {
            // already correct
        } else {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
