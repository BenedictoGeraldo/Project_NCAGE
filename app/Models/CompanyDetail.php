<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'ncage_application_id',
        'name',
        'province',
        'city',
        'street',
        'postal_code',
        'po_box',
        'phone',
        'fax',
        'email',
        'website',
        'affiliate'
    ];
}
