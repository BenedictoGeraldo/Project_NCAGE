<?php

namespace App\Http\Controllers;

use App\Models\NcageRecord;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Carbon;

class CertificateController extends Controller
{
    public function downloadFromRecord(NcageRecord $record)
    {
        $templatePath = storage_path('app/templates/sertifikat_template.docx');

        if (!file_exists($templatePath)) {
            abort(500, "File template tidak ditemukan.");
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Semua data sekarang diambil langsung dari $record
        $templateProcessor->setValue('ncage_code', $record->ncage_code ?? '-');
        $templateProcessor->setValue('entity_name', $record->entity_name ?? '-');
        $templateProcessor->setValue('street', $record->street ?? '-');
        $templateProcessor->setValue('city', $record->city ?? '-');
        $templateProcessor->setValue('stt', $record->stt ?? '-');
        $templateProcessor->setValue('psc', $record->psc ?? '-');
        $templateProcessor->setValue('tel', $record->tel ?? '-');
        $templateProcessor->setValue('ema', $record->ema ?? '-');
        $templateProcessor->setValue('www', $record->www ?? '-');

        // Mengolah data tanggal
        $now = Carbon::now();
        $bulanRomawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $templateProcessor->setValue('nomor_bulan_romawi', $bulanRomawi[$now->month - 1]);
        $templateProcessor->setValue('tahun_download', $now->year);
        $templateProcessor->setValue('bulan_download', $now->translatedFormat('F'));

        // ... (Proses simpan dan konversi ke PDF tetap sama) ...
        $tempDocxPath = storage_path('app/temp/' . $record->id . '_sertifikat.docx');
        $templateProcessor->saveAs($tempDocxPath);

        \PhpOffice\PhpWord\Settings::setPdfRendererPath(base_path('vendor/tecnickcom/tcpdf'));
        \PhpOffice\PhpWord\Settings::setPdfRendererName('TCPDF');

        $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempDocxPath);
        $pdfPath = storage_path('app/temp/' . $record->id . '_sertifikat.pdf');

        $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
        $pdfWriter->save($pdfPath);

        unlink($tempDocxPath);

        return response()->download($pdfPath)->deleteFileAfterSend(true);
    }
}
