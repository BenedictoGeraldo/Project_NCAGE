<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    protected $fillable = [
        'ncage_application_id', 'q1_kesesuaian_persyaratan', 'q2_kemudahan_prosedur',
        'q3_kecepatan_pelayanan', 'q4_kewajaran_biaya', 'q5_kesesuaian_produk',
        'q6_kompetensi_petugas', 'q7_perilaku_petugas', 'q8_kualitas_sarana',
        'q9_penanganan_pengaduan',
    ];
}
