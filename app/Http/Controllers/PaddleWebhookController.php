<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TenantPlan;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaddleWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        if (! $this->verifySignature($request)) {
            Log::warning('Paddle webhook signature verification failed');
            abort(403, 'Invalid signature.');
        }

        $payload = $request->all();
        $type = $payload['event_type'] ?? null;

        Log::info('Paddle webhook received', ['event_type' => $type]);

        match ($type) {
            'subscription.created', 'subscription.updated' => $this->handleSubscriptionUpserted($payload),
            'subscription.canceled' => $this->handleSubscriptionCancelled($payload),
            default => null,
        };

        return response()->json(['ok' => true]);
    }

    private function verifySignature(Request $request): bool
    {
        $secret = (string) config('services.paddle.webhook_secret');
        $header = (string) $request->header('Paddle-Signature', '');

        if ($header === '' || $secret === '') {
            return false;
        }

        // Header format: ts=1234567890;h1=abc123...
        $parts = [];
        foreach (explode(';', $header) as $part) {
            [$key, $value] = array_pad(explode('=', $part, 2), 2, '');
            $parts[$key] = $value;
        }

        if (! isset($parts['ts'], $parts['h1'])) {
            return false;
        }

        $expected = hash_hmac('sha256', $parts['ts'].':'.$request->getContent(), $secret);

        return hash_equals($expected, $parts['h1']);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function handleSubscriptionUpserted(array $payload): void
    {
        $data = $payload['data'] ?? [];
        $tenant = $this->resolveTenant($data);

        if (! $tenant) {
            return;
        }

        $priceId = $data['items'][0]['price']['id'] ?? null;
        $plan = $this->planFromPriceId($priceId);
        $expiresAt = isset($data['current_billing_period']['ends_at'])
            ? Carbon::parse($data['current_billing_period']['ends_at'])
            : now()->addMonth();

        $tenant->update([
            'paddle_customer_id' => $data['customer_id'] ?? $tenant->paddle_customer_id,
            'paddle_subscription_id' => $data['id'] ?? null,
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
        $tenant = $this->resolveTenant($payload['data'] ?? []);

        if (! $tenant) {
            return;
        }

        $tenant->update(['plan' => TenantPlan::Cancelled]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveTenant(array $data): ?Tenant
    {
        $tenantId = $data['custom_data']['tenant_id'] ?? null;

        if ($tenantId) {
            return Tenant::find($tenantId);
        }

        $customerId = $data['customer_id'] ?? null;

        return $customerId ? Tenant::where('paddle_customer_id', $customerId)->first() : null;
    }

    private function planFromPriceId(?string $priceId): TenantPlan
    {
        return match ($priceId) {
            config('services.paddle.price_pro') => TenantPlan::Pro,
            config('services.paddle.price_starter') => TenantPlan::Starter,
            default => TenantPlan::Starter,
        };
    }
}
