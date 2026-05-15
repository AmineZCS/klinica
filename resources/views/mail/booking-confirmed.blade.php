<x-mail::message>
# Your booking is confirmed!

Hi **{{ $patientName }}**,

Great news! Your booking has been confirmed.

<x-mail::panel>

**Service:** {{ $booking->service?->name ?? 'N/A' }}

**Date:** {{ $booking->start_at->format('l, F j, Y') }}

**Time:** {{ $booking->start_at->format('g:i A') }} – {{ $booking->end_at?->format('g:i A') }}

@if($booking->employee)
**Assigned to:** {{ $booking->employee->name }}
@endif

</x-mail::panel>

<x-mail::button :url="config('app.url')">
Visit Our Website
</x-mail::button>

@if($booking->notes)
> {{ $booking->notes }}
@endif

We look forward to seeing you. If you need to make any changes, please contact us as soon as possible.

Thanks,
{{ config('app.name') }}
</x-mail::message>

