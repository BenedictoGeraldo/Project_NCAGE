<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'ncage_application_id',
        'name',
        'identity_number',
        'address',
        'phone_number',
        'email',
        'position',
    ];
}
