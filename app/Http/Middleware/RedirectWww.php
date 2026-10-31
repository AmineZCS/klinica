<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Canonicalizes the central site to its bare apex by 301-redirecting any
 * "www." central host to the same path on the apex host. Keeping a single
 * canonical host avoids duplicate content across www/apex and gives crawlers
 * and LLM fetchers one URL per page. Tenant subdomains are never touched.
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
