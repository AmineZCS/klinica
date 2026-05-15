<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Widgets;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChartWidget extends ChartWidget
{
    protected ?string $heading = 'Monthly Revenue (Last 6 Months)';

    protected ?string $pollingInterval = '120s';

    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(
            fn (int $ago) => Carbon::now()->subMonths($ago)->startOfMonth()
        );

        $revenue = $months->map(function (Carbon $monthStart) {
            return (float) Booking::join('services', 'bookings.service_id', '=', 'services.id')
                ->where('bookings.start_at', '>=', $monthStart)
                ->where('bookings.start_at', '<', $monthStart->copy()->addMonth())
                ->where('bookings.status', BookingStatus::Completed)
                ->whereNull('bookings.deleted_at')
                ->sum('services.price');
        });

        $bookingCounts = $months->map(
            fn (Carbon $monthStart) => Booking::where('start_at', '>=', $monthStart)
                ->where('start_at', '<', $monthStart->copy()->addMonth())
                ->count()
        );

        $labels = $months->map(fn (Carbon $m) => $m->format('M Y'));

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (DA)',
                    'data' => $revenue->values()->toArray(),
                    'fill' => true,
                    'tension' => 0.4,
                    'borderColor' => '#2F8082',
                    'backgroundColor' => 'rgba(47, 128, 130, 0.15)',
                    'pointBackgroundColor' => '#2F8082',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Bookings',
                    'data' => $bookingCounts->values()->toArray(),
                    'fill' => false,
                    'tension' => 0.4,
                    'borderColor' => '#8DAA91',
                    'backgroundColor' => 'rgba(141, 170, 145, 0.15)',
                    'pointBackgroundColor' => '#8DAA91',
                    'borderDash' => [5, 5],
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels->values()->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => ['display' => true, 'text' => 'Revenue (DA)'],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => ['display' => true, 'text' => 'Bookings'],
                    'grid' => ['drawOnChartArea' => false],
                ],
            ],
        ];
    }
}
