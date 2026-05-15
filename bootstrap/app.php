<?php

use App\Http\Middleware\InitializeTenancyBySubdomainIfPresent;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Initialize tenancy globally so ALL routes — including Livewire's AJAX
        // update endpoint — run in the correct tenant database context.
        // The middleware safely does nothing on the central domain (no subdomain).
        $middleware->prependToGroup('web', InitializeTenancyBySubdomainIfPresent::class);

        $middleware->validateCsrfTokens(except: [
            'webhooks/polar',
            'polar/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
