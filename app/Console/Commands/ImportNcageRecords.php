<?php

namespace App\Console\Commands;

use App\Models\NcageRecord;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportNcageRecords extends Command
{
    /**
     * Nama dan signature dari command.
     */
    protected $signature = 'import:ncage-records';

    /**
     * Deskripsi dari command.
     */
    protected $description = 'Impor data NCAGE dari file CSV ke database';

    /**
     * Jalankan command.
     */
    public function handle()
    {
        $path = database_path('data/export-20250703-104640.csv');

        if (!File::exists($path)) {
            $this->error('File CSV tidak ditemukan di: ' . $path);
            return 1;
        }

        $this->info('Memulai proses impor data NCAGE...');

        $file = fopen($path, 'r');

        $header = fgetcsv($file);

        NcageRecord::truncate();

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            NcageRecord::create([
                'ncage_code' => $row[0],
                'ncagesd' => $row[1],
                'toec' => $row[2],
                'entity_name' => $row[3],
                'street' => $row[4],
                'city' => $row[5],
                'psc' => $row[6],
                'country' => $row[7],
                'ctr' => $row[8],
                'stt' => $row[9],
                'ste' => $row[10],
                'is_sam_requested' => $row[11] == '1',
                'remarks' => $row[12] ?: null,
                'last_change_date_international' => $row[13] ?: null,
                'change_date' => $row[14] ?: null,
                'creation_date' => $row[15] ?: null,
                'load_date' => $row[16] ?: null,
                'national' => $row[17] ?: null,
                'nac' => $row[18] ?: null,
                'idn' => $row[19] ?: null,
                'bar' => $row[20] ?: null,
                'nai' => $row[21] ?: null,
                'cpv' => $row[22] ?: null,
                'uns' => $row[23] ?: null,
                'sic' => $row[24] ?: null,
                'tel' => $row[25] ?: null,
                'fax' => $row[26] ?: null,
                'ema' => $row[27] ?: null,
                'www' => $row[28] ?: null,
                'pob' => $row[29] ?: null,
                'pcc' => $row[30] ?: null,
                'pcs' => $row[31] ?: null,
                'rp1_5' => $row[32] ?: null,
                'nmcrl_ref_count' => $row[33] ? (int)$row[33] : null,
            ]);
            $count++;
        }

        fclose($file);

        $this->info("Selesai! Berhasil mengimpor {$count} data NCAGE.");

        return 0;
    }
}
