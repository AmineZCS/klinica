<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Tenant\Widgets\BookingStatsWidget;
use App\Filament\Tenant\Widgets\BookingsTrendWidget;
use App\Filament\Tenant\Widgets\PageVisitsWidget;
use App\Filament\Tenant\Widgets\RevenueChartWidget;
use App\Http\Middleware\EnforceTenantSubscription;
use App\Http\Middleware\SetTenantLocale;
use App\Models\ClinicSetting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class TenantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('clinic')
            ->path('clinic')
            ->favicon(asset('favicon.ico'))
            ->login()
            ->colors([
                'primary' => Color::hex('#2F8082'),
                'success' => Color::hex('#8DAA91'),
                'warning' => Color::hex('#56B2B4'),
                'danger' => Color::hex('#CC4E4E'),
            ])
            ->font('Inter')
            ->viteTheme('resources/css/filament/clinic/theme.css')
            ->authGuard('tenant')
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn () => view('filament.tenant.partials.trial-banner'),
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                function (): View|string {
                    if (! auth('tenant')->check()) {
                        return '';
                    }

                    if (ClinicSetting::current()->onboarding_completed_at !== null) {
                        return '';
                    }

                    return view('filament.tenant.partials.onboarding-tour-mount');
                },
            )
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                function (): View|string {
                    if (! auth('tenant')->check()) {
                        return '';
                    }

                    return view('filament.tenant.partials.topbar-clinic-link');
                },
            )
            ->discoverResources(in: app_path('Filament/Tenant/Resources'), for: 'App\Filament\Tenant\Resources')
            ->discoverPages(in: app_path('Filament/Tenant/Pages'), for: 'App\Filament\Tenant\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Tenant/Widgets'), for: 'App\Filament\Tenant\Widgets')
            ->widgets([
                AccountWidget::class,
                BookingStatsWidget::class,
                BookingsTrendWidget::class,
                RevenueChartWidget::class,
                PageVisitsWidget::class,
            ])
            ->middleware([
                InitializeTenancyBySubdomain::class,
                PreventAccessFromCentralDomains::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetTenantLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnforceTenantSubscription::class,
            ]);
    }
}
