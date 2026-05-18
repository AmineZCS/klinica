<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Http\RedirectResponse;

class BillingController extends Controller
{
    public function checkout(string $plan): RedirectResponse
    {
        $tenant = tenancy()->tenant;

        if (! $tenant) {
            abort(403);
        }

        $gateway = app(PaymentGatewayContract::class);
        $priceId = $gateway->priceIdForPlan($plan);
        $currentTier = $tenant->effectiveTier();
        $isOnActivePlan = $tenant->isOnActivePlan();

        if ($isOnActivePlan && $currentTier === $plan) {
            return back()->with('success', 'You are already on this plan.');
        }

        if ($isOnActivePlan && ! empty($tenant->paddle_subscription_id)) {
            $swapped = $gateway->swapSubscriptionPlan(
                (string) $tenant->paddle_subscription_id,
                $priceId,
            );

            if (! $swapped) {
                return back()->with('error', 'Unable to switch your plan right now. Please try again.');
            }

            return back()->with('success', 'Plan change requested successfully. It should reflect shortly after the update is confirmed.');
        }

        $successUrl = route('billing.return', ['tenant' => $tenant->id]);

        $checkoutUrl = $gateway->createCheckoutUrl(
            priceId: $priceId,
            tenantId: $tenant->id,
            successUrl: $successUrl,
            customerEmail: $tenant->owner_email,
            gatewayCustomerId: $tenant->paddle_customer_id ?: null,
        );

        if ($checkoutUrl === null) {
            return back()->with('error', 'Unable to create checkout. Please try again.');
        }

        return redirect()->away($checkoutUrl);
    }
}
