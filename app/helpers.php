<?php

declare(strict_types=1);

if (! function_exists('klinica_asset')) {
    /**
     * Generate a URL to a tenant-scoped uploaded file.
     *
     * tenant_asset() uses APP_URL (the central domain) as the base, which
     * breaks when the TenantAssetsController tries to look up a tenant for the
     * central domain. This helper uses the CURRENT REQUEST host instead, so the
     * URL is correctly scoped to the tenant subdomain.
     */
    function klinica_asset(string $path): string
    {
        $host = request()->getHost() ?: parse_url(config('app.url'), PHP_URL_HOST);
        $scheme = request()->getScheme() ?: 'http';

        return $scheme.'://'.$host.'/tenancy/assets/'.ltrim($path, '/');
    }
}
