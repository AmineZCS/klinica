<x-mail::message>
# {{ __('klinica.email.booking_reminder_heading') }}

{{ __('klinica.email.greeting', ['name' => $patientName]) }}

{{ __('klinica.email.booking_reminder_intro') }}

<x-mail::panel>

**{{ __('klinica.email.label_service') }}:** {{ $booking->service?->name ?? 'N/A' }}

**{{ __('klinica.email.label_date') }}:** {{ $booking->start_at->format('l, F j, Y') }}

**{{ __('klinica.email.label_time') }}:** {{ $booking->start_at->format('g:i A') }} – {{ $booking->end_at?->format('g:i A') }}


</x-mail::panel>

<x-mail::button :url="$tenantUrl">
{{ __('klinica.email.booking_reminder_cta') }}
</x-mail::button>

{{ __('klinica.email.booking_reminder_outro') }}

{{ __('klinica.email.salutation') }}
{{ config('app.name') }}
</x-mail::message>
