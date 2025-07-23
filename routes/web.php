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
use App\Http\Controllers\EntityCheckController;
use App\Http\Controllers\CertificateController;
use App\Models\NcageApplication;
use Illuminate\Http\Request;
use Filament\Http\Middleware\Authenticate as FilamentAuth;
use App\Http\Controllers\NotificationController;
use App\Notifications\ApplicationNeedsRevision;
use App\Notifications\ApplicationRejected;
use App\Notifications\FinalValidation;


// =========================================================================
// RUTE PUBLIK
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

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

    // Rute Lupa Password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// =========================================================================
// RUTE OTENTIKASI (Sudah login, tapi belum tentu terverifikasi)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Rute untuk proses Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::post('/ncage-applications/{record}/approve', function (NcageApplication $record) {
    $record->update(['status_id' => 4]);

    // Kirim notifikasi ke pengguna
    if ($user = $record->user) {
        $user->notify(new FinalValidation());
    }

    return redirect()->route('filament.admin.resources.ncage-applications.index')
        ->with('success', 'Permohonan disetujui.');
})->name('ncage.approve');

Route::post('/ncage-applications/{record}/reject', function (NcageApplication $record, Request $request) {
    $record->update([
        'status_id' => 6,
        'revision_notes' => $request->input('reason'),
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
    ]);

    // Kirim notifikasi ke pengguna
    if ($user = $record->user) {
        $user->notify(new ApplicationNeedsRevision());
    }

    return redirect()->route('filament.admin.resources.ncage-applications.index')
        ->with('success', 'Permohonan diminta revisi.');
})->name('ncage.revision');

// =========================================================================
// RUTE TERPROTEKSI (WAJIB SUDAH LOGIN DAN SUDAH VERIFIKASI)
// =========================================================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Rute Beranda setelah login
    Route::get('/beranda', [HomeController::class, 'index'])->name('beranda');

    // Rute Pantau Status
    Route::get('/pantau-status', [TrackingController::class, 'index'])->name('tracking.index');
    Route::get('/pantau-status/{application}', [TrackingController::class, 'show'])->name('tracking.show');

    // Rute Pendaftaran NCAGE (DIPINDAHKAN KE SINI)
    Route::get('/pendaftaran-ncage/{step}/{substep?}', [FormNCAGEController::class, 'show'])->name('pendaftaran-ncage.show');
    Route::post('/pendaftaran-ncage', [FormNCAGEController::class, 'handleStep'])->name('pendaftaran-ncage.handle-step');
    Route::post('/pendaftaran-ncage/upload-temp', [FormNCAGEController::class, 'uploadTemp']);
    Route::post('/pendaftaran-ncage/remove-file', [FormNCAGEController::class, 'removeFile']);
    Route::get('/surat-permohonan', [FormNCAGEController::class, 'showSuratPermohonan'])->name('surat-permohonan.show');
    Route::get('/download-surat-permohonan', [FormNCAGEController::class, 'downloadSuratPermohonan'])->name('surat-permohonan.download');
    Route::get('/surat-pernyataan', [FormNCAGEController::class, 'showSuratPernyataan'])->name('surat-pernyataan.show');
    Route::get('/download-surat-pernyataan', [FormNCAGEController::class, 'downloadSuratPernyataan'])->name('surat-pernyataan.download');

    // Rute lainnya yang butuh login & verifikasi
    Route::get('/check-entity', [EntityCheckController::class, 'check'])->name('entity.check.api');
    Route::get('/sertifikat/record/{record}/unduh', [CertificateController::class, 'downloadFromRecord'])->name('certificate.download.record');

    Route::get('/akun', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/akun', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    //notifikasi route
    Route::get('/notifications', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

Route::middleware([
    FilamentAuth::class,
])
->prefix('admin')
->group(function () {
    Route::get('/sertifikat/record/{record}/unduh', [CertificateController::class, 'downloadFromRecord'])
         ->name('admin.certificate.download');
    Route::get('/sertifikat/record/{record}/unduh-xml', [CertificateController::class, 'downloadXml'])
         ->name('admin.certificate.download.xml');
    Route::get('/sertifikat/record/{record}/unduh-berkas', [CertificateController::class, 'downloadBundle'])
         ->name('admin.certificate.download.bundle');
});
