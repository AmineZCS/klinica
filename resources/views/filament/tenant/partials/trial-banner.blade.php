@php
    $tenant = tenancy()->tenant;
    $show = $tenant && $tenant->plan?->value === 'trial';
    $daysLeft = $show ? $tenant->trialDaysRemaining() : 0;
    $expired = $show && $daysLeft <= 0;
@endphp

@if ($show)
    <div @class([
        'w-full px-4 py-2 text-sm text-center font-medium',
        'bg-danger-600 text-white' => $expired,
        'bg-warning-400 text-warning-900' => ! $expired && $daysLeft <= 3,
        'bg-warning-100 text-warning-800' => ! $expired && $daysLeft > 3,
    ])>
        @if ($expired)
            Your free trial has expired.
            <a href="{{ \App\Filament\Tenant\Pages\BillingPage::getUrl() }}" class="underline font-semibold">Subscribe now</a> to continue using Klinica.
        @else
            You have <strong>{{ $daysLeft }} {{ Str::plural('day', $daysLeft) }}</strong> left in your free trial.
            <a href="{{ \App\Filament\Tenant\Pages\BillingPage::getUrl() }}" class="underline font-semibold ml-1">Upgrade now &rarr;</a>
        @endif
    </div>
@endif
