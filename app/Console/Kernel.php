<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CheckCertificateExpiration;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Pastikan ini ada jika Anda ingin scheduler menjalankannya setiap hari
        $schedule->command(CheckCertificateExpiration::class)->dailyAt('02:00');
        // Atau untuk testing: $schedule->command(CheckCertificateExpiration::class)->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Pastikan ini memuat semua command dari direktori Commands
        $this->load(__DIR__.'/Commands'); // <--- PASTIKAN BARIS INI TIDAK DIKOMENTARI

        require base_path('routes/console.php');
    }
}