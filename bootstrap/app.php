<?php

use App\Http\Middleware\InitializeTenancyBySubdomainIfPresent;
use App\Http\Middleware\RedirectWww;
use App\Http\Middleware\SetLocale;
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
        // Canonicalize www -> apex before anything else (incl. tenancy) runs.
        $middleware->prepend(RedirectWww::class);

        // Initialize tenancy globally so ALL routes — including Livewire's AJAX
        // update endpoint — run in the correct tenant database context.
        // The middleware safely does nothing on the central domain (no subdomain).
        $middleware->prependToGroup('web', InitializeTenancyBySubdomainIfPresent::class);
        $middleware->appendToGroup('web', SetLocale::class);
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: [
            'webhooks/paddle',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
