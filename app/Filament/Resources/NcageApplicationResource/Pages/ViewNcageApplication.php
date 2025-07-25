<?php

namespace App\Filament\Resources\NcageApplicationResource\Pages;

use App\Filament\Resources\NcageApplicationResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions\Action;
use Filament\Actions;
use Filament\Forms\Components\Placeholder;
use Filament\Support\Enums\Alignment;
use Filament\Widgets\StatsOverviewWidget\Card;


class ViewNcageApplication extends ViewRecord
{
    protected static string $resource = NcageApplicationResource::class;

    public function getTitle(): string
    {
        return 'Detail NCAGE - ' . ($this->record->companyDetail->name ?? 'Tidak Diketahui');
    }

}
