<?php

namespace App\Filament\Widgets;

use App\Models\NcageRecord;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NcageStatusStats extends BaseWidget
{
    protected function getStats(): array
    {
        //menghitung jumlah NCAGE yang aktif (kode 'A')
        $activeCount = NcageRecord::where('ncagesd', 'A')->count();

        //menghitung jumlah NCAGE yang invalid
        $invalidCount = NcageRecord::where('ncagesd', 'H')->count();

        //menghitung jumlah status ncage lainnya
        $otherCount = NcageRecord::whereNotIn('ncagesd', ['A', 'H'])->count();

        //membuat tampilan data dalam bentuk teks
        return[
            Stat::make('NCAGE AKTIF', $activeCount)
                ->description('Total Kode NCAGE Yang Aktif')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('NCAGE Invalid', $invalidCount)
                ->description('Total Kode NCAGE Invalid')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Status NCAGE Lainnya', $otherCount)
                ->description('Total kode NCAGE status lain')
                ->descriptionIcon('heroicon-m-tag')
                ->color('gray'),
        ];
    }
}
