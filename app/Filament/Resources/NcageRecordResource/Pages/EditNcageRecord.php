<?php

namespace App\Filament\Resources\NcageRecordResource\Pages;

use App\Filament\Resources\NcageRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNcageRecord extends EditRecord
{
    protected static string $resource = NcageRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
