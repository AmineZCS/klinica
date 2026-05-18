<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Services\AdminAnalyticsService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsWidget extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $totals = app(AdminAnalyticsService::class)->getDashboardAnalytics()['totals'];

        return [
            Stat::make('Total Clinics', number_format((int) $totals['total_clinics']))
                ->description('Registered tenants')
                ->icon('heroicon-o-building-office')
                ->color('primary'),

            Stat::make('Active Subscriptions', number_format((int) $totals['active_subscriptions']))
                ->description('Paying clinics (monthly + yearly)')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Free Trial Sign-ups (30d)', number_format((int) $totals['trial_signups_last_30_days']))
                ->description(number_format((int) $totals['active_trials']).' currently on trial')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Landing Visits (30d)', number_format((int) $totals['landing_visits_last_30_days']))
                ->description(number_format((int) $totals['landing_visits_total']).' all-time visits')
                ->icon('heroicon-o-globe-alt')
                ->color('info'),

            Stat::make('Total Bookings', number_format((int) $totals['total_bookings']))
                ->description('Across all tenant clinics')
                ->icon('heroicon-o-calendar-days')
                ->color('success'),

            Stat::make('Total Services', number_format((int) $totals['total_services']))
                ->description(number_format((int) $totals['total_employees']).' employees onboarded')
                ->icon('heroicon-o-briefcase')
                ->color('primary'),
        ];
    }
}
