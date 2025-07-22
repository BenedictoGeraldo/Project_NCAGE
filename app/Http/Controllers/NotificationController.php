<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // <-- Tambahkan ini untuk mempermudah

class NotificationController extends Controller
{
    /**
     * Mengambil notifikasi terbaru untuk pengguna yang sedang login.
     */
    public function fetch()
    {
        /** @var \App\Models\User $user */ // <--- TAMBAHKAN INI
        $user = Auth::user();

        // Ambil 10 notifikasi terbaru
        $notifications = $user->notifications()->latest()->limit(10)->get();
        
        // Hitung notifikasi yang belum dibaca
        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(Request $request)
    {
        // Validasi request, pastikan 'ids' adalah array
        $request->validate([
            'ids' => 'required|array'
        ]);

        /** @var \App\Models\User $user */ // <--- TAMBAHKAN INI JUGA
        $user = Auth::user();
        
        // Cari notifikasi yang belum dibaca berdasarkan ID yang dikirim
        // dan tandai sebagai sudah dibaca.
        $user->unreadNotifications
             ->whereIn('id', $request->ids)
             ->markAsRead();

        return response()->json(['status' => 'success']);
    }
}