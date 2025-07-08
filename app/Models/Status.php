<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public function ncageApplications()
    {
        return $this->hasMany(NcageApplication::class);
    }
}
