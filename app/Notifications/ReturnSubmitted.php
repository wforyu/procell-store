<?php

namespace App\Notifications;

use App\Models\Returns;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReturnSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public Returns $return;

    public function __construct(Returns $return)
    {
        $this->return = $return;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $reasonLabels = [
            'defective' => 'Produk Cacat',
            'wrong_item' => 'Tidak Sesuai',
            'not_as_described' => 'Tidak Sesuai Deskripsi',
            'damaged' => 'Rusak Saat Kirim',
            'other' => 'Lainnya',
        ];

        return (new MailMessage)
            ->subject('Retur Baru — #'.$this->return->return_number)
            ->greeting('Halo '.$notifiable->name.'!')
            ->line('Customer '.$this->return->user->name.' mengajukan retur untuk pesanan #'.$this->return->order->order_number.'.')
            ->line('Alasan: '.($reasonLabels[$this->return->reason] ?? $this->return->reason))
            ->line('Deskripsi: '.$this->return->description)
            ->action('Lihat Detail Retur', url('/admin/returns/'.$this->return->id.'/edit'))
            ->line('Silakan proses pengajuan retur di panel admin.');
    }

    public function toArray($notifiable): array
    {
        return [
            'return_id' => $this->return->id,
            'return_number' => $this->return->return_number,
            'order_id' => $this->return->order_id,
        ];
    }
}
