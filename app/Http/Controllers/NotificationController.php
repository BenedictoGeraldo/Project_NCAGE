<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mengambil notifikasi terbaru untuk ditampilkan.
     * Method diganti dari fetch() menjadi index() agar sesuai standar.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
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
     * Menandai notifikasi sebagai sudah dibaca saat dropdown dibuka.
     * Method ini akan kita panggil secara terpisah.
     */
    public function markAsRead()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['status' => 'success']);
    }
}
