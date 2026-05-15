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

class TenantWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $tenantName,
        public readonly string $ownerName,
        public readonly string $planTier,
        public readonly ?CarbonInterface $trialEndsAt,
        public readonly string $dashboardLoginUrl,
        public readonly string $billingPageUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Klinica - your trial starts now',
            tags: ['tenant-welcome'],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.tenant-welcome',
            with: [
                'tenantName' => $this->tenantName,
                'ownerName' => $this->ownerName,
                'planTier' => ucfirst($this->planTier),
                'trialEndsAt' => $this->trialEndsAt,
                'dashboardLoginUrl' => $this->dashboardLoginUrl,
                'billingPageUrl' => $this->billingPageUrl,
            ],
        );
    }
}
