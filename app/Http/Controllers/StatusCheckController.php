<?php

namespace App\Http\Controllers;

use App\Models\NcageRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Survey;

class StatusCheckController extends Controller
{
    public function check(Request $request)
    {
        App::setLocale('id');
        $user = Auth::user();
        $companyName = strtoupper(trim($user->company_name));

        // Ambil record dan relasi jika ada
        $record = NcageRecord::with('ncageApplication')
            ->where(DB::raw('TRIM(UPPER(entity_name))'), 'LIKE', '%' . $companyName . '%')
            ->first();

        if (!$record) {
            return response()->json(['status' => 'not_found']);
        }

        // Hitung masa berlaku
        $validUntil = $record->change_date
            ? Carbon::parse($record->change_date)->addYears(5)->translatedFormat('j F Y')
            : 'Tidak Tersedia';

        // Bangun data dasar
        $data = [
            'id' => $record->id,
            'entity_name' => $record->entity_name,
            'ncage_code' => $record->ncage_code,
            'ncagesd' => trim($record->ncagesd),
            'domestic_certificate_path' => $record->domestic_certificate_path,
            'valid_until' => $validUntil,
        ];

        // Tambahkan data dari NcageApplication jika ada
        if ($record->ncageApplication) {
            $data['application_id'] = $record->ncageApplication->id;
            $data['international_certificate_path'] = $record->ncageApplication->international_certificate_path;
            $data['is_survey_filled'] = Survey::where('ncage_application_id', $record->ncageApplication->id)->exists();
        }

        return response()->json([
            'status' => 'found',
            'data' => $data,
        ]);
    }
}
