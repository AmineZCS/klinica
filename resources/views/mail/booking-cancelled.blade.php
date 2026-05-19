<x-mail::message>
# {{ __('klinica.email.booking_cancelled_heading') }}

{{ __('klinica.email.greeting', ['name' => $patientName]) }}

{{ __('klinica.email.booking_cancelled_intro') }}

<x-mail::panel>

**{{ __('klinica.email.label_service') }}:** {{ $booking->service?->name ?? 'N/A' }}

**{{ __('klinica.email.label_date') }}:** {{ $booking->start_at->format('l, F j, Y') }}

**{{ __('klinica.email.label_time') }}:** {{ $booking->start_at->format('g:i A') }} – {{ $booking->end_at?->format('g:i A') }}

@if($booking->cancellation_reason)
**{{ __('klinica.email.label_reason') }}:** {{ $booking->cancellation_reason }}
@endif

</x-mail::panel>

{{ __('klinica.email.booking_cancelled_outro') }}

<x-mail::button :url="$tenantUrl">
{{ __('klinica.email.booking_cancelled_cta') }}
</x-mail::button>

{{ __('klinica.email.salutation') }}
{{ config('app.name') }}
</x-mail::message>
