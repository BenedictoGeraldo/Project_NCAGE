<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmitted extends Notification
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
     * Get the mail representation of the notification for database.
     * 
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return[
            'title' => 'Permohonan Terkirim',
            'message' => 'Permohonan NCAGE Anda sukses terkirim. Tim kami akan segera melakukan verifikasi.',
            'icon' => 'fa-solid fa-paper-plane',
        ];
    }
}
