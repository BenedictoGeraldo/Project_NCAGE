<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- Tambahkan ini untuk mengelola file

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil (akun) pengguna.
     */
    public function show()
    {
        $user = Auth::user();
        return view('account.account', ['user' => $user]);
    }

    /**
     * Memperbarui data profil pengguna.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */ // <-- TAMBAHKAN BARIS INI
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Update data dasar
        $user->name = $request->name;
        $user->company_name = $request->company_name;
        $user->phone_number = $request->phone_number;

        // Jika ada file foto profil yang di-upload
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        // Simpan semua perubahan
        $user->save();

        return redirect()->route('profile.show')->with('status', 'Profil berhasil diperbarui!');
    }
}