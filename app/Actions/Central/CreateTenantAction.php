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
     * Create the tenant's central records and start async provisioning.
     *
     * Returns a "provisioning" tenant; TenantCreated runs the queued CreateDatabase
     * → MigrateDatabase → SeedDatabase → FinalizeTenantSetup pipeline, which builds
     * the database and admin user and flips the tenant to "ready".
     *
     * @param  array{name: string, subdomain: string, admin_name?: string, admin_email?: string, admin_password?: string, plan_tier?: string, locale?: string}  $data
     */
    public function execute(array $data): Tenant
    {
        // Cache the admin credentials (hashed) before creating the tenant so
        // FinalizeTenantSetup can read them rather than receive them via the pipeline.
        if (isset($data['admin_email'])) {
            Cache::put("tenant_setup:{$data['subdomain']}", [
                'name' => $data['admin_name'] ?? null,
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password'] ?? Str::password(16)),
                'locale' => $data['locale'] ?? null,
            ], now()->addHour());
        }

        // after_commit ensures the provisioning pipeline dispatches only once this commits.
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
