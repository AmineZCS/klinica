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
            {{ __('klinica.trial.banner_expired') }}
            <a href="{{ \App\Filament\Tenant\Pages\BillingPage::getUrl() }}" class="underline font-semibold">
                {{ __('klinica.trial.banner_subscribe') }}
            </a>
            {{ __('klinica.trial.banner_continue') }}
        @else
            {!! __('klinica.trial.banner_days_left', ['days' => $daysLeft, 'word' => Str::plural('day', $daysLeft)]) !!}
            <a href="{{ \App\Filament\Tenant\Pages\BillingPage::getUrl() }}" class="underline font-semibold ml-1">
                {!! __('klinica.trial.banner_upgrade') !!}
            </a>
        @endif
    </div>
@endif
