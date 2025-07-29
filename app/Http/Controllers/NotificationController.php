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
     * Mengambil jumlah notifikasi yang belum dibaca untuk pengguna yang sedang login.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadCount()
    {
        $user = Auth::user();

        // Pastikan pengguna sudah login
        if (!$user) {
            return response()->json(['count' => 0], 401); // Unauthorized
        }

        // Mengambil jumlah notifikasi yang belum dibaca dari database
        $unreadCount = $user->unreadNotifications->count();

        return response()->json(['count' => $unreadCount]);
    }

    /**
     * Menandai semua notifikasi yang belum dibaca untuk pengguna yang sedang login sebagai sudah dibaca.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request)
    {
        $user = Auth::user();

        // Pastikan pengguna sudah login
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Tandai semua notifikasi yang belum dibaca sebagai sudah dibaca di database
        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true, 'message' => 'Notifications marked as read.']);
    }
}
