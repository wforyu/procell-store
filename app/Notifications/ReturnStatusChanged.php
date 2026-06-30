<?php

namespace App\Notifications;

use App\Models\Returns;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReturnStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public Returns $return;

    public string $oldStatus;

    public string $newStatus;

    public function __construct(Returns $return, string $oldStatus, string $newStatus)
    {
        $this->return = $return;
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
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];

        $newLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;

        $message = (new MailMessage)
            ->subject('Status Retur #'.$this->return->return_number.' — '.$newLabel)
            ->greeting('Halo '.$notifiable->name.'!')
            ->line('Status pengajuan retur Anda telah diperbarui.')
            ->line('Nomor Retur: #'.$this->return->return_number)
            ->line('Status: **'.$newLabel.'**')
            ->action('Lihat Detail Pesanan', route('orders.show', $this->return->order));

        if ($this->newStatus === 'rejected' && $this->return->admin_note) {
            $message->line('Alasan: '.$this->return->admin_note);
        }

        return $message->line('Terima kasih telah berbelanja di '.config('app.name').'.');
    }

    public function toArray($notifiable): array
    {
        return [
            'return_id' => $this->return->id,
            'return_number' => $this->return->return_number,
            'order_id' => $this->return->order_id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }
}
