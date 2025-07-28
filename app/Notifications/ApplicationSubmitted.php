<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\NcageApplication;

class ApplicationSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(NcageApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Kirim ke database untuk riwayat DAN broadcast untuk real-time
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification for the database.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Permohonan Terkirim',
            'message' => 'Permohonan NCAGE Anda sukses terkirim. Tim kami akan segera melakukan verifikasi.',
            'icon' => 'fa-solid fa-paper-plane',
            'application_id' => $this->application->id,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        // Data ini yang akan diterima oleh JavaScript secara real-time
        return new BroadcastMessage([
            'title' => 'Permohonan Terkirim',
            'message' => 'Permohonan NCAGE Anda sukses terkirim. Tim kami akan segera melakukan verifikasi.',
            'icon' => 'fa-solid fa-paper-plane',
        ]);
    }
}