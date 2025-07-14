<?php

namespace App\Http\Controllers;

use App\Models\NcageRecord;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class CertificateController extends Controller
{
    public function downloadFromRecord(NcageRecord $record)
    {
        $templatePath = storage_path('app/templates/Indonesia Certificate Template.docx');

        if (!file_exists($templatePath)) {
            abort(500, "File template tidak ditemukan.");
        }

        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('entity_name', $record->entity_name ?? '-');
        $templateProcessor->setValue('ncage_code', $record->ncage_code ?? '-');
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

        $newFileName = 'Sertifikat NCAGE - ' . ($record->entity_name ?? 'Record ' . $record->id) . '.docx';
        $tempFilePath = storage_path('app/temp/' . $newFileName);
        $templateProcessor->saveAs($tempFilePath);

        return response()->download($tempFilePath)->deleteFileAfterSend(true);
    }
}
