<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Stancl\Tenancy\Database\Models\Domain;

class DomainValidationController extends Controller
{
    /**
     * Called by Caddy's on-demand TLS to decide whether to issue a cert for a
     * host. Cached for a day so probes can't hammer the DB through Caddy.
     */
    public function __invoke(Request $request): Response
    {
        $incomingDomain = strtolower((string) $request->query('domain'));

        if (blank($incomingDomain)) {
            return response('Invalid Domain Parameter', 400);
        }

        $centralDomains = ['klinica.space', 'www.klinica.space'];

        if (in_array($incomingDomain, $centralDomains, true)) {
            return response('Valid Central Domain', 200);
        }

        $isValidTenant = Cache::remember("domain_auth:{$incomingDomain}", now()->addDay(), function () use ($incomingDomain): bool {
            if (str_ends_with($incomingDomain, '.klinica.space')) {
                $subdomain = head(explode('.', $incomingDomain));

                return Tenant::where('id', $subdomain)->exists();
            }

            // Custom white-label domains resolve through the Domain model.
            return Domain::where('domain', $incomingDomain)->exists();
        });

        return $isValidTenant
            ? response('Valid Tenant Domain', 200)
            : response('Unauthorized Domain', 403);
    }
}
