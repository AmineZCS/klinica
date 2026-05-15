<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Enums\BookingStatus;
use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ConfirmBookingAction
{
    public function execute(Booking $booking): Booking
    {
        if ($booking->status === BookingStatus::Cancelled) {
            throw ValidationException::withMessages([
                'booking' => 'A cancelled booking cannot be confirmed.',
            ]);
        }

        $booking->update(['status' => BookingStatus::Confirmed]);

        $booking->refresh()->load(['patient', 'service', 'employee']);

        $recipientEmail = $booking->patient?->email ?? $booking->guest_email;

        if ($recipientEmail) {
            Mail::send(new BookingConfirmedMail($booking));
        }

        return $booking;
    }
}
