<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::firstOrCreate([
            'name' => 'view_audit_log',
            'guard_name' => 'tenant',
        ]);

        $adminRole = Role::where('name', 'admin')->where('guard_name', 'tenant')->first();

        if ($adminRole !== null) {
            $adminRole->givePermissionTo('view_audit_log');
        }
    }

    public function down(): void
    {
        Permission::where('guard_name', 'tenant')
            ->where('name', 'view_audit_log')
            ->delete();
    }
};
