<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NcageRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckNcageStatusExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ncage:check-ncage-status-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek apakah change_date lebih dari 5 tahun, jika iya ubah ncagesd dari A menjadi H';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fiveYearsAgo = Carbon::now()->subYears(5);

        $this->info('Memeriksa data NCAGE yang lebih dari 5 tahun...');
        Log::info('Memulai pengecekan NCAGE record lebih dari 5 tahun.', [
            'timestamp' => Carbon::now()->toDateTimeString()
        ]);

        $records = NcageRecord::where('ncagesd', 'A')
            ->whereDate('change_date', '<=', $fiveYearsAgo)
            ->get();

        $count = $records->count();

        if ($count === 0) {
            $this->info('Tidak ada record yang perlu diupdate.');
            Log::info('Tidak ada record yang diupdate pada pengecekan ini.');
            return Command::SUCCESS;
        }

        foreach ($records as $record) {
            $oldStatus = $record->ncagesd;
            $record->update(['ncagesd' => 'H']);

            // Catat setiap perubahan
            Log::info('NcageRecord diperbarui.', [
                'record_id' => $record->id,
                'entity_name' => $record->entity_name,
                'old_status' => $oldStatus,
                'new_status' => 'H',
                'change_date' => $record->change_date,
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }

        $this->info("Sebanyak {$count} record telah diperbarui.");
        Log::info("Total record yang diperbarui: {$count}");

        return Command::SUCCESS;
    }
}
