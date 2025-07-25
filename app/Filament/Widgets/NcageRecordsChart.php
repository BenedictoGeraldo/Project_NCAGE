<?php

namespace App\Filament\Widgets;

use App\Models\NcageRecord;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class NcageRecordsChart extends ChartWidget
{
    // 1. Kosongkan heading default agar tidak ada judul di kiri
    protected static ?string $heading = null;

    // Atur tinggi maksimum chart agar tidak terlalu besar
    protected static ?string $maxHeight = '250px';

    // Atur agar chart ini memakan lebar penuh
    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        // 1. Ambil data dari 15 tahun terakhir, kelompokkan per TAHUN
        $data = NcageRecord::query()
            ->where('creation_date', '>=', now()->subYears(15))
            ->groupBy('year')
            ->orderBy('year', 'ASC')
            ->get([
                DB::raw('YEAR(creation_date) as year'), // Ambil TAHUN dari tanggal
                DB::raw('count(*) as count')
            ])
            ->pluck('count', 'year'); // Hasilnya: [2010 => 150, 2011 => 200]

        // 2. Buat rentang 15 tahun untuk label di sumbu X
        $period = CarbonPeriod::create(now()->subYears(14), '1 year', now());
        
        // 3. Siapkan array data lengkap, isi dengan 0 untuk tahun yang tidak ada data
        $labels = [];
        $dataset = [];
        foreach ($period as $date) {
            $year = $date->format('Y'); // Ambil tahun, contoh: '2025'
            $labels[] = $year;
            $dataset[] = $data[$year] ?? 0; // Jika ada data, gunakan, jika tidak, isi dengan 0
        }

        return [
            'datasets' => [
                [
                    'label' => 'Record Baru per Tahun',
                    'data' => $dataset,
                    'borderColor' => '#4ade80', // Ganti warna jadi hijau
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    /**
     * Menambahkan opsi kustom ke Chart.js
     */
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'Pertumbuhan Data NCAGE (15 Tahun Terakhir)',
                    'align' => 'center', // Mengatur judul ke tengah
                    'font' => [
                        'size' => 16, // Atur ukuran font jika perlu
                        'weight' => 'bold', //atur tebal font
                    ],
                ],
                'legend' => [
                    'display' => true, // Pastikan legenda tetap tampil
                ],
            ],
        ];
    }
}
