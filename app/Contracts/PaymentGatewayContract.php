<?php

declare(strict_types=1);

namespace App\Contracts;

interface PaymentGatewayContract
{
    /**
     * Create a hosted checkout URL and redirect the customer there.
     */
    public function createCheckoutUrl(
        string $priceId,
        string $tenantId,
        string $successUrl,
        ?string $customerEmail = null,
        ?string $gatewayCustomerId = null,
    ): ?string;

    /**
     * Swap an active subscription to a different price/plan.
     */
    public function swapSubscriptionPlan(string $subscriptionId, string $priceId): bool;

    /**
     * Cancel a subscription immediately.
     */
    public function cancelSubscription(string $subscriptionId): bool;

    /**
     * Create a draft transaction and return its ID for Paddle.Checkout.open().
     */
    public function createTransaction(
        string $priceId,
        string $tenantId,
        ?string $customerEmail = null,
        ?string $gatewayCustomerId = null,
    ): ?string;

    /**
     * Resolve the gateway price ID for a given plan slug (e.g. "starter", "pro").
     */
    public function priceIdForPlan(string $plan): string;
}
