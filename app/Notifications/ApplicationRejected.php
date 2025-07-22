<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationRejected extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => 'Permohonan Ditolak',
            'message' => 'Dengan berat hati, permohonan NCAGE Anda tidak dapat kami setujui. Silakan lihat detail pada halaman "Pantau Status" untuk alasannya.',
            'icon'    => 'fa-solid fa-circle-xmark',
        ];
    }
}