<?php

namespace App\Http\Controllers;

use App\Models\NcageApplication;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    public function store(Request $request, NcageApplication $application)
    {
        $validator = Validator::make($request->all(), [
            'q1_kesesuaian_persyaratan' => 'required|integer|between:1,4',
            'q2_kemudahan_prosedur' => 'required|integer|between:1,4',
            'q3_kecepatan_pelayanan' => 'required|integer|between:1,4',
            'q4_kewajaran_biaya' => 'required|integer|between:1,4',
            'q5_kesesuaian_produk' => 'required|integer|between:1,4',
            'q6_kompetensi_petugas' => 'required|integer|between:1,4',
            'q7_perilaku_petugas' => 'required|integer|between:1,4',
            'q8_kualitas_sarana' => 'required|integer|between:1,4',
            'q9_penanganan_pengaduan' => 'required|integer|between:1,4',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Cek agar tidak mengisi survei dua kali
        if ($application->survey) {
            return response()->json(['success' => false, 'message' => 'Survei sudah pernah diisi.'], 400);
        }

        $data = $request->all();
        $data['ncage_application_id'] = $application->id;

        Survey::create($data);

        return response()->json(['success' => true, 'message' => 'Terima kasih telah mengisi survei.']);
    }
}
