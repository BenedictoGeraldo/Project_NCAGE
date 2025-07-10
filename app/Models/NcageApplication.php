<?php

namespace App\Models;

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
}
