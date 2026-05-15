<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TenantPlan;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PolarWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        $type = $payload['type'] ?? null;

        Log::info('Polar webhook received', ['type' => $type]);

        match ($type) {
            'subscription.created', 'subscription.updated' => $this->handleSubscriptionUpserted($payload),
            'subscription.canceled' => $this->handleSubscriptionCancelled($payload),
            default => null,
        };

        return response()->json(['ok' => true]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function handleSubscriptionUpserted(array $payload): void
    {
        $tenant = $this->resolveTenant($payload);

        if (! $tenant) {
            return;
        }

        $productId = $payload['data']['product_id'] ?? null;
        $plan = $this->planFromProductId($productId);
        $expiresAt = Carbon::parse($payload['data']['current_period_end'] ?? now()->addMonth());

        $tenant->update([
            'polar_customer_id' => $payload['data']['customer_id'] ?? $tenant->polar_customer_id,
            'polar_subscription_id' => $payload['data']['id'] ?? null,
            'plan' => $plan,
            'plan_tier' => $plan->tierKey(),
            'plan_expires_at' => $expiresAt,
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function handleSubscriptionCancelled(array $payload): void
    {
        $tenant = $this->resolveTenant($payload);

        if (! $tenant) {
            return;
        }

        $tenant->update(['plan' => TenantPlan::Cancelled]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function resolveTenant(array $payload): ?Tenant
    {
        $tenantId = $payload['data']['metadata']['tenant_id'] ?? null;

        if ($tenantId) {
            return Tenant::find($tenantId);
        }

        $customerId = $payload['data']['customer_id'] ?? null;

        return $customerId ? Tenant::where('polar_customer_id', $customerId)->first() : null;
    }

    private function planFromProductId(?string $productId): TenantPlan
    {
        return match ($productId) {
            config('services.polar.product_pro') => TenantPlan::Pro,
            config('services.polar.product_starter') => TenantPlan::Starter,
            default => TenantPlan::Starter,
        };
    }
}
