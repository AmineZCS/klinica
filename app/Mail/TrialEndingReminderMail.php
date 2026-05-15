<?php

declare(strict_types=1);

namespace App\Mail;

use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialEndingReminderMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $tenantName,
        public readonly string $ownerName,
        public readonly int $daysLeft,
        public readonly ?CarbonInterface $trialEndsAt,
        public readonly string $billingPageUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Klinica trial is ending soon',
            tags: ['trial-ending-reminder'],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.trial-ending-reminder',
            with: [
                'tenantName' => $this->tenantName,
                'ownerName' => $this->ownerName,
                'daysLeft' => $this->daysLeft,
                'trialEndsAt' => $this->trialEndsAt,
                'billingPageUrl' => $this->billingPageUrl,
            ],
        );
    }
}
