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
        return 'Billing';
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

        $actions = [];
        $tier = $tenant->effectiveTier();

        if ($tenant->plan === TenantPlan::Trial) {
            $actions[] = Action::make('subscribe_current_plan')
                ->label($tier === 'pro' ? 'Subscribe to Pro — €49/mo' : 'Subscribe to Starter — €19/mo')
                ->color($tier === 'pro' ? 'success' : 'primary')
                ->icon($tier === 'pro' ? 'heroicon-o-rocket-launch' : 'heroicon-o-check-circle')
                ->url(route('billing.checkout', ['plan' => $tier]))
                ->openUrlInNewTab(false);

            if ($tier === 'starter') {
                $actions[] = Action::make('upgrade_pro')
                    ->label('Upgrade to Pro — €49/mo')
                    ->color('success')
                    ->icon('heroicon-o-rocket-launch')
                    ->url(route('billing.checkout', ['plan' => 'pro']))
                    ->openUrlInNewTab(false);
            } else {
                $actions[] = Action::make('switch_to_starter')
                    ->label('Choose Starter — €19/mo')
                    ->color('gray')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->url(route('billing.checkout', ['plan' => 'starter']))
                    ->openUrlInNewTab(false);
            }
        }

        if ($tenant->plan === TenantPlan::Starter) {
            $actions[] = Action::make('upgrade_pro')
                ->label('Upgrade to Pro — €49/mo')
                ->color('success')
                ->icon('heroicon-o-rocket-launch')
                ->url(route('billing.checkout', ['plan' => 'pro']))
                ->openUrlInNewTab(false);
        }

        return $actions;
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
        ];
    }
}
