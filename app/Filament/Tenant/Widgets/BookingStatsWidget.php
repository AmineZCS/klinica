<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Widgets;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\ClinicSetting;
use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingStatsWidget extends BaseWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $todayCount = Booking::whereDate('start_at', $today)->count();
        $pendingCount = Booking::where('status', BookingStatus::Pending)->count();
        $confirmedToday = Booking::whereDate('start_at', $today)->where('status', BookingStatus::Confirmed)->count();

        $thisMonthCount = Booking::where('start_at', '>=', $thisMonth)->count();
        $lastMonthCount = Booking::whereBetween('start_at', [$lastMonth, $lastMonthEnd])->count();
        $monthTrend = $lastMonthCount > 0 ? round((($thisMonthCount - $lastMonthCount) / $lastMonthCount) * 100) : 0;

        $completedToday = Booking::whereDate('start_at', $today)->where('status', BookingStatus::Completed)->count();
        $completedMonth = Booking::where('start_at', '>=', $thisMonth)->where('status', BookingStatus::Completed)->count();
        $cancelledMonth = Booking::where('start_at', '>=', $thisMonth)->where('status', BookingStatus::Cancelled)->count();
        $completionRate = ($completedMonth + $cancelledMonth) > 0
            ? round($completedMonth / ($completedMonth + $cancelledMonth) * 100)
            : 0;

        $newPatientsThisMonth = Patient::where('created_at', '>=', $thisMonth)->count();

        $revenueToday = Booking::join('services', 'bookings.service_id', '=', 'services.id')
            ->whereDate('bookings.start_at', $today)
            ->where('bookings.status', BookingStatus::Completed)
            ->whereNull('bookings.deleted_at')
            ->sum('services.price');

        $revenueMonth = Booking::join('services', 'bookings.service_id', '=', 'services.id')
            ->where('bookings.start_at', '>=', $thisMonth)
            ->where('bookings.status', BookingStatus::Completed)
            ->whereNull('bookings.deleted_at')
            ->sum('services.price');

        $visitsToday = DB::table('page_visits')->whereDate('date', $today)->value('count') ?? 0;
        $visitsMonth = DB::table('page_visits')->where('date', '>=', $thisMonth->toDateString())->sum('count');

        $currency = ClinicSetting::current()->currency ?? 'DA';

        $monthTrendDesc = $monthTrend >= 0
            ? __('klinica.dashboard.stat_this_month_desc_up', ['percent' => $monthTrend])
            : __('klinica.dashboard.stat_this_month_desc_down', ['percent' => $monthTrend]);

        return [
            Stat::make(__('klinica.dashboard.stat_today_appointments'), $todayCount)
                ->description(__('klinica.dashboard.stat_today_appointments_desc', ['confirmed' => $confirmedToday, 'completed' => $completedToday]))
                ->icon('heroicon-o-calendar')
                ->color('info'),

            Stat::make(__('klinica.dashboard.stat_pending_approvals'), $pendingCount)
                ->description(__('klinica.dashboard.stat_pending_approvals_desc'))
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make(__('klinica.dashboard.stat_this_month'), $thisMonthCount)
                ->description($monthTrendDesc)
                ->icon('heroicon-o-chart-bar')
                ->color($monthTrend >= 0 ? 'success' : 'danger'),

            Stat::make(__('klinica.dashboard.stat_completion_rate'), $completionRate.'%')
                ->description(__('klinica.dashboard.stat_completion_rate_desc', ['completed' => $completedMonth, 'cancelled' => $cancelledMonth]))
                ->icon('heroicon-o-check-circle')
                ->color($completionRate >= 70 ? 'success' : ($completionRate >= 40 ? 'warning' : 'danger')),

            Stat::make(__('klinica.dashboard.stat_revenue_today'), number_format((float) $revenueToday, 0, '.', ' ').' '.$currency)
                ->description(__('klinica.dashboard.stat_revenue_desc'))
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make(__('klinica.dashboard.stat_revenue_month'), number_format((float) $revenueMonth, 0, '.', ' ').' '.$currency)
                ->description(__('klinica.dashboard.stat_revenue_desc'))
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make(__('klinica.dashboard.stat_new_patients'), $newPatientsThisMonth)
                ->description(__('klinica.dashboard.stat_new_patients_desc'))
                ->icon('heroicon-o-user-plus')
                ->color('primary'),

            Stat::make(__('klinica.dashboard.stat_page_visits'), number_format((int) $visitsToday))
                ->description(__('klinica.dashboard.stat_page_visits_desc', ['month' => number_format((int) $visitsMonth)]))
                ->icon('heroicon-o-globe-alt')
                ->color('info'),
        ];
    }
}
