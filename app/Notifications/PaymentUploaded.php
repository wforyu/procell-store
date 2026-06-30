<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentUploaded extends Notification implements ShouldQueue
{
    use Queueable;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bukti Pembayaran — Pesanan #'.$this->order->order_number)
            ->greeting('Halo '.$notifiable->name.'!')
            ->line('Customer '.$this->order->user->name.' telah mengupload bukti pembayaran untuk pesanan #'.$this->order->order_number.'.')
            ->line('Total Pesanan: Rp '.number_format($this->order->grand_total, 0, ',', '.'))
            ->action('Lihat Detail Pesanan', url('/admin/orders/'.$this->order->id.'/edit'))
            ->line('Silakan konfirmasi pembayaran di panel admin.');
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
        ];
    }
}
