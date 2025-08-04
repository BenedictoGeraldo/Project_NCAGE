<?php

namespace App\Notifications; // Ini harus App\Notifications;

use App\Models\NcageRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class CertificateExpiringSoon extends Notification implements ShouldQueue // Ini harus CertificateExpiringSoon extends Notification
{
    use Queueable;

    public NcageRecord $record;

    public function __construct(NcageRecord $record)
    {
        $this->record = $record;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $expirationDate = Carbon::parse($this->record->change_date)->addYears(5)->format('d F Y');
        $recordName = $this->record->name ?? 'Sertifikat Anda';

        return (new MailMessage)
                    ->subject('Peringatan: Masa Berlaku Sertifikat Anda Akan Habis!')
                    ->greeting("Halo, {$notifiable->name},")
                    ->line("Kami ingin memberitahukan bahwa {$recordName} akan segera kedaluwarsa pada tanggal **{$expirationDate}**.")
                    ->line('Mohon segera perbarui sertifikat Anda untuk menghindari gangguan layanan.')
                    ->line('Terima kasih!');
    }

    public function toArray(object $notifiable): array
    {
        $expirationDate = Carbon::parse($this->record->change_date)->addYears(5)->format('d F Y');
        $recordName = $this->record->name ?? 'Sertifikat Anda';

        return [
            'type' => 'certificate_expiration',
            'record_id' => $this->record->id,
            'message' => "Masa berlaku {$recordName} akan habis pada {$expirationDate}.",
            'expiration_date' => $expirationDate,
            'title' => 'Peringatan: Sertifikat Akan Kedaluwarsa!',
        ];
    }
}