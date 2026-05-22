<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentGatewayContract;
use App\Enums\TenantPlan;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaddleGateway implements PaymentGatewayContract
{
    private function client(): PendingRequest
    {
        $base = config('services.paddle.sandbox')
            ? 'https://sandbox-api.paddle.com'
            : 'https://api.paddle.com';

        return Http::withToken((string) config('services.paddle.api_key'))
            ->baseUrl($base)
            ->acceptJson()
            ->asJson();
    }

    public function createCheckoutUrl(
        string $priceId,
        string $tenantId,
        string $successUrl,
        ?string $customerEmail = null,
        ?string $gatewayCustomerId = null,
    ): ?string {
        try {
            $payload = [
                'items' => [['price_id' => $priceId, 'quantity' => 1]],
                'custom_data' => ['tenant_id' => $tenantId],
                'checkout' => ['url' => $successUrl],
            ];

            if ($gatewayCustomerId !== null) {
                $payload['customer_id'] = $gatewayCustomerId;
            } elseif ($customerEmail !== null) {
                $payload['customer'] = ['email' => $customerEmail];
            }

            $response = $this->client()->post('/transactions', $payload);

            if (! $response->successful()) {
                Log::error('Paddle transaction create failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return null;
            }

            return $response->json('data.checkout.url');
        } catch (\Throwable $e) {
            Log::error('Paddle checkout exception', ['error' => $e->getMessage()]);

            return null;
        }
    }

    public function swapSubscriptionPlan(string $subscriptionId, string $priceId): bool
    {
        try {
            $response = $this->client()->patch("/subscriptions/{$subscriptionId}", [
                'items' => [['price_id' => $priceId, 'quantity' => 1]],
                'proration_billing_mode' => 'prorated_immediately',
            ]);

            if (! $response->successful()) {
                Log::error('Paddle subscription swap failed', [
                    'subscription_id' => $subscriptionId,
                    'price_id' => $priceId,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Paddle subscription swap exception', [
                'subscription_id' => $subscriptionId,
                'price_id' => $priceId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function cancelSubscription(string $subscriptionId): bool
    {
        try {
            $response = $this->client()->post("/subscriptions/{$subscriptionId}/cancel", [
                'effective_from' => 'next_billing_period',
            ]);

            if (! $response->successful()) {
                Log::error('Paddle subscription cancel failed', [
                    'subscription_id' => $subscriptionId,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Paddle subscription cancel exception', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function createTransaction(
        string $priceId,
        string $tenantId,
        ?string $customerEmail = null,
        ?string $gatewayCustomerId = null,
    ): ?string {
        try {
            $payload = [
                'items' => [['price_id' => $priceId, 'quantity' => 1]],
                'custom_data' => ['tenant_id' => $tenantId],
            ];

            if ($gatewayCustomerId !== null) {
                $payload['customer_id'] = $gatewayCustomerId;
            } elseif ($customerEmail !== null) {
                $payload['customer'] = ['email' => $customerEmail];
            }

            $response = $this->client()->post('/transactions', $payload);

            if (! $response->successful()) {
                Log::error('Paddle transaction create failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return null;
            }

            return $response->json('data.id');
        } catch (\Throwable $e) {
            Log::error('Paddle transaction create exception', ['error' => $e->getMessage()]);

            return null;
        }
    }

    public function priceIdForPlan(string $plan): string
    {
        return TenantPlan::from($plan)->priceId();
    }
}
