<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\PolarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function checkout(Request $request, string $plan): RedirectResponse
    {
        $tenant = tenancy()->tenant;

        if (! $tenant) {
            abort(403);
        }

        $polar = app(PolarService::class);
        $productId = $polar->productIdForPlan($plan);
        $currentTier = $tenant->effectiveTier();
        $isOnActivePlan = $tenant->isOnActivePlan();

        if ($isOnActivePlan && $currentTier === $plan) {
            return back()->with('success', 'You are already on this plan.');
        }

        if ($isOnActivePlan && ! empty($tenant->polar_subscription_id)) {
            $swapped = $polar->swapSubscriptionPlan(
                (string) $tenant->polar_subscription_id,
                (string) $productId,
            );

            if (! $swapped) {
                return back()->with('error', 'Unable to switch your plan right now. Please try again.');
            }

            return back()->with('success', 'Plan change requested successfully. It should reflect shortly after the update is confirmed.');
        }

        $successUrl = $tenant->billingPageUrl().'?success=1';

        $checkoutUrl = $polar->createCheckoutUrl(
            $productId,
            $tenant->id,
            $successUrl,
        );

        if ($checkoutUrl === null) {
            return back()->with('error', 'Unable to create checkout. Please try again.');
        }

        return redirect()->away($checkoutUrl);
    }
}
