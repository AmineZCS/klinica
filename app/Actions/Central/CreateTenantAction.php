<?php

declare(strict_types=1);

namespace App\Actions\Central;

use App\Enums\TenantPlan;
use App\Mail\TenantWelcomeMail;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class CreateTenantAction
{
    /**
     * Create a new tenant, run migrations/seeding, then optionally create an admin user.
     *
     * @param  array{name: string, subdomain: string, admin_name?: string, admin_email?: string, admin_password?: string, plan_tier?: string}  $data
     */
    public function execute(array $data): Tenant
    {
        // Phase 1 — central DB: create tenant row + domain row atomically.
        // NOTE: Tenant::create() fires TenantCreated → CreateDatabase/MigrateDatabase/SeedDatabase
        // synchronously. CreateDatabase is DDL (not transactional in MySQL), so the tenant DB
        // may exist even if this transaction rolls back. Phase 2 handles cleanup in that case.
        $tenant = DB::transaction(function () use ($data): Tenant {
            $tenant = Tenant::create([
                'id' => $data['subdomain'],
                'name' => $data['name'],
                'owner_name' => $data['admin_name'] ?? null,
                'owner_email' => $data['admin_email'] ?? null,
                'plan' => TenantPlan::Trial,
                'plan_tier' => $data['plan_tier'] ?? 'starter',
                'trial_ends_at' => Carbon::now()->addDays(14),
            ]);

            $tenant->domains()->create(['domain' => $data['subdomain']]);

            return $tenant;
        });

        // Phase 2 — tenant DB: create admin user + assign role.
        // Wrapped in try/finally so tenancy()->end() always runs (critical for Octane workers).
        // On failure, we delete the tenant which fires TenantDeleted → DeleteDatabase cleanup.
        if (isset($data['admin_email'])) {
            try {
                tenancy()->initialize($tenant);

                try {
                    $admin = User::create([
                        'name' => $data['admin_name'] ?? 'Clinic Admin',
                        'email' => $data['admin_email'],
                        'password' => Hash::make($data['admin_password'] ?? 'password'),
                    ]);

                    $adminRole = Role::where('name', 'admin')->where('guard_name', 'tenant')->firstOrFail();
                    $admin->assignRole($adminRole);
                } finally {
                    tenancy()->end();
                }
            } catch (\Throwable $e) {
                Log::error('Tenant user creation failed — rolling back tenant', [
                    'tenant_id' => $tenant->id,
                    'error' => $e->getMessage(),
                ]);

                try {
                    $tenant->delete();
                } catch (\Throwable $deleteException) {
                    Log::critical('Tenant rollback failed — orphaned tenant requires manual cleanup', [
                        'tenant_id' => $tenant->id,
                        'error' => $deleteException->getMessage(),
                    ]);
                }

                throw $e;
            }

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
