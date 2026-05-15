<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Stancl\Tenancy\Exceptions\NotASubdomainException;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;

/**
 * A safe wrapper around InitializeTenancyBySubdomain that gracefully skips
 * initialization on central domains (where no subdomain is present) instead
 * of throwing a NotASubdomainException.
 */
class InitializeTenancyBySubdomainIfPresent extends InitializeTenancyBySubdomain
{
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (NotASubdomainException) {
            return $next($request);
        }
    }
}
