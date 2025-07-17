<?php

namespace App\Models;

use Faker\Provider\ar_EG\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NcageApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status_id',
        'revision_notes',
        'documents',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Status.
     * Satu permohonan dimiliki oleh satu status.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Mendefinisikan relasi "belongsTo" ke model User.
     * Satu permohonan dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi "hasOne" ke data identitas (Bagian A).
     */
    public function identity(): HasOne
    {
        return $this->hasOne(ApplicationIdentity::class);
    }

    public function contacts(): HasOne
    {
        return $this->hasOne(ApplicationContact::class);
    }

    public function companyDetail(): HasOne
    {
        return $this->hasOne(CompanyDetail::class);
    }

    public function otherInformation(): HasOne
    {
        return $this->hasOne(OtherInformation::class);
    }

    public function ncageRecord(): HasOne
    {
        return $this->hasOne(NcageRecord::class);
    }

    
    public function getStatusLabel(): string
    {
        return match ($this->status_id) {
            1 => 'Permohonan Dikirim',
            2 => 'Verifikasi Berkas & Data',
            3 => 'Butuh Perbaikan',
            4 => 'Proses Validasi',
            5 => 'Sertifikat Diterbitkan',
            6 => 'Permohonan Ditolak',
            default => 'Status Tidak Dikenal',
        };
    }
}
