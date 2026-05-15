<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Enums\TenantPlan;
use App\Models\Tenant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class AdminStatsWidget extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $total = Tenant::count();
        $active = Tenant::whereIn('plan', [TenantPlan::Monthly->value, TenantPlan::Yearly->value])
            ->count();
        $onTrial = Tenant::where('plan', TenantPlan::Trial->value)
            ->where('trial_ends_at', '>', Carbon::now())
            ->count();
        $disabled = Tenant::where('is_active', false)->count();

        return [
            Stat::make('Total Clinics', $total)
                ->description('Registered tenants')
                ->icon('heroicon-o-building-office')
                ->color('primary'),

            Stat::make('Active Subscriptions', $active)
                ->description('Monthly + Yearly plans')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('On Free Trial', $onTrial)
                ->description('Active trial tenants')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Disabled Clinics', $disabled)
                ->description('Suspended accounts')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
