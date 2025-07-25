<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationIdentity extends Model
{
    use HasFactory;

    protected $fillable = [
        'ncage_application_id',
        'submission_date',
        'application_type',
        'ncage_request_type',
        'purpose',
        'other_purpose',
        'entity_type',
        'building_ownership_status',
        'is_ahu_registered',
        'office_coordinate',
        'nib',
        'npwp',
        'business_field',
        'created_at',
        'updated_at'
    ];

    public function getApplicationTypeLabel(): string
    {
        return match ((int) $this->application_type) {
            1 => 'Perorangan',
            2 => 'Perusahaan / Kelompok',
            default => 'Jenis Permohonan Tidak Diketahui',
        };
    }

    public function getNcageRequestTypeLabel(): string
    {
        return match ((int) $this->ncage_request_type) {
            1 => 'Baru',
            2 => 'Perpanjangan',
            default => 'Jenis Permohonan Tidak Diketahui',
        };
    }
}
