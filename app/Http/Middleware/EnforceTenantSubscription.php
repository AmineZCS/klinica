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
            abort(402, 'Your subscription has expired. Please renew to continue.');
        }

        return $next($request);
    }
}
