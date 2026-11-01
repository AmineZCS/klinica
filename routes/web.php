<?php

declare(strict_types=1);

use App\Http\Controllers\BillingController;
use App\Http\Controllers\DomainValidationController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\PaddleWebhookController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Tenant\LandingController;
use App\Http\Controllers\TenantRegistrationController;
use App\Http\Middleware\EnsureSuperAdmin;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (tenancy()->initialized) {
        return app(LandingController::class)->index();
    }

    return view('central.landing');
})->name('home');

// Tenant registration
Route::get('/register', [TenantRegistrationController::class, 'create'])->name('register');
Route::post('/register', [TenantRegistrationController::class, 'store'])->name('register.store');
Route::get('/api/check-subdomain', [TenantRegistrationController::class, 'checkSubdomain'])->name('register.check-subdomain');

// Post-signup provisioning page: polls tenant readiness, then forwards to login.
Route::get('/registering/{tenant}', [TenantRegistrationController::class, 'provisioning'])->name('registering');
Route::get('/registering/{tenant}/status', [TenantRegistrationController::class, 'status'])->name('registering.status');

// Sitemap and llms.txt — central domain only (tenant subdomains have their own routes)
Route::domain(config('tenancy.central_domains')[0])->group(function (): void {
    Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
    Route::get('/llms.txt', [SitemapController::class, 'llms'])->name('llms');
});

// Admin impersonation — a super admin enters a tenant panel via a short-lived token.
Route::get('/admin/impersonate/{tenant}', ImpersonationController::class)
    ->middleware(['web', 'auth', EnsureSuperAdmin::class])
    ->name('admin.impersonate');

// Legal pages
Route::get('/privacy', fn () => view('central.privacy'))->name('privacy');
Route::get('/dpa', fn () => view('central.dpa'))->name('dpa');
Route::get('/refund-policy', fn () => view('central.refund-policy'))->name('refund-policy');
Route::get('/terms', fn () => view('central.terms'))->name('terms');

// Billing checkout (tenant context, auth required) — GET because it only creates
// a Paddle checkout session and immediately redirects away; no state mutation.
Route::get('/billing/checkout/{plan}', [BillingController::class, 'checkout'])
    ->middleware(['auth:tenant'])
    ->whereIn('plan', ['starter', 'pro'])
    ->name('billing.checkout');

// Central checkout proxy — opens Paddle overlay from the approved central domain.
// Tenant billing page redirects here with ?txn=... so Paddle domain check passes.
Route::get('/billing/open', [BillingController::class, 'open'])->name('billing.open');

// Paddle posts back to this verified central domain after checkout completes.
// The ?tenant= param identifies which subdomain to redirect back to.
// Paddle appends its own ?_ptxn= query param; webhook handles actual state update.
Route::get('/billing/return', function (Request $request) {
    $tenant = Tenant::find($request->query('tenant'));

    if (! $tenant) {
        return redirect('/');
    }

    return redirect()->away($tenant->billingPageUrl().'?checkout=complete');
})->name('billing.return');

// Billing plan status — polled by the billing page JS after checkout completes.
Route::get('/billing/plan-status', function () {
    $tenant = tenancy()->tenant;

    if (! $tenant) {
        abort(403);
    }

    $tenant->refresh();

    return response()->json([
        'is_active' => $tenant->isOnActivePlan(),
        'plan' => $tenant->plan?->value ?? 'trial',
    ]);
})->middleware(['auth:tenant'])->name('billing.plan-status');

// Cancel active subscription — cancels at end of current billing period.
Route::post('/billing/cancel', [BillingController::class, 'cancel'])
    ->middleware(['auth:tenant'])
    ->name('billing.cancel');

// Paddle webhooks (CSRF excluded in bootstrap/app.php)
Route::post('/webhooks/paddle', [PaddleWebhookController::class, 'handle'])->name('webhooks.paddle');

// Called by Caddy's on-demand TLS to decide whether to issue a cert for a host.
Route::get('/api/v1/validate-tenant', DomainValidationController::class);
