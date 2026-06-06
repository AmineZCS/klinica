<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowService
{
    private const API_URL = 'https://api.indexnow.org/indexnow';

    public function __construct(
        private readonly string $key,
        private readonly string $keyLocation,
    ) {}

    /**
     * Submit a single tenant's landing page URL to IndexNow.
     * Returns true on success (HTTP 200 or 202).
     */
    public function submitTenant(Tenant $tenant): bool
    {
        $url = $tenant->tenantBaseUrl().'/';

        return $this->submit(
            host: parse_url($url, PHP_URL_HOST),
            urls: [$url],
        );
    }

    /**
     * Submit multiple tenants, one request per tenant (each has a unique host).
     * Returns the number of tenants successfully submitted.
     */
    public function submitTenants(Collection $tenants): int
    {
        return $tenants->filter(
            fn (Tenant $tenant) => $this->submitTenant($tenant)
        )->count();
    }

    private function submit(string $host, array $urls): bool
    {
        try {
            $response = Http::timeout(10)->post(self::API_URL, [
                'host' => $host,
                'key' => $this->key,
                'keyLocation' => $this->keyLocation,
                'urlList' => $urls,
            ]);

            $this->logResponse($host, $urls, $response);

            return in_array($response->status(), [200, 202]);
        } catch (\Throwable $e) {
            Log::warning('IndexNow submission failed', [
                'host' => $host,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function logResponse(string $host, array $urls, Response $response): void
    {
        $context = ['host' => $host, 'urls' => $urls, 'status' => $response->status()];

        if (in_array($response->status(), [200, 202])) {
            Log::info('IndexNow: submitted successfully', $context);
        } else {
            Log::warning('IndexNow: unexpected response', $context + ['body' => $response->body()]);
        }
    }
}
