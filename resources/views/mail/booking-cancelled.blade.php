<x-mail::message>
# Your booking has been cancelled

Hi **{{ $patientName }}**,

We want to let you know that the following booking has been cancelled.

<x-mail::panel>

**Service:** {{ $booking->service?->name ?? 'N/A' }}

**Date:** {{ $booking->start_at->format('l, F j, Y') }}

**Time:** {{ $booking->start_at->format('g:i A') }} – {{ $booking->end_at?->format('g:i A') }}

@if($booking->cancellation_reason)
**Reason:** {{ $booking->cancellation_reason }}
@endif

</x-mail::panel>

We apologise for any inconvenience caused. You are welcome to book a new appointment at any time.

<x-mail::button :url="config('app.url')">
Book Again
</x-mail::button>

Thanks,
{{ config('app.name') }}
</x-mail::message>

