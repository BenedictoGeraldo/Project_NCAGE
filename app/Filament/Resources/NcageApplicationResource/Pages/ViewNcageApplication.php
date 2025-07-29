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

    protected function getActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->url(route('filament.admin.resources.ncage-applications.index')) // sesuaikan route index kamu
                ->icon('heroicon-o-arrow-left'),
        ];
    }

}
