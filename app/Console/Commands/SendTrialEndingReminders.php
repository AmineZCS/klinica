<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\TenantPlan;
use App\Mail\TrialEndingReminderMail;
use App\Models\ClinicSetting;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('trials:send-reminders')]
#[Description('Send reminder emails to tenants whose free trial is close to ending.')]
class SendTrialEndingReminders extends Command
{
    public function handle(): int
    {
        $daysBefore = (int) config('app.trial_reminder_days_before', 3);
        $now = Carbon::now();
        $cutoff = $now->copy()->addDays($daysBefore);

        $tenants = Tenant::query()
            ->where('plan', TenantPlan::Trial->value)
            ->whereNotNull('owner_email')
            ->whereNotNull('trial_ends_at')
            ->whereNull('trial_reminder_sent_at')
            ->whereBetween('trial_ends_at', [$now, $cutoff])
            ->get();

        foreach ($tenants as $tenant) {
            $daysLeft = $tenant->trialDaysRemaining();

            $locale = 'en';

            try {
                tenancy()->initialize($tenant);
                $locale = ClinicSetting::current()->locale ?? 'en';
                tenancy()->end();
            } catch (\Throwable) {
                // Tenant DB unavailable; fall back to English
            }

            Mail::to((string) $tenant->owner_email)->queue(new TrialEndingReminderMail(
                tenantName: $tenant->name ?? 'Your Clinic',
                ownerName: $tenant->owner_name ?? 'there',
                daysLeft: $daysLeft,
                trialEndsAt: $tenant->trial_ends_at,
                billingPageUrl: $tenant->billingPageUrl(),
                locale: $locale,
            ));

            $tenant->forceFill([
                'trial_reminder_sent_at' => Carbon::now(),
            ])->save();
        }

        $this->info("Queued {$tenants->count()} trial reminder email(s).");

        return self::SUCCESS;
    }
}
