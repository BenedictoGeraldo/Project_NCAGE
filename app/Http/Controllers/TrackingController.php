<?php

namespace App\Http\Controllers;

use App\Models\NcageApplication;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Menampilkan halaman detail status permohonan.
     *
     * @param  \App\Models\NcageApplication  $application
     * @return \Illuminate\View\View
     */
    public function show(NcageApplication $application)
    {
        if ($application->user_id !== auth()->id()) {
            abort(404);
        }
        
        $application->load(['status', 'identity']);

        // Kirim data permohonan ke view 'tracking.index'
        return view('tracking.index', [
            'application' => $application
        ]);
    }
}
