<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Enums\BookingStatus;
use App\Mail\BookingCancelledMail;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CancelBookingAction
{
    public function execute(Booking $booking, ?string $reason = null): Booking
    {
        if ($booking->status === BookingStatus::Cancelled) {
            throw ValidationException::withMessages([
                'booking' => __('klinica.booking.already_cancelled'),
            ]);
        }

        $booking->update([
            'status' => BookingStatus::Cancelled,
            'cancellation_reason' => $reason,
            'cancelled_at' => Carbon::now(),
        ]);

        $booking->refresh()->load(['patient', 'service', 'employee']);

        $recipientEmail = $booking->patient?->email ?? $booking->guest_email;

        if ($recipientEmail) {
            Mail::send(new BookingCancelledMail($booking));
        }

        return $booking;
    }
}
