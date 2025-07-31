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
     * Mengunduh file sertifikat XML untuk impor internasional.
     * Method ini mengambil semua aplikasi yang telah divalidasi.
     */
    public function downloadInternationalXml()
    {
        // Ambil semua pendaftaran yang statusnya sudah 'validated' (status_id = 4)
        // Sesuaikan query ini dengan logika status di aplikasi Anda.
        $applications = NcageApplication::with(['identity', 'companyDetail'])
            ->where('status_id', 4)
            ->whereNotNull('ncage_code')
            ->get();

        if ($applications->isEmpty()) {
            abort(404, 'Tidak ada data aplikasi tervalidasi yang siap untuk diekspor.');
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->standalone = true;
        $dom->formatOutput = true;

        // --- ROOT ELEMENT & NAMESPACES ---
        $root = $dom->createElementNS('http://eportal.nspa.nato.int/Message', 'ncs:MESSAGE');
        $dom->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttributeNS(
            'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation',
            'http://eportal.nspa.nato.int/Message https://eportal.nspa.nato.int/AC135Public/Schema/v2.0/Message.xsd'
        );

        // --- HEADER ---
        $header = $dom->createElement('HEADER');
        $root->appendChild($header);
        $header->appendChild($dom->createElement('MESSAGE_SERIAL_NUMBER_8722', (string)time()));
        $header->appendChild($dom->createElement('MESSAGE_DATE_TIME_8711', Carbon::now()->toIso8601ZuluString()));
        $header->appendChild($dom->createElement('SOURCE_CODE_8709', 'IDN'));

        // --- BODY ---
        $body = $dom->createElement('BODY');
        $root->appendChild($body);

        foreach ($applications as $app) {
            $ncage = $dom->createElement('NCAGE');
            $body->appendChild($ncage);

            // Data Level 1: Info NCAGE
            $ncage->appendChild($dom->createElement('NCAGE_CODE_4140', $app->ncage_code));
            $ncage->appendChild($dom->createElement('DATE_NCAGE_ESTABLISHED_2262', Carbon::parse($app->created_at)->toIso8601ZuluString()));
            $ncage->appendChild($dom->createElement('DATE_LAST_CHANGE_NCAGE_RECORD_9567', Carbon::parse($app->updated_at)->toIso8601ZuluString()));

            // --- NCAGE_DATA ---
            $ncageData = $dom->createElement('NCAGE_DATA');
            $ncage->appendChild($ncageData);

            $company = $app->companyDetail;
            $identity = $app->identity;

            $ncageData->appendChild($dom->createElement('NCAGE_NAME_8972', $company->name));
            $ncageData->appendChild($dom->createElement('NCAGE_STATUS_CODE_2694', 'A')); // 'A' untuk Active

            $ncageData->appendChild($dom->createElement('NCAGE_TYPE_CODE_4238', $identity->entity_type));

            $ncageData->appendChild($dom->createElement('COUNTRY_CODE_3408', 'IDN'));

            // --- STATE (PROVINCE) ---
            if ($company->province) {
                $state = $dom->createElement('STATE');
                $ncageData->appendChild($state);
                $state->appendChild($dom->createElement('PROVINCE_NAME_8978', $company->province));
            }

            // --- PHYSICAL_ADDRESS ---
            $physicalAddress = $dom->createElement('PHYSICAL_ADDRESS');
            $ncageData->appendChild($physicalAddress);

            // Memecah alamat menjadi 2 baris
            $streetParts = explode("\n", $company->street, 2);
            $streetLine1 = trim($streetParts[0] ?? '');
            $streetLine2 = trim($streetParts[1] ?? '');

            $physicalAddress->appendChild($dom->createElement('STREET_ADDRESS_LINE_1_1082', $streetLine1));
            if (!empty($streetLine2)) {
                $physicalAddress->appendChild($dom->createElement('STREET_ADDRESS_LINE_2_1083', $streetLine2));
            }

            $physicalAddress->appendChild($dom->createElement('GEO_ADDRESS_POSTAL_ZONE_2549', $company->postal_code));
            $physicalAddress->appendChild($dom->createElement('GEO_ADDRESS_CITY_1084', $company->city));

            // --- COMMUNICATION ---
            $communication = $dom->createElement('COMMUNICATION');
            $ncageData->appendChild($communication);

            if ($company->phone) {
                $telephones = $dom->createElement('TELEPHONES');
                $communication->appendChild($telephones);
                $telephones->appendChild($dom->createElement('TELEPHONE_NUMBER_8974', $company->phone));
            }
            if ($company->email) {
                $emails = $dom->createElement('EMAILS');
                $communication->appendChild($emails);
                $emails->appendChild($dom->createElement('EMAIL_ADDRESS_3375', $company->email));
            }
            if ($company->website) {
                $websites = $dom->createElement('WEBSITES');
                $communication->appendChild($websites);
                $websites->appendChild($dom->createElement('WEB_URL_8021', $company->website));
            }
        }

        $xmlContent = $dom->saveXML();
        $fileName = 'NCAGE_IDN_EXPORT_' . Carbon::now()->format('Ymd_His') . '.xml';

        return response($xmlContent, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Mengunduh file XML untuk satu aplikasi individual.
     * Menerima model NcageApplication dari route.
     *
     * @param \App\Models\NcageApplication $application Model yang di-inject dari route.
     * @return \Illuminate\Http\Response
     */
    public function downloadIndividualXml(NcageApplication $application)
    {
        // Validasi data yang masuk.
        // Pastikan hanya status yang benar (validated) yang bisa diunduh.
        if ($application->status_id != 4 || empty($application->ncage_code)) {
            abort(403, 'XML hanya bisa dibuat untuk aplikasi yang sertifikatnya sudah terbit dan memiliki NCAGE Code.');
        }

        // Buat objek DOMDocument untuk membangun XML.
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->standalone = true;
        $dom->formatOutput = true;

        // Setup root element <ncs:MESSAGE> beserta namespace-nya.
        $root = $dom->createElementNS('http://eportal.nspa.nato.int/Message', 'ncs:MESSAGE');
        $dom->appendChild($root);
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttributeNS(
            'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation',
            'http://eportal.nspa.nato.int/Message https://eportal.nspa.nato.int/AC135Public/Schema/v2.0/Message.xsd'
        );

        // Buat elemen <HEADER>.
        $header = $dom->createElement('HEADER');
        $root->appendChild($header);
        $header->appendChild($dom->createElement('MESSAGE_SERIAL_NUMBER_8722', (string)time()));
        $header->appendChild($dom->createElement('MESSAGE_DATE_TIME_8711', Carbon::now()->toIso8601ZuluString()));
        $header->appendChild($dom->createElement('SOURCE_CODE_8709', 'IDN'));

        // Buat elemen <BODY> dan proses satu aplikasi.
        $body = $dom->createElement('BODY');
        $root->appendChild($body);

        // Langsung gunakan variabel $application yang diterima dari route.
        // Alias ke $app agar sisa kode sama dengan versi batch.
        $app = $application;
        // Pastikan relasi sudah di-load untuk efisiensi.
        $app->load(['identity', 'companyDetail']);

        $ncage = $dom->createElement('NCAGE');
        $body->appendChild($ncage);

        // --- Data Level 1: Info NCAGE ---
        $ncage->appendChild($dom->createElement('NCAGE_CODE_4140', $app->ncage_code));
        $ncage->appendChild($dom->createElement('DATE_NCAGE_ESTABLISHED_2262', Carbon::parse($app->created_at)->toIso8601ZuluString()));
        $ncage->appendChild($dom->createElement('DATE_LAST_CHANGE_NCAGE_RECORD_9567', Carbon::parse($app->updated_at)->toIso8601ZuluString()));

        // --- NCAGE_DATA ---
        $ncageData = $dom->createElement('NCAGE_DATA');
        $ncage->appendChild($ncageData);

        $company = $app->companyDetail;
        $identity = $app->identity;

        $ncageData->appendChild($dom->createElement('NCAGE_NAME_8972', $company->name));
        $ncageData->appendChild($dom->createElement('NCAGE_STATUS_CODE_2694', 'A')); // 'A' untuk Active
        $ncageData->appendChild($dom->createElement('NCAGE_TYPE_CODE_4238', $identity->entity_type));
        $ncageData->appendChild($dom->createElement('COUNTRY_CODE_3408', 'IDN'));

        // --- STATE (PROVINCE) ---
        if ($company->province) {
            $state = $dom->createElement('STATE');
            $ncageData->appendChild($state);
            $state->appendChild($dom->createElement('PROVINCE_NAME_8978', $company->province));
        }

        // --- PHYSICAL_ADDRESS ---
        $physicalAddress = $dom->createElement('PHYSICAL_ADDRESS');
        $ncageData->appendChild($physicalAddress);

        $streetParts = explode("\n", $company->street, 2);
        $streetLine1 = trim($streetParts[0] ?? '');
        $streetLine2 = trim($streetParts[1] ?? '');

        $physicalAddress->appendChild($dom->createElement('STREET_ADDRESS_LINE_1_1082', $streetLine1));
        if (!empty($streetLine2)) {
            $physicalAddress->appendChild($dom->createElement('STREET_ADDRESS_LINE_2_1083', $streetLine2));
        }

        $physicalAddress->appendChild($dom->createElement('GEO_ADDRESS_POSTAL_ZONE_2549', $company->postal_code));
        $physicalAddress->appendChild($dom->createElement('GEO_ADDRESS_CITY_1084', $company->city));

        // --- COMMUNICATION ---
        $communication = $dom->createElement('COMMUNICATION');
        $ncageData->appendChild($communication);

        if ($company->phone) {
            $telephones = $dom->createElement('TELEPHONES');
            $communication->appendChild($telephones);
            $telephones->appendChild($dom->createElement('TELEPHONE_NUMBER_8974', $company->phone));
        }
        if ($company->email) {
            $emails = $dom->createElement('EMAILS');
            $communication->appendChild($emails);
            $emails->appendChild($dom->createElement('EMAIL_ADDRESS_3375', $company->email));
        }
        if ($company->website) {
            $websites = $dom->createElement('WEBSITES');
            $communication->appendChild($websites);
            $websites->appendChild($dom->createElement('WEB_URL_8021', $company->website));
        }

        // Simpan XML dan kirim sebagai response untuk diunduh.
        $xmlContent = $dom->saveXML();

        // Gunakan nama file yang spesifik untuk record ini.
        $fileName = 'NCAGE_' . $app->ncage_code . '.xml';

        return response($xmlContent, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

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
    public function downloadDomesticCertificate(NcageRecord $record)
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
        // Ambil path langsung dari kolom 'international_certificate_path'
        $filePath = $application->international_certificate_path;

        // Cek apakah path sertifikat internasional ada dan filenya tersedia
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            // Jika file tidak ditemukan, hentikan proses.
            abort(404, 'File sertifikat tidak ditemukan.');
        }

        // Kembalikan response untuk mengunduh file.
        return Storage::disk('public')->download($filePath);
    }
}
