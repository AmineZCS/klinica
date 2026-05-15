<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ClinicSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $locale = ClinicSetting::current()->locale;

            if (in_array($locale, ['en', 'fr', 'de'], strict: true)) {
                app()->setLocale($locale);
            }
        } catch (\Throwable) {
            // Tenancy may not be fully initialized yet; safe to skip
        }

        return $next($request);
    }
}
