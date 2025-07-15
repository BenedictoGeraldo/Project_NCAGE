<?php

namespace App\Filament\Resources\NcageRecordResource\Pages;

use App\Filament\Resources\NcageRecordResource;
use App\Jobs\ImportNcageRecordsJob;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ListNcageRecords extends ListRecords
{
    protected static string $resource = NcageRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
                ->label('Impor Data NCAGE')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    Forms\Components\FileUpload::make('attachment')
                        ->label('File XLSX/CSV')
                        ->required()
                        ->disk('local') // Arahkan ke disk local
                        ->directory('livewire-tmp'), // Direktori temporary
                ])
                ->action(function (array $data) {

                    // 1. Ambil HANYA nama filenya saja dari path yang diberikan Filament
                    $baseFileName = basename($data['attachment']);

                    // 2. Tentukan path asal file di direktori temporary Livewire
                    $sourcePath = 'livewire-tmp/' . $baseFileName;

                    // 3. Buat nama file baru yang unik menggunakan nama file asli
                    $newFileName = 'imports/ncage-' . uniqid() . '-' . $baseFileName;

                    // 4. Pindahkan file dari direktori temporary Livewire ke direktori tujuan
                    Storage::disk('local')->move($sourcePath, $newFileName);

                    ImportNcageRecordsJob::dispatch($newFileName);

                    Notification::make()
                        ->title('Impor Dimulai')
                        ->body('Proses impor data sedang berjalan di background.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
