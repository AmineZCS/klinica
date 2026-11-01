<?php

declare(strict_types=1);

namespace App\Actions\Central;

use App\Enums\TenantPlan;
use App\Enums\TenantStatus;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTenantAction
{
    /**
     * Create a tenant's central records and kick off asynchronous provisioning.
     *
     * The returned tenant is in the "provisioning" state: creating it fires
     * TenantCreated, which runs the queued CreateDatabase → MigrateDatabase →
     * SeedDatabase → FinalizeTenantSetup pipeline on the worker. That pipeline
     * creates the tenant database, the admin user, and flips the tenant to
     * "ready". The signup page polls the tenant status until then.
     *
     * @param  array{name: string, subdomain: string, admin_name?: string, admin_email?: string, admin_password?: string, plan_tier?: string, locale?: string}  $data
     */
    public function execute(array $data): Tenant
    {
        // Stash admin credentials in the central cache BEFORE creating the tenant,
        // so FinalizeTenantSetup can read them without receiving them through the
        // job pipeline. The password is hashed; nothing is cached in plaintext.
        // Caching first also covers the sync-queue case (tests), where the pipeline
        // runs inline during Tenant::create().
        if (isset($data['admin_email'])) {
            Cache::put("tenant_setup:{$data['subdomain']}", [
                'name' => $data['admin_name'] ?? null,
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password'] ?? Str::password(16)),
                'locale' => $data['locale'] ?? null,
            ], now()->addHour());
        }

        // Central DB: create the tenant + its domain atomically. With after_commit
        // enabled, the provisioning pipeline is only dispatched once this commits.
        return DB::transaction(function () use ($data): Tenant {
            $tenant = Tenant::create([
                'id' => $data['subdomain'],
                'name' => $data['name'],
                'status' => TenantStatus::Provisioning,
                'owner_name' => $data['admin_name'] ?? null,
                'owner_email' => $data['admin_email'] ?? null,
                'plan' => TenantPlan::Trial,
                'plan_tier' => $data['plan_tier'] ?? 'starter',
                'trial_ends_at' => Carbon::now()->addDays(14),
            ]);

            $tenant->domains()->create(['domain' => $data['subdomain']]);

            return $tenant;
        });
    }
}
