<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\NcageRecord;
use App\Models\Admin;

class CheckNcage
{
    public function compose(View $view)
    {
        $user = Auth::user();

        $hasPendingNcage = false;
        $activeNcage = null;

        // Jika user adalah admin, hentikan
        if ($user instanceof Admin) {
            return;
        }

        if ($user) {
            $hasPendingNcage = $user->ncageApplication()
                ->whereIn('status_id', [1, 2, 3, 4])
                ->exists();

            $activeNcage = NcageRecord::where('entity_name', $user->company_name)->first();
        }

        $view->with([
            'hasPendingNcage' => $hasPendingNcage,
            'activeNcage' => $activeNcage,
            'hasActiveNcage' => !is_null($activeNcage),
            'validUntil' => optional($activeNcage?->change_date)->addYears(5)?->format('d M Y'),
        ]);
    }
}
