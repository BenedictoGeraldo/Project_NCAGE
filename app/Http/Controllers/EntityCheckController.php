<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NcageRecord; // Pastikan ini di-use
use Illuminate\Support\Facades\Auth; // Jika Anda ingin memfilter data berdasarkan user login

class EntityCheckController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // 
        // Ambil data dari tabel ncage_records
        // Anda bisa memfilter berdasarkan user login jika hanya ingin menampilkan entitas yang didaftarkan oleh user tersebut.
        // Contoh untuk mengambil semua record (sesuaikan jika ada filter):
        $ncageRecords = NcageRecord::select('ncage_code', 'entity_name', 'ncagesd', 'stt', 'city') // Pilih kolom yang ingin ditampilkan // Urutkan berdasarkan yang terbaru
                                 ->paginate(10); // Tambahkan pagination untuk performa lebih baik

        // Jika Anda ingin memfilter berdasarkan pengguna yang login:
        // $user = Auth::user();
        // $ncageRecords = NcageRecord::where('user_id', $user->id) // Sesuaikan dengan kolom user_id di tabel ncage_records
        //                          ->select('ncage_code', 'company_name', 'status')
        //                          ->latest()
        //                          ->paginate(10);


        return view('check-entity.index', compact('ncageRecords'));
    }
}