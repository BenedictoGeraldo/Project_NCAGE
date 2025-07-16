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
use Maatwebsite\Excel\Facades\Excel;

class ImportNcageRecordsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
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

            // Ini otomatis mendeteksi apakah file itu XLSX atau CSV.
            $collection = Excel::toCollection(null, $absolutePath)[0]; // Ambil data dari sheet pertama

            $header = $collection->first()->toArray(); // Ambil baris pertama sebagai header
            $rows = $collection->slice(1); // Ambil sisa baris sebagai data

            $count = 0;
            foreach ($rows as $row) {
                try {
                    // Gabungkan header dengan data baris untuk membuat array asosiatif
                    // Contoh: ['NCAGE' => 'SK2M9', 'Entity Name' => 'ACME Corp']
                    $data = array_combine($header, $row->toArray());

                    // LOGIKA MAPPING & KONVERSI YANG SUDAH KITA BUAT
                    $databaseData = [
                        'ncage_code'        => $data['NCAGE'] ?? null,
                        'ncagesd'           => $data['NCAGESD'] ?? null,
                        'toec'              => $data['TOEC'] ?? null,
                        'entity_name'       => $data['Entity Name'] ?? null,
                        'street'            => $data['Street (ST1/2)'] ?? null,
                        'city'              => $data['City (CIT)'] ?? null,
                        'psc'               => $data['Post Code, Physical Address (PSC)'] ?? null,
                        'country'           => $data['Country'] ?? null,
                        'ctr'               => $data['ISO (CTR)'] ?? null,
                        'stt'               => $data['State/Province (STT)'] ?? null,
                        'ste'               => $data['FIPS State (STE)'] ?? null,
                        'is_sam_requested'  => $data['Cage code requested for SAM'] ?? null,
                        'remarks'           => $data['Remarks'] ?? null,
                        'national'          => $data['National'] ?? null,
                        'nac'               => $data['NAC'] ?? null,
                        'idn'               => $data['IDN'] ?? null,
                        'bar'               => $data['BAR'] ?? null,
                        'nai'               => $data['NAI'] ?? null,
                        'cpv'               => $data['CPV'] ?? null,
                        'uns'               => $data['UNS'] ?? null,
                        'sic'               => $data['SIC'] ?? null,
                        'tel'               => $data['Voice telephone number (TEL)'] ?? null,
                        'fax'               => $data['Telefax number (FAX)'] ?? null,
                        'ema'               => $data['Email (EMA)'] ?? null,
                        'www'               => $data['WWW (WWW)'] ?? null,
                        'pob'               => $data['Post Office Box Number (POB)'] ?? null,
                        'pcc'               => $data['City, Postal Address (PCC)'] ?? null,
                        'pcs'               => $data['Post Code, Postal Address (PCS)'] ?? null,
                        'rp1_5'             => $data['Replaced By (RP1-5)'] ?? null,
                        'nmcrl_ref_count'   => $data['NMCRL Reference count'] ?? null,
                    ];

                    // Konversi Tanggal
                    $dateFields = [
                        'last_change_date_international' => 'Date Last Change International',
                        'change_date' => 'Change Date',
                        'creation_date' => 'Creation Date',
                        'load_date' => 'Load Date',
                    ];

                    foreach ($dateFields as $dbKey => $excelKey) {
                        $value = $data[$excelKey] ?? null;
                        if (empty($value)) {
                            $databaseData[$dbKey] = null; continue;
                        }
                        if (is_numeric($value)) {
                            $databaseData[$dbKey] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
                        } else {
                            $databaseData[$dbKey] = \Carbon\Carbon::parse($value)->format('Y-m-d');
                        }
                    }

                    // Konversi Tipe Data Lain
                    $databaseData['is_sam_requested'] = isset($databaseData['is_sam_requested']) && in_array(strtoupper($databaseData['is_sam_requested']), ['1', 'TRUE', 'Y', 'YES']);
                    $databaseData['nmcrl_ref_count'] = isset($databaseData['nmcrl_ref_count']) && is_numeric($databaseData['nmcrl_ref_count']) ? (int)$databaseData['nmcrl_ref_count'] : null;

                    NcageRecord::updateOrCreate(
                        ['ncage_code' => $databaseData['ncage_code']], // Kunci untuk mencari data
                        $databaseData  // Data yang akan di-update atau di-create
                    );
                    $count++;

                } catch (\Exception $e) {
                    Log::error("Gagal memproses baris data. Error: " . $e->getMessage(), ['row_data' => $row->toArray()]);
                    continue;
                }
            }

            Log::info("Selesai! Berhasil mengimpor {$count} dari " . count($rows) . " total baris data.");

        } catch (\Exception $e) {
            Log::error("Proses impor gagal total: " . $e->getMessage());
        } finally {
            if (isset($this->filePath) && Storage::disk('local')->exists($this->filePath)) {
                Storage::disk('local')->delete($this->filePath);
            }
        }
    }
}
