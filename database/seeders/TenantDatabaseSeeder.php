<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_bookings',
            'create_bookings',
            'update_bookings',
            'cancel_bookings',
            'view_patients',
            'create_patients',
            'update_patients',
            'delete_patients',
            'view_services',
            'manage_services',
            'view_clinic_settings',
            'manage_clinic_settings',
            'view_employees',
            'manage_employees',
            'manage_schedule',
            'manage_roles',
            'view_audit_log',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'tenant',
            ]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'tenant']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'tenant']);

        $adminRole->syncPermissions($permissions);

        $employeeRole->syncPermissions([
            'view_bookings',
            'create_bookings',
            'update_bookings',
            'cancel_bookings',
            'view_patients',
            'create_patients',
            'update_patients',
        ]);
    }
}
