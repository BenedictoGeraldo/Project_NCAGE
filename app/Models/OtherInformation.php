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
        'branch_office_street',
        'branch_office_city',
        'branch_office_postal_code',
        'affiliate_company',
        'affiliate_company_street',
        'affiliate_company_city',
        'affiliate_company_postal_code',
    ];
}
