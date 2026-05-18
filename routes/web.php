<?php

declare(strict_types=1);

use App\Http\Controllers\BillingController;
use App\Http\Controllers\PolarWebhookController;
use App\Http\Controllers\TenantRegistrationController;
use App\Models\ClinicSetting;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Database\Models\Domain;

Route::get('/', function () {
    // If tenancy is initialized (subdomain request), delegate to the tenant landing
    if (tenancy()->initialized) {
        $settings = ClinicSetting::current();
        $services = Service::query()->where('is_active', true)->get();

        return view('tenant.landing', compact('settings', 'services'));
    }

    return view('central.landing');
})->name('home');

// Tenant registration
Route::get('/register', [TenantRegistrationController::class, 'create'])->name('register');
Route::post('/register', [TenantRegistrationController::class, 'store'])->name('register.store');

// Legal pages
Route::get('/privacy', fn () => view('central.privacy'))->name('privacy');
Route::get('/dpa', fn () => view('central.dpa'))->name('dpa');
Route::get('/refund-policy', fn () => view('central.refund-policy'))->name('refund-policy');
Route::get('/terms', fn () => view('central.terms'))->name('terms');

// Billing checkout (tenant context, auth required) — GET because it only creates
// a Polar checkout session and immediately redirects away; no state mutation.
Route::get('/billing/checkout/{plan}', [BillingController::class, 'checkout'])
    ->middleware(['auth:tenant'])
    ->whereIn('plan', ['starter', 'pro'])
    ->name('billing.checkout');

// Polar webhooks — both paths accepted (CSRF excluded in bootstrap/app.php)
Route::post('/webhooks/polar', [PolarWebhookController::class, 'handle'])->name('webhooks.polar');
Route::post('/polar/webhook', [PolarWebhookController::class, 'handle'])->name('polar.webhook');

// for production only
// subdomain validation to check if a requested subdomain is actually a tenant or no to decide wether we issue an ssl certificate for it using Caddy or no
Route::get('/api/v1/validate-tenant', function (Request $request) {
    $incomingDomain = strtolower($request->query('domain'));

    if (blank($incomingDomain)) {
        return response('Invalid Domain Parameter', 400);
    }

    // 1. Instantly whitelist your central core domains
    $centralDomains = ['klinica.space', 'www.klinica.space'];
    if (in_array($incomingDomain, $centralDomains)) {
        return response('Valid Central Domain', 200);
    }

    // Cache the database lookup for 24 hours.
    // This stops malicious scripts from hammering the DB via Caddy.
    $isValidTenant = Cache::remember("domain_auth:{$incomingDomain}", now()->addDay(), function () use ($incomingDomain) {

        // Handle standard subdomains (e.g., test.klinica.space)
        if (str_ends_with($incomingDomain, '.klinica.space')) {
            $subdomain = head(explode('.', $incomingDomain));

            // Check if the tenant ID matches the subdomain prefix
            return \App\Models\Tenant::where('id', $subdomain)->exists();
        }

        // Handle fully custom white-label domains (e.g., www.algiersclinic.dz)
        // This utilizes Stancl Tenancy's built-in Domain model lookup
        return Domain::where('domain', $incomingDomain)->exists();
    });

    if ($isValidTenant) {
        return response('Valid Tenant Domain', 200);
    }

    return response('Unauthorized Domain', 403);
});


// test smtp
Route::get('/test-smtp', function () {
    // send email to medamineben19@gmail.com
    try {
        Mail::raw('This is a test email from Laravel.', function ($message) {
            $message->to('medamineben19@gmail.com')
                ->subject('Test Email');
        });

        return 'Test email sent!';
    } catch (Exception $e) {
        return 'Failed to send test email: '.$e->getMessage();
    }
})->name('test.smtp');


