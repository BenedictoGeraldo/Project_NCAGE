<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\FormNCAGEController;
use App\Http\Controllers\StatusCheckController;
use App\Http\Controllers\CertificateController;
use Illuminate\Http\Request;
use Filament\Http\Middleware\Authenticate as FilamentAuth;
use App\Http\Controllers\NotificationController;
use App\Notifications\ApplicationNeedsRevision;
use App\Notifications\ApplicationRejected;
use App\Notifications\FinalValidation;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\EntityCheckController;
use App\Http\Controllers\SurveyController;
use App\Models\NcageApplication;
use App\Models\NcageRecord;


// =========================================================================
// RUTE PUBLIK
// =========================================================================
Route::middleware(['clearncage'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

// Rute untuk verifikasi OTP
Route::get('/verify-otp', [OtpVerificationController::class, 'show'])->name('verification.notice');
Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');

// =========================================================================
// RUTE KHUSUS TAMU (Hanya untuk yang BELUM login)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Tahap 1: Menampilkan form untuk memasukkan email/nomor telepon
    Route::get('forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');

    // Tahap 2: Mengirim OTP ke email atau WhatsApp
    Route::post('forgot-password', [PasswordResetController::class, 'sendResetOtp'])->name('password.send.otp');

    // Tahap 3: Menampilkan form untuk memasukkan OTP
    Route::get('verify-reset-otp', [PasswordResetController::class, 'showOtpForm'])->name('password.otp.form');

    // Tahap 4: Memverifikasi OTP yang dimasukkan pengguna
    Route::post('verify-reset-otp', [PasswordResetController::class, 'verifyOtp'])->name('password.otp.verify');

    // Tahap 5: Menampilkan form untuk membuat password baru (setelah OTP benar)
    Route::get('reset-password', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');

    // Tahap 6: Menyimpan password baru ke database
    Route::post('reset-password', [PasswordResetController::class, 'updatePassword'])->name('password.update.new');
});


// =========================================================================
// RUTE OTENTIKASI (Sudah login, tapi belum tentu terverifikasi)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Rute untuk proses Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

function normalizeCompanyName($name)
{
    $name = preg_replace('/^(pt|cv|ud|pd|perum|perusahaan|persero)\.?[\s]+/i', '', $name);
    $name = preg_replace('/[^a-z0-9]/i', '', $name);
    return strtolower($name);
}

// =========================================================================
// RUTE TERPROTEKSI (WAJIB SUDAH LOGIN DAN SUDAH VERIFIKASI)
// =========================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    Route::middleware(['clearncage'])->group(function () {
        // Rute Beranda setelah login
        Route::get('/beranda', [HomeController::class, 'index'])->name('beranda');

        // Rute Pantau Status
        Route::get('/pantau-status', [TrackingController::class, 'index'])->name('tracking.index');
        Route::get('/pantau-status/{application}', [TrackingController::class, 'show'])->name('tracking.show');

        // Rute lainnya yang butuh login & verifikasi
        Route::get('/check-status', [StatusCheckController::class, 'check'])->name('status.check.api');
        Route::get('/sertifikat/record/{record}/unduh', [CertificateController::class, 'downloadDomesticCertificate'])->name('certificate.download.record');
        Route::get('/sertifikat/international/{application}/unduh', [CertificateController::class, 'downloadInternationalCertificate'])->name('certificate.download.international');
        Route::post('/survey/{application}', [SurveyController::class, 'store'])->name('survey.store');

        Route::get('/akun', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::patch('/akun', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        //rute untuk cek entitas
        Route::get('/cek-entitas', [EntityCheckController::class, 'index'])->name('entity-check.index');
        // --- BARIS INI DITAMBAHKAN/DISESUAIKAN ---
        // Route baru untuk mengambil data Datatables secara AJAX
        // Menggunakan prefix nama 'entity-check' agar konsisten
        Route::get('/get-ncagerecords-data', [EntityCheckController::class, 'getNcageRecordsData'])->name('entity-check.get-data');
    });
    
    // Rute Pendaftaran NCAGE (DIPINDAHKAN KE SINI)
    Route::get('/pendaftaran-ncage/{step}/{substep?}', [FormNCAGEController::class, 'show'])->name('pendaftaran-ncage.show');
    Route::post('/pendaftaran-ncage', [FormNCAGEController::class, 'handleStep'])->name('pendaftaran-ncage.handle-step');
    Route::post('/pendaftaran-ncage/upload-temp', [FormNCAGEController::class, 'uploadTemp']);
    Route::post('/pendaftaran-ncage/remove-file', [FormNCAGEController::class, 'removeFile']);
    Route::get('/surat-permohonan', [FormNCAGEController::class, 'showSuratPermohonan'])->name('surat-permohonan.show');
    Route::get('/download-surat-permohonan', [FormNCAGEController::class, 'downloadSuratPermohonan'])->name('surat-permohonan.download');
    Route::get('/surat-pernyataan', [FormNCAGEController::class, 'showSuratPernyataan'])->name('surat-pernyataan.show');
    Route::get('/download-surat-pernyataan', [FormNCAGEController::class, 'downloadSuratPernyataan'])->name('surat-pernyataan.download');
    Route::get('/pembaruan-ncage', [FormNCAGEController::class, 'showPerpanjangan'])->name('pendaftaran-ncage.perpanjang');

    //notifikasi route
    // PERBAIKAN: Mengubah 'fetch' menjadi 'index' agar cocok dengan controller
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.fetch');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread.count');

});

