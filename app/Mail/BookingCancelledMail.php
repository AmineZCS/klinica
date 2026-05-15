<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCancelledMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly Booking $booking) {}

    public function envelope(): Envelope
    {
        $recipientName = $this->booking->patient
            ? "{$this->booking->patient->first_name} {$this->booking->patient->last_name}"
            : ($this->booking->guest_name ?? 'Guest');

        $recipientEmail = $this->booking->patient?->email ?? $this->booking->guest_email;

        return new Envelope(
            subject: 'Your booking has been cancelled — '.($this->booking->service?->name ?? 'Klinica'),
            to: $recipientEmail ? [new Address($recipientEmail, $recipientName)] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.booking-cancelled',
            with: [
                'booking' => $this->booking,
                'patientName' => $this->booking->patient
                    ? "{$this->booking->patient->first_name} {$this->booking->patient->last_name}"
                    : ($this->booking->guest_name ?? 'Guest'),
            ],
        );
    }
}
