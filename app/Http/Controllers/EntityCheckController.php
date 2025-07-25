<?php

namespace App\Http\Controllers;

use App\Models\NcageRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EntityCheckController extends Controller
{
    public function check(Request $request)
    {
        $user = Auth::user();
        $companyName = strtoupper(trim($user->company_name));

        // Ambil record dan muat relasi ke NcageApplication
        $record = NcageRecord::with('ncageApplication')
            ->where(DB::raw('TRIM(entity_name)'), $companyName)
            ->first();

        if ($record && $record->ncageApplication) {
            // Bangun respons JSON dengan semua data yang dibutuhkan
            return response()->json([
                'status' => 'found',
                'data' => [
                    'id' => $record->id,
                    'entity_name' => $record->entity_name,
                    'ncage_code' => $record->ncage_code,
                    'ncagesd' => trim($record->ncagesd),
                    'application_id' => $record->ncageApplication->id,
                    'domestic_certificate_path' => $record->domestic_certificate_path,
                    'international_certificate_path' => $record->ncageApplication->international_certificate_path,
                ]
            ]);
        } else {
            return response()->json([
                'status' => 'not_found'
            ]);
        }
    }
}
