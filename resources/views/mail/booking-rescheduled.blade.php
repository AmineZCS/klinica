<x-mail::message>
# {{ __('klinica.email.booking_rescheduled_heading') }}

{{ __('klinica.email.greeting', ['name' => $patientName]) }}

{{ __('klinica.email.booking_rescheduled_intro') }}

<x-mail::panel>

**{{ __('klinica.email.label_service') }}:** {{ $booking->service?->name ?? 'N/A' }}

**{{ __('klinica.email.booking_rescheduled_label_new_date') }}:** {{ $booking->start_at->format('l, F j, Y') }}

**{{ __('klinica.email.booking_rescheduled_label_new_time') }}:** {{ $booking->start_at->format('g:i A') }} – {{ $booking->end_at?->format('g:i A') }}

@if($booking->employee)
**{{ __('klinica.email.label_assigned_to') }}:** {{ $booking->employee->name }}
@endif

</x-mail::panel>

<x-mail::button :url="$tenantUrl">
{{ __('klinica.email.booking_rescheduled_cta') }}
</x-mail::button>

**{{ __('klinica.email.booking_rescheduled_previous', ['date' => $previousStartAt->format('l, F j, Y'), 'time' => $previousStartAt->format('g:i A')]) }}**

{{ __('klinica.email.booking_rescheduled_outro') }}

{{ __('klinica.email.salutation') }}
{{ config('app.name') }}
</x-mail::message>
