<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PageVisitsWidget extends ChartWidget
{
    protected ?string $pollingInterval = '60s';

    public function getHeading(): string
    {
        return __('klinica.dashboard.chart_visits_heading');
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(
            fn (int $ago) => Carbon::today()->subDays($ago)
        );

        $dateStrings = $days->map(fn (Carbon $d) => $d->toDateString());

        $rows = DB::table('page_visits')
            ->whereIn('date', $dateStrings->toArray())
            ->pluck('count', 'date');

        $counts = $days->map(fn (Carbon $d) => (int) ($rows[$d->toDateString()] ?? 0));

        return [
            'datasets' => [
                [
                    'label' => __('klinica.dashboard.chart_visits_label'),
                    'data' => $counts->values()->toArray(),
                    'fill' => true,
                    'tension' => 0.4,
                    'borderColor' => '#56B2B4',
                    'backgroundColor' => 'rgba(86, 178, 180, 0.12)',
                    'pointBackgroundColor' => '#56B2B4',
                ],
            ],
            'labels' => $days->map(fn (Carbon $d) => $d->format('M j'))->values()->toArray(),
        ];
    }
}
