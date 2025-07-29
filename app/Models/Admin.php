<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Arr;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    //ini untuk mengizinkan akses ke panel
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    protected $guard_name = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // public function setPasswordAttribute($value)
    // {
    //     if ($value) {
    //         $this->attributes['password'] = Hash::make($value);
    //     }
    // }
}