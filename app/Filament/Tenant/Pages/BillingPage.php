<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Pages;

use App\Enums\TenantPlan;
use App\Services\PlanLimitsService;
use Filament\Actions\Action;
use Filament\Pages\Page;

class BillingPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 90;

    protected string $view = 'filament.tenant.pages.billing-page';

    public static function getNavigationLabel(): string
    {
        return __('klinica.billing.nav_label');
    }

    public static function canAccess(): bool
    {
        return auth('tenant')->check();
    }

    protected function getHeaderActions(): array
    {
        $tenant = tenancy()->tenant;

        if ($tenant === null) {
            return [];
        }

        // Trial users subscribe via Paddle.js overlay in the page cards below.
        // Only show a header action for active-plan swaps (server-side API call).
        if ($tenant->plan === TenantPlan::Starter) {
            return [
                Action::make('upgrade_pro')
                    ->label(__('klinica.billing.upgrade_pro_action'))
                    ->color('success')
                    ->icon('heroicon-o-rocket-launch')
                    ->url(route('billing.checkout', ['plan' => 'pro']))
                    ->openUrlInNewTab(false),
            ];
        }

        return [];
    }

    private function buildCentralUrl(string $path): string
    {
        $scheme = parse_url((string) config('app.url', 'http://localhost'), PHP_URL_SCHEME) ?: 'https';
        $centralDomains = config('tenancy.central_domains', []);
        $host = is_array($centralDomains) && $centralDomains !== []
            ? (string) $centralDomains[0]
            : (parse_url((string) config('app.url', 'http://localhost'), PHP_URL_HOST) ?: 'localhost');

        return $scheme.'://'.$host.$path;
    }

    /** @return array<string, mixed> */
    public function getViewData(): array
    {
        $tenant = tenancy()->tenant;

        if ($tenant === null) {
            return [];
        }

        $tier = $tenant->effectiveTier();
        $limits = $tier === 'pro'
            ? PlanLimitsService::pro()
            : PlanLimitsService::starter();

        return [
            'tenant' => $tenant,
            'tier' => $tier,
            'limits' => $limits,
            'trialDaysRemaining' => $tenant->trialDaysRemaining(),
            'isOnTrial' => $tenant->plan === TenantPlan::Trial,
            'isActive' => $tenant->isOnActivePlan(),
            'planLabel' => $tenant->plan?->value ?? 'trial',
            'planExpiresAt' => $tenant->plan_expires_at,
            'planCancelsAt' => $tenant->plan_cancels_at,
            'paddleClientToken' => (string) config('services.paddle.client_token'),
            'paddleSandbox' => (bool) config('services.paddle.sandbox'),
            'paddlePriceStarter' => (string) config('services.paddle.price_starter'),
            'paddlePricePro' => (string) config('services.paddle.price_pro'),
            'centralBillingOpenUrl' => $this->buildCentralUrl('/billing/open'),
        ];
    }
}
