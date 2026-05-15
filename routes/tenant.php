<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\GdprExportController;
use App\Http\Controllers\Tenant\LandingController;
use App\Http\Controllers\Tenant\PublicBookingController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function (): void {
    Route::get('/', [LandingController::class, 'index'])->name('tenant.home');

    // Public booking
    Route::prefix('book')->name('book.')->group(function (): void {
        Route::get('/', [PublicBookingController::class, 'create'])->name('create');
        Route::post('/', [PublicBookingController::class, 'store'])->name('store');
    });

    // GDPR data exports (authenticated tenant users only)
    Route::middleware(['auth:tenant'])->prefix('gdpr')->name('gdpr.')->group(function (): void {
        Route::get('/export/patients', [GdprExportController::class, 'patients'])->name('export.patients');
        Route::get('/export/bookings', [GdprExportController::class, 'bookings'])->name('export.bookings');
    });
});
