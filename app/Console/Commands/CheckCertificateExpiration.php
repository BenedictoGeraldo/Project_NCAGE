<?php

namespace App\Console\Commands; // Ini harus App\Console\Commands;

use App\Models\NcageRecord;
use App\Models\NcageApplication;
use App\Models\User;
use App\Notifications\CertificateExpiringSoon;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckCertificateExpiration extends Command // Ini harus CheckCertificateExpiration extends Command
{
    protected $signature = 'certificates:check-expiration';
    protected $description = 'Checks for certificates expiring soon and sends notifications.';

    public function handle()
    {
        $expirationThresholdMonths = 3;

        $fiveYearsAgo = Carbon::now()->subYears(5);
        $threeMonthsBeforeFiveYearsAgo = Carbon::now()->subYears(5)->addMonths($expirationThresholdMonths);

        $this->info("Current Date: " . Carbon::now()->format('Y-m-d H:i:s'));
        $this->info("Expiration Threshold (change_date <=): " . $threeMonthsBeforeFiveYearsAgo->format('Y-m-d'));
        $this->info("Exclusion Threshold (change_date >): " . $fiveYearsAgo->subDay()->format('Y-m-d'));

        $recordsToNotify = NcageRecord::with('ncageApplication.user')
            ->whereNotNull('change_date')
            ->whereNull('notified_for_expiration_at')
            ->whereDate('change_date', '<=', $threeMonthsBeforeFiveYearsAgo)
            ->whereDate('change_date', '>', $fiveYearsAgo->subDay())
            ->get();

        $this->info("Found " . $recordsToNotify->count() . " records matching criteria.");

        if ($recordsToNotify->isEmpty()) {
            $this->info('No certificates found expiring soon.');
            return Command::SUCCESS;
        }

        foreach ($recordsToNotify as $record) {
            $user = $record->ncageApplication->user ?? null;

            if (!$user) {
                $this->warn("Skipping notification for record ID: {$record->id}. User not found via ncage_application (ncage_application_id: {$record->ncage_application_id}) or user ID in associated application.");
                continue;
            }

            $this->info("Sending notification for record ID: {$record->id} to user: {$user->email}");
            Notification::send($user, new CertificateExpiringSoon($record));

            $record->update(['notified_for_expiration_at' => Carbon::now()]);
        }

        $this->info('Certificate expiration check completed.');
        return Command::SUCCESS;
    }
}