<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "User {$i}",
                'company_name' => "Perusahaan {$i}",
                'phone_number' => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'email' => "perusahaanUser{$i}@gmail.com",
                'password' => Hash::make('admin123'), // password sama
                'otp_code' => rand(100000, 999999),
                'otp_expires_at' => Carbon::now()->addMinutes(10),
                'profile_photo_path' => null,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
