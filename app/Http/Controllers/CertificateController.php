<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\NcageRecord;
use App\Models\NcageApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;
use DOMDocument;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CertificateController extends Controller
{
    /**
     * Method utama untuk mengunduh bundel ZIP yang berisi sertifikat DOCX dan XML.
     */
    public function downloadBundle(NcageRecord $record): BinaryFileResponse
    {
        // Pastikan direktori temporary ada, jika tidak, buat.
        $this->ensureTempDirectoryExists();

        // Pastikan kedua file sertifikat sudah ada, jika belum, buat terlebih dahulu.
        $this->ensureCertificatesExist($record);

        // Buat dan unduh file ZIP.
        $zipFileName = 'Berkas_Sertifikat_' . $record->entity_name . '_' . $record->ncage_code . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            abort(500, 'Gagal membuat file ZIP.');
        }

        // Dapatkan path absolut menggunakan helper storage_path()
        $docxPath = storage_path('app/public/' . $record->domestic_certificate_path);
        $xmlPath = storage_path('app/public/' . $record->domestic_certificate_xml_path);

        // Tambahkan file DOCX dan XML ke dalam ZIP
        $zip->addFile($docxPath, basename($record->domestic_certificate_path));
        $zip->addFile($xmlPath, basename($record->domestic_certificate_xml_path));

        $zip->close();

        // Kirim file ZIP untuk diunduh dan hapus file tersebut setelah terkirim.
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Memeriksa dan memastikan kedua sertifikat (DOCX dan XML) sudah ada.
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

        // Muat ulang data record untuk mendapatkan path terbaru jika baru dibuat.
        $record->refresh();
    }

    /**
     * Membuat file sertifikat DOCX.
     */
    private function generateDocx(NcageRecord $record): void
    {
        $templatePath = storage_path('Indonesia Certificate Template.docx');
        if (!file_exists($templatePath)) {
            abort(500, "File template DOCX tidak ditemukan.");
        }

        $fileName = 'Sertifikat_NCAGE_' . $record->entity_name . '_' . $record->ncage_code . '.docx';
        $permanentPath = 'uploads/' . $record->entity_name . '/sertifikat/' . $fileName;
        $tempFilePath = storage_path('app/temp/' . $fileName);

        try {
            $templateProcessor = new TemplateProcessor($templatePath);
            $this->fillTemplatePlaceholders($templateProcessor, $record);
            $templateProcessor->saveAs($tempFilePath);

            Storage::disk('public')->put($permanentPath, file_get_contents($tempFilePath));

            $record->domestic_certificate_path = $permanentPath;
            $record->save();
        } finally {
            // Pastikan file sementara selalu dihapus, bahkan jika terjadi error.
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
        }
    }

    /**
     * Membuat file sertifikat XML.
     */
    private function generateXml(NcageRecord $record): void
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $root = $dom->createElement('sertifikat');
        $dom->appendChild($root);

        foreach ($record->toArray() as $key => $value) {
            $child = $dom->createElement($key, htmlspecialchars((string) ($value ?? '')));
            $root->appendChild($child);
        }
        $xmlContent = $dom->saveXML();

        $fileName = 'Sertifikat_NCAGE_' . $record->entity_name . '_' . $record->ncage_code . '.xml';
        $permanentPath = 'uploads/' . $record->entity_name . '/sertifikat/' . $fileName;

        Storage::disk('public')->put($permanentPath, $xmlContent);

        $record->domestic_certificate_xml_path = $permanentPath;
        $record->save();
    }

    /**
     * Mengisi semua placeholder pada template DOCX dengan data dari record.
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

    /**
     * Helper untuk memastikan direktori temporary ada.
     */
    private function ensureTempDirectoryExists(): void
    {
        $tempDirectory = storage_path('app/temp');
        if (!is_dir($tempDirectory)) {
            mkdir($tempDirectory, 0755, true);
        }
    }

    /**
     * Mengunduh file sertifikat DOCX dari sebuah record.
     * Akan men-generate file jika belum ada.
     */
    public function downloadFromRecord(NcageRecord $record)
    {
        // Cek apakah path sertifikat sudah ada di database dan filenya benar-benar ada di storage.
        if (!$record->domestic_certificate_path || !Storage::disk('public')->exists($record->domestic_certificate_path)) {
            // Jika tidak ada, panggil fungsi untuk membuat file DOCX.
            $this->generateDocx($record);
            // Muat ulang data record untuk mendapatkan path file yang baru saja dibuat.
            $record->refresh();
        }

        // Ambil path lengkap ke file sertifikat.
        $filePath = $record->domestic_certificate_path;
        // Dapatkan nama file yang aman untuk diunduh.
        $fileName = basename($filePath);

        // Kembalikan response untuk mengunduh file.
        return Storage::disk('public')->download($filePath, $fileName);
    }

    /**
     * Menangani unduhan sertifikat internasional yang diunggah oleh admin.
     *
     * @param \App\Models\NcageApplication $application
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadInternationalCertificate(NcageApplication $application)
    {
        // Ambil data dokumen dan decode dari JSON
        $documents = json_decode($application->documents, true);

        // Cek apakah path sertifikat internasional ada dan filenya tersedia
        $filePath = $documents['sertifikat_nspa'] ?? null;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            // Jika file tidak ditemukan, hentikan proses.
            abort(404, 'File sertifikat tidak ditemukan.');
        }

        // Kembalikan response untuk mengunduh file.
        return Storage::disk('public')->download($filePath);
    }
}
