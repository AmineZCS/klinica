<?php

declare(strict_types=1);

use App\Enums\TenantPlan;
use App\Http\Controllers\BillingController;
use App\Models\Tenant;
use App\Services\PolarService;
use Illuminate\Http\Request;
use Stancl\Tenancy\Tenancy;

it('redirects trial tenants to checkout for their selected plan', function (string $tier): void {
    $tenant = new Tenant;
    $tenant->id = $tier.'-clinic';
    $tenant->name = ucfirst($tier).' Clinic';
    $tenant->plan = TenantPlan::Trial;
    $tenant->plan_tier = $tier;

    $tenancy = app(Tenancy::class);
    $tenancy->getBootstrappersUsing = static fn (): array => [];
    $tenancy->initialize($tenant);

    $polarService = Mockery::mock(PolarService::class);
    $polarService->shouldReceive('productIdForPlan')
        ->once()
        ->with($tier)
        ->andReturn('polar_'.$tier);
    $polarService->shouldReceive('createCheckoutUrl')
        ->once()
        ->with('polar_'.$tier, $tenant->id, Mockery::type('string'))
        ->andReturn('https://polar.test/'.$tier);

    $this->app->instance(PolarService::class, $polarService);

    $response = app(BillingController::class)->checkout(
        Request::create('/billing/checkout/'.$tier, 'GET'),
        $tier,
    );

    expect($response->isRedirect())->toBeTrue();
    expect($response->getTargetUrl())->toBe('https://polar.test/'.$tier);

    $tenancy->end();
    $tenancy->getBootstrappersUsing = null;
})->with([
    'starter trial' => 'starter',
    'pro trial' => 'pro',
]);
