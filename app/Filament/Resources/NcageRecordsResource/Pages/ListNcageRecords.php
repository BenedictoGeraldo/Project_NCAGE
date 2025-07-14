<?php

namespace App\Filament\Resources\NcageRecordsResource\Pages;

use App\Filament\Resources\NcageRecordsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNcageRecords extends ListRecords
{
    protected static string $resource = NcageRecordsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
