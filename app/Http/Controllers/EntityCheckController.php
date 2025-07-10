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

        $record = NcageRecord::select(
                'entity_name',
                'ncage_code',
                DB::raw('TRIM(ncagesd) as ncagesd')
            )
            ->where(DB::raw('TRIM(entity_name)'), $companyName)
            ->first();

        if ($record) {
            return response()->json([
                'status' => 'found',
                'data' => $record
            ]);
        } else {
            return response()->json([
                'status' => 'not_found'
            ]);
        }
    }
}
