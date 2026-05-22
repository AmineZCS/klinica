<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Enums\TenantPlan;
use App\Mail\BookingReminderMail;
use App\Models\Booking;
use App\Models\ClinicSetting;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('bookings:send-reminders')]
#[Description('Send 30-minute appointment reminder emails to patients on Pro tenants.')]
class SendBookingReminders extends Command
{
    public function handle(): int
    {
        $now = Carbon::now();
        $windowStart = $now->copy()->addMinutes(25);
        $windowEnd = $now->copy()->addMinutes(35);

        $tenants = Tenant::query()
            ->where('plan', TenantPlan::Pro->value)
            ->where('is_active', true)
            ->get();

        $sent = 0;

        foreach ($tenants as $tenant) {
            try {
                tenancy()->initialize($tenant);

                $locale = 'en';

                try {
                    $locale = ClinicSetting::current()->locale ?? 'en';
                } catch (\Throwable) {
                }

                $bookings = Booking::query()
                    ->with(['patient', 'service', 'employee'])
                    ->where('status', BookingStatus::Confirmed->value)
                    ->whereNull('reminder_sent_at')
                    ->whereBetween('start_at', [$windowStart, $windowEnd])
                    ->get();

                foreach ($bookings as $booking) {
                    $recipientEmail = $booking->patient?->email ?? $booking->guest_email;

                    if (! $recipientEmail) {
                        continue;
                    }

                    Mail::to($recipientEmail)->queue(new BookingReminderMail(
                        booking: $booking,
                        locale: $locale,
                        tenantUrl: $tenant->tenantBaseUrl(),
                    ));

                    $booking->update(['reminder_sent_at' => $now]);
                    $sent++;
                }

                tenancy()->end();
            } catch (\Throwable $e) {
                $this->warn("Tenant {$tenant->id}: {$e->getMessage()}");
                tenancy()->end();
            }
        }

        $this->info("Queued {$sent} booking reminder email(s).");

        return self::SUCCESS;
    }
}
