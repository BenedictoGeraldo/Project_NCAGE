<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NcageRecord;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables; // <--- BARIS INI DITAMBAHKAN

class EntityCheckController extends Controller
{
    public function index(Request $request)
    {
        // Metode index ini sekarang hanya akan menampilkan view 'check-entity.index'
        // Data akan diambil secara asynchronous oleh Datatables melalui metode getNcageRecordsData
        return view('check-entity.index');
    }

    /**
     * Metode baru untuk mengambil data NcageRecord untuk Datatables via AJAX.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNcageRecordsData(Request $request)
    {
        // Memastikan permintaan datang dari AJAX (yang umum untuk Datatables)
        if ($request->ajax()) {
            // Ambil data dari model NcageRecord.
            // Pastikan kolom yang dipilih di sini sesuai dengan nama kolom di database Anda
            // dan akan sesuai dengan kolom yang ditampilkan di view nanti.
            $data = NcageRecord::select('ncage_code', 'entity_name', 'ncagesd', 'stt', 'city');

            // --- Bagian Opsional: Filter data berdasarkan user yang login ---
            // Jika Anda ingin hanya menampilkan data yang didaftarkan oleh user yang sedang login,
            // aktifkan baris kode di bawah ini. Pastikan tabel 'ncage_records' Anda memiliki
            // kolom 'user_id' yang menyimpan ID user yang mendaftar.
            // $user = Auth::user();
            // if ($user) {
            //     $data->where('user_id', $user->id); // Sesuaikan 'user_id' jika nama kolomnya berbeda
            // }
            // -----------------------------------------------------------------

            // Memproses data menggunakan Yajra Datatables
            return Datatables::of($data)
                    ->addIndexColumn() // Menambahkan kolom 'No.' secara otomatis
                    // Anda bisa menambahkan kolom aksi (misalnya tombol 'Lihat' atau 'Edit') di sini jika diperlukan
                    // Contoh:
                    // ->addColumn('action', function($row){
                    //     $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
                    //     return $btn;
                    // })
                    // ->rawColumns(['action']) // Penting jika kolom aksi mengandung HTML
                    ->make(true); // Mengembalikan respons JSON yang sesuai untuk Datatables
        }
    }
}
