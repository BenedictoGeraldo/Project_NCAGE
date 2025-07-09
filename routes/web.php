<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController; // Perbaiki path 'auth' menjadi 'Auth' sesuai standar
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackingController;


// =========================================================================
// RUTE PUBLIK (Bisa diakses siapa saja)
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');


// =========================================================================
// RUTE KHUSUS TAMU (Hanya untuk yang BELUM login)
// =========================================================================
Route::middleware('guest')->group(function () {
    // Rute Registrasi
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Rute Login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});


// =========================================================================
// RUTE TERPROTEKSI (Hanya untuk yang SUDAH login)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Rute Beranda setelah login
    Route::get('/beranda', function () {
        return 'Ini halaman Beranda khusus untuk user yang sudah login.';
    })->name('beranda');

    // Rute untuk proses Logout (methodnya 'destroy')
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Tempatkan rute lain yang membutuhkan login di sini
    // Contoh:
    Route::get('/pantau-status/{application}', [TrackingController::class, 'show'])->name('tracking.show');
    // Route::get('/buat-daftar-ncage', [FormNCAGEController::class, 'create'])->name('ncage.create');
});