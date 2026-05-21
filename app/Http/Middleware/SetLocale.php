<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ClinicSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED = ['en', 'fr', 'de', 'es'];

    public function handle(Request $request, Closure $next): Response
    {
        if (tenancy()->initialized) {
            try {
                $locale = ClinicSetting::current()->locale;

                if (in_array($locale, self::SUPPORTED, strict: true)) {
                    app()->setLocale($locale);

                    return $next($request);
                }
            } catch (\Throwable) {
                // Tenancy not ready; fall through to browser detection
            }
        }

        $preferred = $request->getPreferredLanguage(self::SUPPORTED);
        app()->setLocale($preferred ?: 'en');

        return $next($request);
    }
}
