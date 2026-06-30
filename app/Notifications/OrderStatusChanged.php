<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public Order $order;
    public string $oldStatus;
    public string $newStatus;

    public function __construct(Order $order, string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $statusLabels = [
            'pending' => 'Menunggu Pembayaran',
            'waiting_confirmation' => 'Menunggu Konfirmasi',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $newLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;

        $message = (new MailMessage)
            ->subject('Status Pesanan #'.$this->order->order_number.' — '.$newLabel)
            ->greeting('Halo '.$notifiable->name.'!')
            ->line('Status pesanan Anda telah diperbarui.')
            ->line('Nomor Pesanan: #'.$this->order->order_number)
            ->line('Status: **'.$newLabel.'**')
            ->action('Lihat Detail Pesanan', route('orders.show', $this->order))
            ->line('Terima kasih telah berbelanja di '.config('app.name').'.');

        if ($this->newStatus === 'shipped' && $this->order->tracking_number) {
            $message->line('Nomor Resi: '.$this->order->tracking_number.' ('.$this->order->courier.')');
        }

        return $message;
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }
}
