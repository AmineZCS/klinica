<?php

declare(strict_types=1);

namespace App\Actions\Central;

use App\Enums\TenantPlan;
use App\Mail\TenantWelcomeMail;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class CreateTenantAction
{
    /**
     * Create a new tenant, run migrations/seeding, then optionally create an admin user.
     *
     * @param  array{name: string, subdomain: string, admin_name?: string, admin_email?: string, admin_password?: string}  $data
     */
    public function execute(array $data): Tenant
    {
        $tenant = Tenant::create([
            'id' => $data['subdomain'],
            'name' => $data['name'],
            'owner_name' => $data['admin_name'] ?? null,
            'owner_email' => $data['admin_email'] ?? null,
            'plan' => TenantPlan::Trial,
            'plan_tier' => $data['plan_tier'] ?? 'starter',
            'trial_ends_at' => Carbon::now()->addDays(14),
        ]);

        // The TenantCreated pipeline (CreateDatabase → MigrateDatabase → SeedDatabase)
        // runs synchronously here. After this, the tenant DB is ready with roles/permissions.
        $tenant->domains()->create(['domain' => $data['subdomain']]);

        // Create the admin user in the tenant context if registration data was provided.
        if (isset($data['admin_email'])) {
            tenancy()->initialize($tenant);

            $admin = User::create([
                'name' => $data['admin_name'] ?? 'Clinic Admin',
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password'] ?? 'password'),
            ]);

            $adminRole = Role::where('name', 'admin')->where('guard_name', 'tenant')->firstOrFail();
            $admin->assignRole($adminRole);

            tenancy()->end();

            Mail::to($data['admin_email'])->queue(new TenantWelcomeMail(
                tenantName: $tenant->name ?? 'Your Clinic',
                ownerName: $data['admin_name'] ?? 'there',
                planTier: $tenant->effectiveTier(),
                trialEndsAt: $tenant->trial_ends_at,
                dashboardLoginUrl: $tenant->dashboardLoginUrl(),
                billingPageUrl: $tenant->billingPageUrl(),
            ));
        }

        return $tenant;
    }
}
