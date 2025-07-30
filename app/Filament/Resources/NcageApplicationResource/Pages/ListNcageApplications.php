<?php

namespace App\Filament\Resources\NcageApplicationResource\Pages;

use App\Filament\Resources\NcageApplicationResource;
use App\Models\NcageApplication;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListNcageApplications extends ListRecords
{
    protected static string $resource = NcageApplicationResource::class;
    // Properti untuk menyimpan status apakah ada data yang bisa diekspor
    public bool $hasExportableData = false;

    /**
     * Mendefinisikan aksi di header halaman.
     */
    protected function getHeaderActions(): array
    {
        return [
            // TOMBOL UNTUK EKSPOR XML BATCH
            Actions\Action::make('exportBatchXml')
                ->label('Export XML (Batch)')
                ->icon('heroicon-o-document-arrow-down')
                ->color('primary')
                // Arahkan ke route yang baru kita buat
                ->url(route('admin.export.batch.xml'))
                // Buka di tab baru agar tidak meninggalkan halaman admin
                ->openUrlInNewTab()
                // Tombol akan non-aktif jika properti $hasExportableData adalah false
                ->disabled(!$this->hasExportableData)
                // Tambahkan konfirmasi untuk UX yang lebih baik
                ->requiresConfirmation()
                ->modalDescription('Anda akan mengunduh file XML yang berisi semua data aplikasi yang telah divalidasi (Status 4). Lanjutkan?'),
        ];
    }

    public function mount(): void
    {
        parent::mount(); // penting untuk memanggil parent!

        // Cek apakah ada data yang siap di-ekspor (status_id = 4)
        $this->updateExportableStatus();

        if (session()->has('success')) {
            Notification::make()
                ->title(session('success'))
                ->success()
                ->send();
        }

        if (session()->has('warning')) {
            Notification::make()
                ->title(session('warning'))
                ->warning()
                ->send();
        }
    }

    /**
     * Helper untuk memeriksa dan mengupdate status tombol ekspor.
     */
    protected function updateExportableStatus(): void
    {
        $this->hasExportableData = NcageApplication::where('status_id', 4)->exists();
    }
}
