<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Custom permissions
        $permissions = [
            'verify_ncage_application',
            'validate_ncage_application',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admin']);
        }

        // Optional: assign to role
        // $verifikator = Role::firstOrCreate(['name' => 'verifikator']);
        // $verifikator->givePermissionTo('verify_ncage::application');

        // $validator = Role::firstOrCreate(['name' => 'validator']);
        // $validator->givePermissionTo('validate_ncage::application');
    }
}
