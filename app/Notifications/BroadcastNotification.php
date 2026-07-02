<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BroadcastNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $subject;

    public string $message;

    public function __construct(string $subject, string $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->greeting('Halo '.($notifiable->name ?? $notifiable->customer?->name ?? 'Pelanggan').'!')
            ->line($this->message)
            ->line('Terima kasih telah berbelanja di '.config('app.name').'.');
    }

    public function toArray($notifiable): array
    {
        return [
            'subject' => $this->subject,
            'message' => $this->message,
        ];
    }
}
