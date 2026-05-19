<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

        // Use APP_URL (central domain) explicitly — route() would use the current
        // tenant subdomain as base, which Paddle won't accept as an approved checkout URL.
        $successUrl = rtrim((string) config('app.url'), '/')
            .'/billing/return?tenant='.urlencode((string) $tenant->id);

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

    public function cancel(Request $request): JsonResponse
    {
        $tenant = tenancy()->tenant;

        if (! $tenant) {
            abort(403);
        }

        if (! $tenant->isOnActivePlan() || empty($tenant->paddle_subscription_id)) {
            return response()->json(['error' => 'No active subscription to cancel.'], 422);
        }

        $reason = $request->input('reason');

        $gateway = app(PaymentGatewayContract::class);
        $cancelled = $gateway->cancelSubscription((string) $tenant->paddle_subscription_id);

        if (! $cancelled) {
            return response()->json(['error' => 'Unable to cancel at this time. Please try again or contact support.'], 500);
        }

        $tenant->update([
            'cancellation_reason' => $reason,
            'plan_cancels_at' => $tenant->plan_expires_at,
        ]);

        return response()->json([
            'ok' => true,
            'access_until' => $tenant->plan_expires_at?->format('M j, Y'),
        ]);
    }
}
