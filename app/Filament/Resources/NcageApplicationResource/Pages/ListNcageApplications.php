<?php

namespace App\Filament\Resources\NcageApplicationResource\Pages;

use App\Filament\Resources\NcageApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListNcageApplications extends ListRecords
{
    protected static string $resource = NcageApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }

    public function mount(): void
    {
        parent::mount(); // penting untuk memanggil parent!

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
}
