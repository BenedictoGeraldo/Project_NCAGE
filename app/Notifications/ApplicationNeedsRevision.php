<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationNeedsRevision extends Notification
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
            'title'   => 'Permohonan Anda Perlu Diperbaiki',
            'message' => 'Terdapat beberapa data pada permohonan Anda yang perlu diperbaiki. Silakan periksa catatan dari tim kami pada halaman "Pantau Status".',
            'icon'    => 'fa-solid fa-pen-to-square',
        ];
    }
}