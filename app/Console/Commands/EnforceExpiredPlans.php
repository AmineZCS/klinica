<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\TenantPlan;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('plans:enforce-expired')]
#[Description('Mark tenants with expired paid subscriptions as Cancelled.')]
class EnforceExpiredPlans extends Command
{
    public function handle(): int
    {
        $activePlans = array_map(
            fn (TenantPlan $p) => $p->value,
            array_filter(TenantPlan::cases(), fn (TenantPlan $p) => $p->isActive()),
        );

        $expired = Tenant::query()
            ->whereIn('plan', $activePlans)
            ->whereNotNull('plan_expires_at')
            ->where('plan_expires_at', '<', Carbon::now())
            ->get();

        foreach ($expired as $tenant) {
            $tenant->update(['plan' => TenantPlan::Cancelled]);
        }

        $this->info("Marked {$expired->count()} tenant(s) as cancelled.");

        return self::SUCCESS;
    }
}
