<?php

namespace App\Http\Controllers;

use App\Models\NcageApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    /**
     * Method baru untuk memeriksa status dan mengarahkan pengguna.
     */
    public function index()
    {
        // Ambil permohonan dari user yang sedang login
        $application = Auth::user()->ncageApplication;

        if ($application) {
            // JIKA ADA: Redirect ke halaman detail status menggunakan route 'tracking.show'
            return redirect()->route('tracking.show', $application);
        } else {
            // JIKA TIDAK ADA: Tampilkan halaman kosong
            return view('tracking.empty');
        }
    }
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
