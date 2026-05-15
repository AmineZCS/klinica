<x-mail::message>
# Your booking has been rescheduled

Hi **{{ $patientName }}**,

Your booking has been updated to a new date and time. Here are the new details:

<x-mail::panel>

**Service:** {{ $booking->service?->name ?? 'N/A' }}

**New Date:** {{ $booking->start_at->format('l, F j, Y') }}

**New Time:** {{ $booking->start_at->format('g:i A') }} – {{ $booking->end_at?->format('g:i A') }}

@if($booking->employee)
**Assigned to:** {{ $booking->employee->name }}
@endif

</x-mail::panel>

<x-mail::button :url="config('app.url')">
Visit Our Website
</x-mail::button>

**Previous appointment was:** {{ $previousStartAt->format('l, F j, Y') }} at {{ $previousStartAt->format('g:i A') }}

If you did not expect this change or have questions, please contact us.

Thanks,
{{ config('app.name') }}
</x-mail::message>

