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

    public function getApplicationTypeLabelAttribute(): string
    {
        return match ((int) $this->application_type) {
            1 => 'Perorangan',
            2 => 'Perusahaan / Kelompok',
            default => '-',
        };
    }

    public function getNcageRequestTypeLabelAttribute(): string
    {
        return match ((int) $this->ncage_request_type) {
            1 => 'Permohonan Baru',
            2 => 'Perbarui Data / Update',
            default => '-',
        };
    }

    public function getPurposeLabelAttribute(): string
    {
        return match ((int) $this->purpose) {
            1 => 'SAM.GOV',
            2 => 'Pengadaan',
            3 => $this->other_purpose ?? 'Lainnya',
            default => '-',
        };
    }

    public function getEntityTypeLabelAttribute(): string
    {
        return match ($this->entity_type) {
            'E' => 'Pabrikan',
            'F' => 'Suplier/Distributor/Sales/Ritel',
            'G' => 'Jasa Layanan/Organisasi Profesional',
            'H' => 'Pemerintah, Kementerian, Lembaga',
            default => '-',
        };
    }
}