Route::middleware([
    FilamentAuth::class,
])
->prefix('admin')
->group(function () {
    Route::get('/sertifikatIndonesia/record/{record}/unduh', [CertificateController::class, 'downloadDomesticCertificate'])
        ->name('admin.Indonesia.certificate.download');
    Route::get('/sertifikatInternasional/application/{application}/unduh', [CertificateController::class, 'downloadInternationalCertificate'])
        ->name('admin.Internasional.certificate.download');
    Route::get('/xml/export/batch', [CertificateController::class, 'downloadInternationalXml'])
        ->name('admin.export.batch.xml');
    Route::get('/xml/application/{application}/download', [CertificateController::class, 'downloadIndividualXml'])
        ->name('admin.download.individual.xml');
    
    Route::post('/ncage-applications/{record}/approve', function (NcageApplication $record) {
        $input = request();

        $company = $input->input('company'); // array
        $entityType = $input->input('entity_type');

        // Normalisasi input
        $normalizedInput = normalizeCompanyName($company['name']);
        
        // Ambil semua record dan bandingkan nama yang sudah dinormalisasi
        $checkCompany = NcageRecord::get()->first(function ($r) use ($normalizedInput) {
            return normalizeCompanyName($r->entity_name) === $normalizedInput;
        });

        // dd($company, $entityType, $normalizedInput, $checkCompany);
        
        if (!$checkCompany){
            $lastRecord = NcageRecord::orderBy('id', 'desc')->first();
            
            if ($lastRecord && preg_match('/^(\d+)([A-Z])$/', $lastRecord->ncage_code, $matches)) {
                // $matches[1] = angka (string), $matches[2] = huruf
                $number = intval($matches[1]) + 1; // tambah 1
                $letter = $matches[2];
        
                // Format ulang angka dengan padding 4 digit (sesuai contoh)
                $newCode = str_pad($number, strlen($matches[1]), '0', STR_PAD_LEFT) . $letter;
            } else {
                // Kalau tidak ada record atau format beda, mulai dari default
                $newCode = '0001Z';
            }
            
            NcageRecord::create(
            [
                'ncage_code' => $newCode,
                'ncagesd' => 'A',
                'toec' => $entityType,
                'entity_name' => $company['name'],
                'street' => $company['street'],
                'city' => $company['city'],
                'psc' => $company['postal_code'],
                'country' => 'INDONESIA',
                'ctr' => 'IDN',
                'stt' => $company['province'],
                'is_sam_requested' => 0,
                'tel' => $company['phone'],
                'fax' => $company['fax'],
                'ema' => $company['email'],
                'www' => $company['website'],
                'pob' => $company['po_box'], 
                'ncage_application_id' => $record->id
            ]);
        } else {
            $newCode = $checkCompany->ncage_code;

            // Update NcageRecord
            $checkCompany->update([
                'toec' => $entityType,
                'entity_name' => $company['name'],
                'street' => $company['street'],
                'city' => $company['city'],
                'psc' => $company['postal_code'],
                'country' => 'INDONESIA',
                'ctr' => 'IDN',
                'stt' => $company['province'],
                'is_sam_requested' => 0,
                'tel' => $company['phone'],
                'fax' => $company['fax'],
                'ema' => $company['email'],
                'www' => $company['website'],
                'pob' => $company['po_box'], 
                'ncage_application_id' => $record->id
            ]);
        }

        $record->update([
            'ncage_code' => $newCode,
            'status_id' => 4,
            'verified_by' => auth('admin')->user()->id
        ]);

        // Kirim notifikasi ke pengguna
        if ($user = $record->user) {
            $user->notify(new FinalValidation());
        }

        return redirect()->route('filament.admin.resources.ncage-applications.index')
            ->with([
                'success' => 'Permohonan disetujui.',
                'warning' => 'Harap lanjutkan ke proses validasi.',
            ]);
    })->name('ncage.approve');

    Route::post('/ncage-applications/{record}/reject', function (NcageApplication $record, Request $request) {
        $record->update([
            'status_id' => 6,
            'revision_notes' => $request->input('reason'),
            'rejected_by' => auth('admin')->user()->id
        ]);

        // Kirim notifikasi ke pengguna
        if ($user = $record->user) {
            $user->notify(new ApplicationRejected());
        }

        return redirect()->route('filament.admin.resources.ncage-applications.index')
            ->with('success', 'Permohonan ditolak.');
    })->name('ncage.reject');

    Route::post('/ncage-applications/{record}/revision', function (NcageApplication $record, Request $request) {
        $record->update([
            'status_id' => 3,
            'revision_notes' => $request->input('reason'),
            'revision_by' => auth('admin')->user()->id
        ]);

        // Kirim notifikasi ke pengguna
        if ($user = $record->user) {
            $user->notify(new ApplicationNeedsRevision());
        }

        return redirect()->route('filament.admin.resources.ncage-applications.index')
            ->with('success', 'Permohonan diminta revisi.');
    })->name('ncage.revision');
});
