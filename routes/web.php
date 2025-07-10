<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController; // Perbaiki path 'auth' menjadi 'Auth' sesuai standar
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\FormNCAGEController;
use App\Http\Controllers\EntityCheckController;

// =========================================================================
// RUTE PUBLIK
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

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

    //tampil halaman lupa password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    //proses pengiriman reset password
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    //tampil halaman form reset password
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    //proses pembaruan password
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// =========================================================================
// RUTE OTENTIKASI (Sudah login, tapi belum tentu terverifikasi)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Rute untuk menampilkan & memproses OTP

    // Rute untuk proses Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});


// =========================================================================
// RUTE TERPROTEKSI (WAJIB SUDAH LOGIN DAN SUDAH VERIFIKASI)
// =========================================================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Rute Beranda setelah login
    Route::get('/beranda', function () {
        return 'Ini halaman Beranda khusus untuk user yang sudah login dan terverifikasi.';
    })->name('beranda');

    // Tempatkan rute lain yang butuh verifikasi di sini...
    Route::get('/pantau-status/{application}', [TrackingController::class, 'show'])->name('tracking.show');
    Route::get('/pendaftaran-ncage/{step}/{substep?}', [FormNCAGEController::class, 'show'])->name('pendaftaran-ncage.show');
    Route::post('/pendaftaran-ncage', [FormNCAGEController::class, 'handleStep'])->name('pendaftaran-ncage.handle-step');
    Route::get('/check-entity', [EntityCheckController::class, 'check'])->name('entity.check.api');
});
