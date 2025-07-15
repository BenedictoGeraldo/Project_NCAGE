<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function show()
    {
        //menampilkan halaman profil pengguna
        $user = Auth::user();

        //tampilkan view akun dan kirim data pengguna ke dalam
        return view('account.account', ['user' => $user]);
    }

    //memperbarui data pengguna
    public function update(Request $request)
    {
        // Dapatkan pengguna yang sedang login
        $user = Auth::user();

        //validasi input dari form
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        //update data dasar
        $user->name = $request->name;
        $user->company_name = $request->company_name;
        $user->phone_number = $request->phone_number;

        // 2. Jika pengguna memasukkan password baru, update passwordnya
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 3. Simpan SEMUA perubahan ke database dalam satu kali proses
        try {
        // Coba simpan perubahan ke database
            $user->save();
        } catch (\Exception $e) {
            // Jika gagal, hentikan program dan tampilkan pesan error dari database
            dd($e->getMessage());
        }

        //kembali ke halaman akun dengan pesan sukses
        return redirect()->route('profile.show')->with('status', 'Profil berhasil diperbarui');
    }
}
