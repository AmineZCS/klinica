<?php

declare(strict_types=1);

use App\Http\Controllers\BillingController;
use App\Http\Controllers\PolarWebhookController;
use App\Http\Controllers\TenantRegistrationController;
use App\Models\ClinicSetting;
use App\Models\Service;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

// Billing checkout (tenant context, auth required) — GET because it only creates
// a Polar checkout session and immediately redirects away; no state mutation.
Route::get('/billing/checkout/{plan}', [BillingController::class, 'checkout'])
    ->middleware(['auth:tenant'])
    ->whereIn('plan', ['starter', 'pro'])
    ->name('billing.checkout');

// Polar webhooks — both paths accepted (CSRF excluded in bootstrap/app.php)
Route::post('/webhooks/polar', [PolarWebhookController::class, 'handle'])->name('webhooks.polar');
Route::post('/polar/webhook', [PolarWebhookController::class, 'handle'])->name('polar.webhook');

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
