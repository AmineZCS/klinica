<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayContract;
use App\Services\IndexNowService;
use App\Services\PaddleGateway;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Controllers\TenantAssetsController;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayContract::class, PaddleGateway::class);

        $this->app->singleton(IndexNowService::class, fn () => new IndexNowService(
            key: config('services.indexnow.key'),
            keyLocation: config('services.indexnow.key_location'),
        ));

        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // The TenantAssetsController defaults to InitializeTenancyByDomain, but
        // this app identifies tenants by subdomain only (e.g. "myclinic", not
        // "myclinic.lvh.me"). Override to the correct middleware.
        TenantAssetsController::$tenancyMiddleware = InitializeTenancyBySubdomain::class;
    }
}
