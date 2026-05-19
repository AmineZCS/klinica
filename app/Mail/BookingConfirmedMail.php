<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Booking $booking,
        string $locale = 'en',
        public readonly string $tenantUrl = '',
    ) {
        $this->locale($locale);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('klinica.email.booking_confirmed_subject', ['service' => $this->booking->service?->name ?? 'Klinica']),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.booking-confirmed',
            with: [
                'booking' => $this->booking,
                'patientName' => $this->booking->patient
                    ? "{$this->booking->patient->first_name} {$this->booking->patient->last_name}"
                    : ($this->booking->guest_name ?? 'Guest'),
                'tenantUrl' => $this->tenantUrl ?: config('app.url'),
            ],
        );
    }
}
