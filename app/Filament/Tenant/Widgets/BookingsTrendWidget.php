<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Widgets;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BookingsTrendWidget extends ChartWidget
{
    protected ?string $heading = 'Bookings — Last 14 Days';

    protected ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(
            fn (int $ago) => Carbon::today()->subDays($ago)
        );

        $bookingCounts = $days->map(
            fn (Carbon $day) => Booking::whereDate('start_at', $day)->count()
        );

        $completedCounts = $days->map(
            fn (Carbon $day) => Booking::whereDate('start_at', $day)
                ->where('status', BookingStatus::Completed)
                ->count()
        );

        $cancelledCounts = $days->map(
            fn (Carbon $day) => Booking::whereDate('start_at', $day)
                ->where('status', BookingStatus::Cancelled)
                ->count()
        );

        $labels = $days->map(fn (Carbon $day) => $day->format('M j'));

        return [
            'datasets' => [
                [
                    'label' => 'Total Bookings',
                    'data' => $bookingCounts->values()->toArray(),
                    'backgroundColor' => 'rgba(47, 128, 130, 0.7)',
                    'borderColor' => '#2F8082',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Completed',
                    'data' => $completedCounts->values()->toArray(),
                    'backgroundColor' => 'rgba(141, 170, 145, 0.8)',
                    'borderColor' => '#8DAA91',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Cancelled',
                    'data' => $cancelledCounts->values()->toArray(),
                    'backgroundColor' => 'rgba(204, 78, 78, 0.6)',
                    'borderColor' => '#CC4E4E',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels->values()->toArray(),
        ];
    }
}
