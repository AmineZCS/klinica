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

class NewBookingAlertMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Booking $booking,
        public readonly string $dashboardBookingsUrl,
        string $locale = 'en',
    ) {
        $this->locale($locale);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('klinica.email.new_booking_alert_subject', [
                'service' => $this->booking->service?->name ?? 'Booking',
                'date' => $this->booking->start_at->format('d M Y'),
            ]),
        );
    }

    public function content(): Content
    {
        $patientName = $this->booking->patient
            ? "{$this->booking->patient->first_name} {$this->booking->patient->last_name}"
            : ($this->booking->guest_name ?? '—');

        $patientEmail = $this->booking->patient?->email ?? $this->booking->guest_email ?? '—';
        $patientPhone = $this->booking->patient?->phone ?? $this->booking->guest_phone ?? '—';

        return new Content(
            markdown: 'mail.new-booking-alert',
            with: [
                'booking' => $this->booking,
                'patientName' => $patientName,
                'patientEmail' => $patientEmail,
                'patientPhone' => $patientPhone,
                'dashboardBookingsUrl' => $this->dashboardBookingsUrl,
            ],
        );
    }
}
