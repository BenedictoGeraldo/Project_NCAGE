<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class CertificateExpiringSoon extends Notification
{
    use Queueable;

    public string $remainingTime;
    public string $expiryDate;

    /**
     * Create a new notification instance.
     *
     * @param string $remainingTime Contoh: "3 bulan"
     * @param Carbon $expiryDate Objek Carbon untuk tanggal kedaluwarsa
     */
    public function __construct(string $remainingTime, Carbon $expiryDate)
    {
        $this->remainingTime = $remainingTime;
        // Format tanggal ke dalam format yang mudah dibaca (contoh: 7 Mei 2029)
        $this->expiryDate = $expiryDate->locale('id_ID')->isoFormat('D MMMM YYYY');
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
        $message = "Masa berlaku kode NCAGE Anda akan berakhir dalam {$this->remainingTime} (pada tanggal {$this->expiryDate}). Segera ajukan perpanjangan untuk menghindari kendala.";

        return [
            'title'   => 'Masa Berlaku NCAGE Akan Habis',
            'message' => $message,
            'icon'    => 'fa-solid fa-clock-rotate-left',
        ];
    }
}