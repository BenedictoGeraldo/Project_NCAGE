<?php

namespace App\Http\Controllers;

use App\Models\NcageRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function downloadFromRecord(NcageRecord $record)
    {
        // CEK DULU: Apakah path sertifikat sudah ada dan filenya ada di storage?
        if ($record->domestic_certificate_path && Storage::disk('public')->exists($record->domestic_certificate_path)) {

            // JIKA ADA: Langsung unduh file yang sudah ada.
            return Storage::disk('public')->download($record->domestic_certificate_path);
        }

        // JIKA TIDAK ADA: Lanjutkan proses generate, simpan, lalu unduh.
        return $this->generateAndDownloadCertificate($record);
    }

    private function generateAndDownloadCertificate(NcageRecord $record)
    {
        // Path ke template Anda
        $templatePath = storage_path('Indonesia Certificate Template.docx');
        if (!file_exists($templatePath)) {
            abort(500, "File template tidak ditemukan.");
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Isi semua placeholder
        $templateProcessor->setValue('ncage_code', $record->ncage_code ?? '-');
        $templateProcessor->setValue('entity_name', $record->entity_name ?? '-');
        $templateProcessor->setValue('street', $record->street ?? '-');
        $templateProcessor->setValue('city', $record->city ?? '-');
        $templateProcessor->setValue('stt', $record->stt ?? '-');
        $templateProcessor->setValue('psc', $record->psc ?? '-');
        $templateProcessor->setValue('tel', $record->tel ?? '-');
        $templateProcessor->setValue('ema', $record->ema ?? '-');
        $templateProcessor->setValue('www', $record->www ?? '-');

        // Isi data tanggal
        $now = Carbon::now();
        $bulanRomawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $templateProcessor->setValue('nomor_bulan_romawi', $bulanRomawi[$now->month - 1]);
        $templateProcessor->setValue('tahun_download', $now->year);
        $templateProcessor->setValue('bulan_download', $now->translatedFormat('F'));

        // Buat nama dan path file yang akan disimpan secara permanen
        $safeCompanyName = Str::slug($record->entity_name, '_');
        $fileName = 'Sertifikat_NCAGE_Indonesia_' . $safeCompanyName . '_' . $record->ncage_code . '.docx';
        $permanentPath = 'uploads/' . $safeCompanyName . '/sertifikat/' . $fileName;

        // Simpan file ke storage publik
        $tempFilePath = storage_path('app/temp/' . $fileName);
        $templateProcessor->saveAs($tempFilePath);

        // Pindahkan dari temporary ke public storage
        Storage::disk('public')->put($permanentPath, file_get_contents($tempFilePath));

        // Hapus file temporary
        unlink($tempFilePath);

        // Update path di database untuk unduhan berikutnya
        $record->domestic_certificate_path = $permanentPath;
        $record->save();

        // Kembalikan file yang baru dibuat untuk diunduh
        return Storage::disk('public')->download($permanentPath);
    }
}
