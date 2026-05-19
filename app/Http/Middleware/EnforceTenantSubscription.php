<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceTenantSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenancy()->tenant;

        if (! $tenant) {
            abort(503, 'Tenant not found.');
        }

        if ($tenant->is_active === false) {
            abort(403, 'This clinic account has been suspended. Please contact support.');
        }

        if ($tenant->isTrialExpired() && ! $tenant->isOnActivePlan()) {
            $billingRoute = 'filament.clinic.pages.billing-page';

            // Let billing page through so tenant can actually subscribe.
            // Also let Livewire component-update requests through — they power the billing
            // page UI and enforce their own per-component authorization.
            if ($request->routeIs($billingRoute) || $request->hasHeader('X-Livewire')) {
                return $next($request);
            }

            return redirect()->route($billingRoute)->with(
                'warning',
                'Your trial has expired. Please subscribe to continue.'
            );
        }

        return $next($request);
    }
}
