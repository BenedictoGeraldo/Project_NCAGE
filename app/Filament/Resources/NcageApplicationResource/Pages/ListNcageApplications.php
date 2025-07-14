<?php

namespace App\Filament\Resources\NcageApplicationResource\Pages;

use App\Filament\Resources\NcageApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNcageApplications extends ListRecords
{
    protected static string $resource = NcageApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
