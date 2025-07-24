<?php

namespace App\Filament\Widgets;

use App\Models\NcageRecord;
use App\Models\NcageApplication; // Tambahkan use statement untuk model permohonan
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NcageStatusStats extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Logika dari NcageStatusStats (sudah ada)
        $activeCount = NcageRecord::where('ncagesd', 'A')->count();
        $invalidCount = NcageRecord::where('ncagesd', 'H')->count();

        // 2. Logika dari PermohonanStats (kita pindahkan ke sini)
        $perluVerifikasiCount = NcageApplication::where('status_id', 2)->count();

        // 3. Gabungkan semuanya dalam satu array
        return [
            Stat::make('NCAGE AKTIF', $activeCount)
                ->description('Total Kode NCAGE Yang Aktif')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('NCAGE Invalid', $invalidCount)
                ->description('Total Kode NCAGE Invalid')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            
            // Tambahkan stat "Perlu Verifikasi" di sini
            Stat::make('Perlu Verifikasi', $perluVerifikasiCount)
                ->description('Jumlah permohonan menunggu verifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
