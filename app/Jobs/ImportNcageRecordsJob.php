<?php

namespace App\Jobs;

use App\Models\NcageRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ImportNcageRecordsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Helper function untuk mengonversi format tanggal dengan aman.
     */
    private function reformatDate(string $dateString, string $fromFormat = 'd/m/Y', string $toFormat = 'Y-m-d'): ?string
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            return Carbon::createFromFormat($fromFormat, $dateString)->format($toFormat);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function handle(): void
    {
        try {
            if (!Storage::disk('local')->exists($this->filePath)) {
                Log::error('File impor tidak ditemukan di: ' . $this->filePath);
                return;
            }

            $absolutePath = Storage::disk('local')->path($this->filePath);
            Log::info('Memulai proses impor data NCAGE dari file: ' . $this->filePath);

            $file = fopen($absolutePath, 'r');
            fgetcsv($file); // Lewati header
            NcageRecord::truncate();

            $count = 0;
            while (($row = fgetcsv($file)) !== false) {
                NcageRecord::create([
                    'ncage_code' => $row[0] ?? null,
                    'ncagesd' => $row[1] ?? null,
                    'toec' => $row[2] ?? null,
                    'entity_name' => $row[3] ?? null,
                    'street' => $row[4] ?? null,
                    'city' => $row[5] ?? null,
                    'psc' => $row[6] ?? null,
                    'country' => $row[7] ?? null,
                    'ctr' => $row[8] ?? null,
                    'stt' => $row[9] ?? null,
                    'ste' => $row[10] ?? null,
                    'is_sam_requested' => isset($row[11]) && in_array(strtoupper($row[11]), ['1', 'TRUE', 'Y']),
                    'remarks' => $row[12] ?? null,
                    'last_change_date_international' => $this->reformatDate($row[13] ?? ''),
                    'change_date' => $this->reformatDate($row[14] ?? ''),
                    'creation_date' => $this->reformatDate($row[15] ?? ''),
                    'load_date' => $this->reformatDate($row[16] ?? ''),
                    'national' => $row[17] ?? null,
                    'nac' => $row[18] ?? null,
                    'idn' => $row[19] ?? null,
                    'bar' => $row[20] ?? null,
                    'nai' => $row[21] ?? null,
                    'cpv' => $row[22] ?? null,
                    'uns' => $row[23] ?? null,
                    'sic' => $row[24] ?? null,
                    'tel' => $row[25] ?? null,
                    'fax' => $row[26] ?? null,
                    'ema' => $row[27] ?? null,
                    'www' => $row[28] ?? null,
                    'pob' => $row[29] ?? null,
                    'pcc' => $row[30] ?? null,
                    'pcs' => $row[31] ?? null,
                    'rp1_5' => $row[32] ?? null,
                    'nmcrl_ref_count' => isset($row[33]) && is_numeric($row[33]) ? (int)$row[33] : null,
                ]);
                $count++;
            }

            fclose($file);
            Log::info("Selesai! Berhasil mengimpor {$count} data NCAGE.");

        } catch (\Exception $e) {
            Log::error("Proses impor gagal: " . $e->getMessage());
        } finally {
            if (isset($this->filePath) && Storage::disk('local')->exists($this->filePath)) {
                Storage::disk('local')->delete($this->filePath);
            }
        }
    }
}
