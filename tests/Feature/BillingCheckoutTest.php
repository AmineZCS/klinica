<?php

declare(strict_types=1);

use App\Contracts\PaymentGatewayContract;
use App\Enums\TenantPlan;
use App\Http\Controllers\BillingController;
use App\Models\Tenant;
use Stancl\Tenancy\Tenancy;

it('redirects trial tenants to a hosted checkout for their selected plan', function (string $tier): void {
    $tenant = new Tenant;
    $tenant->id = $tier.'-clinic';
    $tenant->name = ucfirst($tier).' Clinic';
    $tenant->plan = TenantPlan::Trial;
    $tenant->plan_tier = $tier;

    // Initialize tenancy without bootstrappers so tenancy()->tenant resolves
    // in-memory, with no database switch.
    $tenancy = app(Tenancy::class);
    $tenancy->getBootstrappersUsing = static fn (): array => [];
    $tenancy->initialize($tenant);

    $gateway = Mockery::mock(PaymentGatewayContract::class);
    $gateway->shouldReceive('priceIdForPlan')
        ->once()
        ->with($tier)
        ->andReturn('price_'.$tier);
    $gateway->shouldReceive('createCheckoutUrl')
        ->once()
        ->with('price_'.$tier, $tenant->id, Mockery::type('string'), Mockery::any(), Mockery::any())
        ->andReturn('https://paddle.test/'.$tier);

    $this->app->instance(PaymentGatewayContract::class, $gateway);

    $response = app(BillingController::class)->checkout($tier);

    expect($response->isRedirect())->toBeTrue();
    expect($response->getTargetUrl())->toBe('https://paddle.test/'.$tier);

    $tenancy->end();
    $tenancy->getBootstrappersUsing = null;
})->with([
    'starter trial' => 'starter',
    'pro trial' => 'pro',
]);
