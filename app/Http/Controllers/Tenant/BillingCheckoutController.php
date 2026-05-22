<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Contracts\PaymentGatewayContract;
use App\Enums\TenantPlan;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BillingCheckoutController extends Controller
{
    public function __construct(private readonly PaymentGatewayContract $gateway) {}

    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'string', Rule::in(TenantPlan::checkoutableValues())],
        ]);

        $plan = TenantPlan::from($validated['plan']);
        $priceId = $plan->priceId();
        $tenant = tenancy()->tenant;

        $transactionId = $this->gateway->createTransaction(
            priceId: $priceId,
            tenantId: $tenant->id,
            customerEmail: $tenant->owner_email ?: null,
            gatewayCustomerId: $tenant->paddle_customer_id ?: null,
        );

        if (! $transactionId) {
            return response()->json(['error' => 'Checkout creation failed. Please try again.'], 502);
        }

        return response()->json(['transaction_id' => $transactionId]);
    }
}
