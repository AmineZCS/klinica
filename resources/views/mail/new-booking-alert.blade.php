<x-mail::message>
# {{ __('klinica.email.new_booking_alert_heading') }}

{{ __('klinica.email.new_booking_alert_intro') }}

<x-mail::panel>

**{{ __('klinica.email.new_booking_alert_patient') }}**

**{{ __('klinica.email.new_booking_alert_name') }}:** {{ $patientName }}

**{{ __('klinica.patient.field_email') }}:** {{ $patientEmail }}

**{{ __('klinica.patient.field_phone') }}:** {{ $patientPhone }}

</x-mail::panel>

<x-mail::panel>

**{{ __('klinica.email.new_booking_alert_service_section') }}**

**{{ __('klinica.email.label_service') }}:** {{ $booking->service?->name ?? 'N/A' }}

**{{ __('klinica.email.label_date') }}:** {{ $booking->start_at->format('l, F j, Y') }}

**{{ __('klinica.email.label_time') }}:** {{ $booking->start_at->format('g:i A') }} – {{ $booking->end_at?->format('g:i A') }}

@if($booking->employee)
**{{ __('klinica.email.label_assigned_to') }}:** {{ $booking->employee->name }}
@endif

</x-mail::panel>

<x-mail::button :url="$dashboardBookingsUrl">
{{ __('klinica.email.new_booking_alert_action') }}
</x-mail::button>

> {{ __('klinica.email.new_booking_alert_note') }}

{{ __('klinica.email.salutation') }}
{{ config('app.name') }}
</x-mail::message>
