<?php

namespace App\Http\Controllers;

use App\Models\NcageRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;
use DOMDocument;

class CertificateController extends Controller
{
    /**
     * Method utama untuk mengunduh bundel ZIP oleh admin.
     * Ini adalah satu-satunya method yang perlu dipanggil dari route.
     */
    public function downloadBundle(NcageRecord $record)
    {
        // Pastikan kedua file sertifikat sudah ada, jika belum, buat terlebih dahulu.
        $this->ensureCertificatesExist($record);

        // Buat dan unduh file ZIP.
        $safeCompanyName = Str::slug($record->entity_name, '_');
        $zipFileName = 'Berkas_Sertifikat_' . $safeCompanyName . '_' . $record->ncage_code . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Tambahkan file DOCX dan XML ke dalam ZIP
            $zip->addFile(Storage::disk('public')->path($record->domestic_certificate_path), basename($record->domestic_certificate_path));
            $zip->addFile(Storage::disk('public')->path($record->domestic_certificate_xml_path), basename($record->domestic_certificate_xml_path));
            $zip->close();
        } else {
            abort(500, 'Gagal membuat file ZIP.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Helper utama untuk memeriksa dan membuat file jika belum ada.
     */
    private function ensureCertificatesExist(NcageRecord $record): void
    {
        $docxExists = $record->domestic_certificate_path && Storage::disk('public')->exists($record->domestic_certificate_path);
        $xmlExists = $record->domestic_certificate_xml_path && Storage::disk('public')->exists($record->domestic_certificate_xml_path);

        if (!$docxExists) {
            $this->generateDocx($record);
        }

        if (!$xmlExists) {
            $this->generateXml($record);
        }

        // Muat ulang data record untuk mendapatkan path terbaru jika baru dibuat
        $record->refresh();
    }

    /**
     * Logika untuk membuat file DOCX saja.
     */
    private function generateDocx(NcageRecord $record): void
    {
        $templatePath = storage_path('Indonesia Certificate Template.docx');
        if (!file_exists($templatePath)) {
            abort(500, "File template DOCX tidak ditemukan.");
        }

        $templateProcessor = new TemplateProcessor($templatePath);
        $this->fillTemplatePlaceholders($templateProcessor, $record);

        $safeCompanyName = Str::slug($record->entity_name, '_');
        $fileName = 'Sertifikat_NCAGE_' . $safeCompanyName . '_' . $record->ncage_code . '.docx';
        $permanentPath = 'uploads/' . $safeCompanyName . '/sertifikat/' . $fileName;

        $tempFilePath = storage_path('app/temp/' . $fileName);
        $templateProcessor->saveAs($tempFilePath);
        Storage::disk('public')->put($permanentPath, file_get_contents($tempFilePath));
        unlink($tempFilePath);

        $record->domestic_certificate_path = $permanentPath;
        $record->save();
    }

    /**
     * Logika untuk membuat file XML saja.
     */
    private function generateXml(NcageRecord $record): void
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $root = $dom->createElement('sertifikat');
        $dom->appendChild($root);

        foreach ($record->toArray() as $key => $value) {
            $child = $dom->createElement($key, htmlspecialchars($value ?? ''));
            $root->appendChild($child);
        }
        $xmlContent = $dom->saveXML();

        $safeCompanyName = Str::slug($record->entity_name, '_');
        $fileName = 'Sertifikat_NCAGE_' . $safeCompanyName . '_' . $record->ncage_code . '.xml';
        $permanentPath = 'uploads/' . $safeCompanyName . '/sertifikat/' . $fileName;

        Storage::disk('public')->put($permanentPath, $xmlContent);

        $record->domestic_certificate_xml_path = $permanentPath;
        $record->save();
    }

    /**
     * Helper untuk mengisi placeholder di template DOCX.
     */
    private function fillTemplatePlaceholders(TemplateProcessor $templateProcessor, NcageRecord $record): void
    {
        $templateProcessor->setValue('ncage_code', $record->ncage_code ?? '-');
        $templateProcessor->setValue('entity_name', $record->entity_name ?? '-');
        $templateProcessor->setValue('street', $record->street ?? '-');
        $templateProcessor->setValue('city', $record->city ?? '-');
        $templateProcessor->setValue('stt', $record->stt ?? '-');
        $templateProcessor->setValue('psc', $record->psc ?? '-');
        $templateProcessor->setValue('tel', $record->tel ?? '-');
        $templateProcessor->setValue('ema', $record->ema ?? '-');
        $templateProcessor->setValue('www', $record->www ?? '-');

        $now = Carbon::now();
        $bulanRomawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $templateProcessor->setValue('nomor_bulan_romawi', $bulanRomawi[$now->month - 1]);
        $templateProcessor->setValue('tahun_download', $now->year);
        $templateProcessor->setValue('bulan_download', $now->translatedFormat('F'));
    }
}
