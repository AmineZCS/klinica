<?php

namespace App\Models;

use App\Enums\TenantPlan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase;
    use HasDomains;

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'trial_reminder_sent_at' => 'datetime',
        'plan_expires_at' => 'datetime',
        'plan' => TenantPlan::class,
        'is_active' => 'boolean',
    ];

    public function tenantHost(): string
    {
        $domain = (string) $this->domains()->orderBy('id')->value('domain');

        if ($domain === '') {
            return $this->id.'.'.$this->centralDomain();
        }

        return Str::contains($domain, '.') ? $domain : $domain.'.'.$this->centralDomain();
    }

    public function tenantBaseUrl(): string
    {
        $scheme = parse_url((string) config('app.url', 'http://localhost'), PHP_URL_SCHEME) ?: 'http';

        return $scheme.'://'.$this->tenantHost();
    }

    public function dashboardLoginUrl(): string
    {
        return $this->tenantBaseUrl().'/clinic/login';
    }

    public function billingPageUrl(): string
    {
        return $this->tenantBaseUrl().'/clinic/billing-page';
    }

    public function trialDaysRemaining(): int
    {
        if ($this->trial_ends_at === null || $this->plan !== TenantPlan::Trial) {
            return 0;
        }

        return max(0, (int) Carbon::now()->diffInDays($this->trial_ends_at, false));
    }

    public function effectiveTier(): string
    {
        if ($this->plan !== null && $this->plan !== TenantPlan::Trial) {
            return $this->plan->tierKey();
        }

        return $this->plan_tier ?? 'starter';
    }

    public function maxPatients(): int
    {
        return $this->effectiveTier() === 'pro' ? PHP_INT_MAX : 20;
    }

    public function maxEmployees(): int
    {
        return $this->effectiveTier() === 'pro' ? PHP_INT_MAX : 1;
    }

    public function maxMonthlyBookings(): int
    {
        return $this->effectiveTier() === 'pro' ? PHP_INT_MAX : 30;
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'owner_name',
            'owner_email',
            'is_active',
            'trial_ends_at',
            'trial_reminder_sent_at',
            'plan',
            'plan_tier',
            'plan_expires_at',
            'polar_customer_id',
            'polar_subscription_id',
            'discount_code',
        ];
    }

    private function centralDomain(): string
    {
        $centralDomains = config('tenancy.central_domains', []);

        if (is_array($centralDomains) && $centralDomains !== []) {
            return (string) $centralDomains[0];
        }

        return parse_url((string) config('app.url', 'http://localhost'), PHP_URL_HOST) ?: 'localhost';
    }

    public function isTrialExpired(): bool
    {
        if ($this->trial_ends_at === null) {
            return false;
        }

        return Carbon::now()->isAfter($this->trial_ends_at);
    }

    public function isOnActivePlan(): bool
    {
        if ($this->plan === null) {
            return false;
        }

        return $this->plan->isActive()
            && ($this->plan_expires_at === null || Carbon::now()->isBefore($this->plan_expires_at));
    }
}
