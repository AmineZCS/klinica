<x-mail::message>
# Welcome to Klinica, {{ $ownerName }}!

Your clinic **{{ $tenantName }}** is ready and your **{{ $planTier }} free trial** just started.

<x-mail::panel>
@if($trialEndsAt)
Your trial is active until **{{ $trialEndsAt->format('M j, Y') }}**.
@else
Your trial is now active.
@endif

Quick start checklist:
- Sign in to your dashboard
- Configure clinic schedule and services
- Invite team members
</x-mail::panel>

<x-mail::button :url="$dashboardLoginUrl">
Open Clinic Dashboard
</x-mail::button>

You can review your subscription options at any time.

<x-mail::button :url="$billingPageUrl" color="gray">
View Billing
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
