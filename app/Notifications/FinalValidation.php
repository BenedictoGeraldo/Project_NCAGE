<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinalValidation extends Notification
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
            'title'   => 'Validasi Akhir & Persiapan Sertifikat',
            'message' => 'Selamat! Berkas Anda telah terverifikasi. Permohonan Anda kini memasuki tahap validasi akhir dan persiapan penerbitan sertifikat oleh tim Puskod.',
            'icon'    => 'fa-solid fa-circle-check',
        ];
    }
}