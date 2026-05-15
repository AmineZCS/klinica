<?php

declare(strict_types=1);

namespace App\Services;

use Danestves\LaravelPolar\Checkout;
use Danestves\LaravelPolar\LaravelPolar;
use Illuminate\Support\Facades\Log;
use Polar\Models\Components\SubscriptionProrationBehavior;
use Polar\Models\Components\SubscriptionUpdateProduct;

class PolarService
{
    public function createCheckoutUrl(string $productId, string $tenantId, string $successUrl): ?string
    {
        try {
            return Checkout::make([$productId])
                ->withMetadata(['tenant_id' => $tenantId])
                ->withSuccessUrl($successUrl)
                ->url();
        } catch (\Throwable $e) {
            Log::error('Polar checkout exception', ['error' => $e->getMessage()]);
        }

        return null;
    }

    public function productIdForPlan(string $tier): string
    {
        return $tier === 'pro'
            ? config('services.polar.product_pro')
            : config('services.polar.product_starter');
    }

    public function swapSubscriptionPlan(string $subscriptionId, string $productId): bool
    {
        try {
            LaravelPolar::updateSubscription(
                subscriptionId: $subscriptionId,
                request: new SubscriptionUpdateProduct(
                    productId: $productId,
                    prorationBehavior: SubscriptionProrationBehavior::Prorate,
                ),
            );

            return true;
        } catch (\Throwable $e) {
            Log::error('Polar subscription swap exception', [
                'subscription_id' => $subscriptionId,
                'product_id' => $productId,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }
}
