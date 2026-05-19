<x-mail::message>
# {{ __('klinica.email.booking_confirmed_heading') }}

{{ __('klinica.email.greeting', ['name' => $patientName]) }}

{{ __('klinica.email.booking_confirmed_intro') }}

<x-mail::panel>

**{{ __('klinica.email.label_service') }}:** {{ $booking->service?->name ?? 'N/A' }}

**{{ __('klinica.email.label_date') }}:** {{ $booking->start_at->format('l, F j, Y') }}

**{{ __('klinica.email.label_time') }}:** {{ $booking->start_at->format('g:i A') }} – {{ $booking->end_at?->format('g:i A') }}

@if($booking->employee)
**{{ __('klinica.email.label_assigned_to') }}:** {{ $booking->employee->name }}
@endif

</x-mail::panel>

<x-mail::button :url="$tenantUrl">
{{ __('klinica.email.booking_confirmed_cta') }}
</x-mail::button>

@if($booking->notes)
> {{ $booking->notes }}
@endif

{{ __('klinica.email.booking_confirmed_outro') }}

{{ __('klinica.email.salutation') }}
{{ config('app.name') }}
</x-mail::message>
