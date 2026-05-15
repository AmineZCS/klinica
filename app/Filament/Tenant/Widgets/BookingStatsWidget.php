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

        // Revenue from completed bookings (joins service price)
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

        return [
            Stat::make("Today's Appointments", $todayCount)
                ->description("{$confirmedToday} confirmed · {$completedToday} completed")
                ->icon('heroicon-o-calendar')
                ->color('info'),

            Stat::make('Pending Approvals', $pendingCount)
                ->description('Awaiting confirmation')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('This Month', $thisMonthCount)
                ->description($monthTrend >= 0 ? "+{$monthTrend}% vs last month" : "{$monthTrend}% vs last month")
                ->icon('heroicon-o-chart-bar')
                ->color($monthTrend >= 0 ? 'success' : 'danger'),

            Stat::make('Completion Rate', $completionRate.'%')
                ->description("{$completedMonth} completed · {$cancelledMonth} cancelled this month")
                ->icon('heroicon-o-check-circle')
                ->color($completionRate >= 70 ? 'success' : ($completionRate >= 40 ? 'warning' : 'danger')),

            Stat::make('Revenue Today', number_format((float) $revenueToday, 0, '.', ' ').' '.$currency)
                ->description('From completed bookings')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Revenue This Month', number_format((float) $revenueMonth, 0, '.', ' ').' '.$currency)
                ->description('From completed bookings')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('New Patients', $newPatientsThisMonth)
                ->description('Registered this month')
                ->icon('heroicon-o-user-plus')
                ->color('primary'),

            Stat::make('Landing Page Visits', number_format((int) $visitsToday))
                ->description('Today · '.number_format((int) $visitsMonth).' this month')
                ->icon('heroicon-o-globe-alt')
                ->color('info'),
        ];
    }
}
