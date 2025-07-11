<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherInformation extends Model
{
    use HasFactory;

    protected $table = 'other_informations';

    protected $fillable = [
        'ncage_application_id',
        'products',
        'production_capacity',
        'number_of_employees',
        'branch_office_name',
        'branch_office_address',
    ];
}
