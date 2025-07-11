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
}
