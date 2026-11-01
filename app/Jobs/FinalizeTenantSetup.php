<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\TenantStatus;
use App\Mail\TenantWelcomeMail;
use App\Models\ClinicSetting;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

/**
 * Final step of the tenant-provisioning pipeline (after CreateDatabase →
 * MigrateDatabase → SeedDatabase): creates the admin user from the cached
 * credentials, marks the tenant ready, and sends the welcome mail. JobPipeline
 * constructs it with the tenant as its only argument.
 */
class FinalizeTenantSetup
{
    public function __construct(public readonly Tenant $tenant) {}

    public function handle(): void
    {
        try {
            /** @var array{name?: string, email?: string, password?: string, locale?: string}|null $setup */
            $setup = Cache::pull("tenant_setup:{$this->tenant->id}");

            if (is_array($setup) && ! empty($setup['email'])) {
                $this->createAdminUser($setup);
            }

            $this->tenant->update(['status' => TenantStatus::Ready]);

            if (is_array($setup) && ! empty($setup['email'])) {
                Mail::to($setup['email'])->queue(new TenantWelcomeMail(
                    tenantName: $this->tenant->name ?? 'Your Clinic',
                    ownerName: $setup['name'] ?? 'there',
                    planTier: $this->tenant->effectiveTier(),
                    trialEndsAt: $this->tenant->trial_ends_at,
                    dashboardLoginUrl: $this->tenant->dashboardLoginUrl(),
                    billingPageUrl: $this->tenant->billingPageUrl(),
                    locale: $setup['locale'] ?? 'en',
                ));
            }
        } catch (\Throwable $e) {
            // Let the signup page stop polling. Earlier pipeline steps failing leave
            // the tenant "provisioning"; the page falls back to a timeout.
            $this->tenant->update(['status' => TenantStatus::Failed]);

            Log::error('Tenant provisioning finalize failed', [
                'tenant_id' => $this->tenant->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * @param  array{name?: string, email?: string, password?: string, locale?: string}  $setup
     */
    private function createAdminUser(array $setup): void
    {
        tenancy()->initialize($this->tenant);

        try {
            $admin = User::create([
                'name' => $setup['name'] ?? 'Clinic Admin',
                'email' => $setup['email'],
                // Already a bcrypt hash; the User "hashed" cast is idempotent.
                'password' => $setup['password'] ?? '',
            ]);

            $admin->assignRole(
                Role::where('name', 'admin')->where('guard_name', 'tenant')->firstOrFail()
            );

            if (! empty($setup['locale'])) {
                ClinicSetting::current()->update(['locale' => $setup['locale']]);
            }
        } finally {
            tenancy()->end();
        }
    }
}
