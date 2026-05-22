<?php

declare(strict_types=1);

namespace App\Enums;

enum TenantPlan: string
{
    case Trial = 'trial';
    case Starter = 'starter';
    case Pro = 'pro';
    case Cancelled = 'cancelled';
    // Legacy — kept for DB backward-compatibility
    case Monthly = 'monthly';
    case Yearly = 'yearly';

    public function label(): string
    {
        return match ($this) {
            self::Trial => 'Free Trial',
            self::Starter => 'Starter',
            self::Pro => 'Pro',
            self::Cancelled => 'Cancelled',
            self::Monthly => 'Monthly',
            self::Yearly => 'Yearly',
        };
    }

    public function isActive(): bool
    {
        return match ($this) {
            self::Starter, self::Pro, self::Monthly, self::Yearly => true,
            default => false,
        };
    }

    public function tierKey(): string
    {
        return match ($this) {
            self::Pro, self::Yearly => 'pro',
            default => 'starter',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Trial => 'warning',
            self::Pro => 'success',
            self::Starter => 'primary',
            self::Cancelled => 'danger',
            default => 'gray',
        };
    }

    public function priceId(): string
    {
        return match ($this) {
            self::Pro => (string) config('services.paddle.price_pro'),
            self::Starter => (string) config('services.paddle.price_starter'),
            default => '',
        };
    }

    public static function fromPriceId(?string $priceId): self
    {
        if ($priceId === null) {
            return self::Starter;
        }

        foreach (self::cases() as $plan) {
            if ($plan->priceId() !== '' && $plan->priceId() === $priceId) {
                return $plan;
            }
        }

        return self::Starter;
    }

    /** @return list<string> */
    public static function checkoutableValues(): array
    {
        return [self::Starter->value, self::Pro->value];
    }
}
