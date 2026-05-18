<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use App\Services\AdminAnalyticsService;
use Filament\Widgets\Widget;

class AdminTenantUsageWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.admin-tenant-usage-widget';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array{rows: array<int, array<string, mixed>>}
     */
    public function getViewData(): array
    {
        $rows = app(AdminAnalyticsService::class)->getDashboardAnalytics()['tenants_usage'];

        return [
            'rows' => array_slice($rows, 0, 25),
        ];
    }
}
