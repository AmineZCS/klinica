<x-mail::message>
# Your Klinica trial is ending soon

Hi {{ $ownerName }},

Your **{{ $tenantName }}** trial has
@if($daysLeft <= 0)
ended.
@elseif($daysLeft === 1)
**1 day** remaining.
@else
**{{ $daysLeft }} days** remaining.
@endif

<x-mail::panel>
@if($trialEndsAt)
Trial end date: **{{ $trialEndsAt->format('M j, Y') }}**
@endif

Keep your bookings, patient data, and team workflows active by choosing a plan now.
</x-mail::panel>

<x-mail::button :url="$billingPageUrl">
Continue with a Plan
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
