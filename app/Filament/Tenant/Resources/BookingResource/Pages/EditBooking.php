<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources\BookingResource\Pages;

use App\Filament\Tenant\Resources\BookingResource;
use App\Mail\BookingRescheduledMail;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    private ?Carbon $previousStartAt = null;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->previousStartAt = $this->record->start_at
            ? Carbon::parse($this->record->start_at)
            : null;

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record->fresh()->load(['patient', 'service', 'employee']);

        $newStartAt = $record->start_at;
        $previousStartAt = $this->previousStartAt;

        $wasRescheduled = $previousStartAt
            && $newStartAt
            && ! $newStartAt->equalTo($previousStartAt);

        if ($wasRescheduled) {
            $recipientEmail = $record->patient?->email ?? $record->guest_email;

            if ($recipientEmail) {
                Mail::send(new BookingRescheduledMail($record, $previousStartAt, app()->getLocale(), tenancy()->tenant?->tenantBaseUrl() ?? ''));
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
