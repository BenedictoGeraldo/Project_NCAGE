<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NcageRecord extends Model
{
    use HasFactory;

    /**
     * Kita tidak menggunakan created_at dan updated_at untuk tabel ini.
     */
    public $timestamps = false;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'ncage_application_id',
        'ncage_code',
        'ncagesd',
        'toec',
        'entity_name',
        'street',
        'city',
        'psc',
        'country',
        'ctr',
        'stt',
        'ste',
        'is_sam_requested',
        'remarks',
        'last_change_date_international',
        'change_date',
        'creation_date',
        'notified_for_expiration_at',
        'load_date',
        'national',
        'nac',
        'idn',
        'bar',
        'nai',
        'cpv',
        'uns',
        'sic',
        'tel',
        'fax',
        'ema',
        'www',
        'pob',
        'pcc',
        'pcs',
        'rp1_5',
        'nmcrl_ref_count',
        'domestic_certificate_path',
        'domestic_certificate_xml_path',
        'international_certificate_path',
    ];
    protected $casts = [
        'change_date' => 'datetime',
    ];
    /**
     * Mendefinisikan relasi "belongsTo" ke model NcageApplication.
     * Satu NCAGE dimiliki oleh satu permohonan.
     */
    public function ncageApplication(): BelongsTo
    {
        return $this->belongsTo(NcageApplication::class);
    }
}
