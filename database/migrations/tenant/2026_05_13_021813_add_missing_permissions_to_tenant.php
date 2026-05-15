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

        $newPermissions = ['manage_schedule', 'manage_roles'];

        foreach ($newPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'tenant',
            ]);
        }

        $adminRole = Role::where('name', 'admin')->where('guard_name', 'tenant')->first();

        if ($adminRole !== null) {
            $adminRole->givePermissionTo($newPermissions);
        }
    }

    public function down(): void
    {
        Permission::where('guard_name', 'tenant')
            ->whereIn('name', ['manage_schedule', 'manage_roles'])
            ->delete();
    }
};
