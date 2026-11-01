<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 301-redirects a "www." central host to its bare apex so the central site has
 * one canonical URL (no www/apex duplicate content). Tenant subdomains are untouched.
 */
class RedirectWww
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        if (str_starts_with($host, 'www.')) {
            $apex = substr($host, 4);

            if (in_array($apex, config('tenancy.central_domains'), true)) {
                return redirect()->away(
                    $request->getScheme().'://'.$apex.$request->getRequestUri(),
                    301
                );
            }
        }

        return $next($request);
    }
}
