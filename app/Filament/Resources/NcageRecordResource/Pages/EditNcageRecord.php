<?php

namespace App\Filament\Resources\NcageRecordResource\Pages;

use App\Filament\Resources\NcageRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNcageRecord extends EditRecord
{
    protected static string $resource = NcageRecordResource::class;
    public static function canAccess(array $parameters = []): bool
    {
        return auth()->check() && auth()->user()->can('edit_ncage::record');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
