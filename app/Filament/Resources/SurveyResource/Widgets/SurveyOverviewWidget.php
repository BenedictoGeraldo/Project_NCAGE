<?php

namespace App\Filament\Resources\SurveyResource\Widgets;

use App\Models\Survey;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SurveyOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Hitung rata-rata per baris (per survei)
        $rawQuery = '(q1_kesesuaian_persyaratan + q2_kemudahan_prosedur + q3_kecepatan_pelayanan +
        q4_kewajaran_biaya + q5_kesesuaian_produk + q6_kompetensi_petugas +
        q7_perilaku_petugas + q8_kualitas_sarana + q9_penanganan_pengaduan) / 9';

        // Hitung rata-rata dari semua rata-rata per baris tersebut
        $overallAverage = Survey::query()->avg(DB::raw($rawQuery));

        // Buat tampilan Stat Card
        return [
            Stat::make('Nilai Kepuasan Keseluruhan', number_format($overallAverage, 2) . ' / 4.00')
                ->description('Rata-rata dari total nilai survei')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // (Opsional) Data chart dummy
                ->icon('heroicon-o-star'),

            Stat::make('Total Survei Masuk', Survey::query()->count())
                ->description('Jumlah total kuesioner yang telah diisi')
                ->color('info')
                ->icon('heroicon-o-document-chart-bar'),
        ];
    }
}
