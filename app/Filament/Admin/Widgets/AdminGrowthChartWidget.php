<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Services\AdminAnalyticsService;
use Filament\Widgets\ChartWidget;

class AdminGrowthChartWidget extends ChartWidget
{
    protected ?string $heading = 'Platform Growth - Last 30 Days';

    protected ?string $pollingInterval = '120s';

    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $analytics = app(AdminAnalyticsService::class)->getDashboardAnalytics();

        return [
            'datasets' => [
                [
                    'label' => 'New trial sign-ups',
                    'data' => $analytics['signups_series'],
                    'fill' => false,
                    'tension' => 0.35,
                    'borderColor' => '#2F8082',
                    'backgroundColor' => '#2F8082',
                ],
                [
                    'label' => 'Landing page visits',
                    'data' => $analytics['visits_series'],
                    'fill' => true,
                    'tension' => 0.35,
                    'borderColor' => '#56B2B4',
                    'backgroundColor' => 'rgba(86, 178, 180, 0.12)',
                ],
            ],
            'labels' => $analytics['labels'],
        ];
    }
}
