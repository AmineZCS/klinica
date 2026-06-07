<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Enums\BookingStatus;
use App\Mail\NewBookingAlertMail;
use App\Models\Booking;
use App\Models\ClinicSetting;
use App\Models\Patient;
use App\Models\Service;
use App\Services\Booking\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CreateBookingAction
{
    public function __construct(private readonly AvailabilityService $availabilityService) {}

    /**
     * @param  array{
     *     patient_id?: int|null,
     *     employee_id?: int|null,
     *     service_id: int,
     *     guest_name?: string|null,
     *     guest_email?: string|null,
     *     guest_phone?: string|null,
     *     start_at: string,
     *     notes?: string|null,
     * } $data
     */
    public function execute(array $data): Booking
    {
        $service = Service::findOrFail($data['service_id']);
        $startAt = Carbon::parse($data['start_at']);
        $endAt = $this->availabilityService->calculateEndTime($startAt, $service);

        if (! empty($data['employee_id'])) {
            if (! $this->availabilityService->isSlotAvailable((int) $data['employee_id'], $startAt, $endAt)) {
                throw ValidationException::withMessages([
                    'start_at' => __('klinica.booking.slot_unavailable'),
                ]);
            }
        }

        // If patient_id not explicitly given, try to auto-link by email or phone.
        $patientId = $data['patient_id'] ?? null;

        if (! $patientId) {
            $email = $data['guest_email'] ?? null;
            $phone = $data['guest_phone'] ?? null;

            $patient = Patient::query()
                ->where(function ($q) use ($email, $phone): void {
                    if ($email) {
                        $q->where('email', $email);
                    }
                    if ($phone) {
                        $q->orWhere('phone', $phone);
                    }
                })
                ->first();

            if ($patient) {
                $patientId = $patient->id;
            }
        }

        $booking = Booking::create([
            'patient_id' => $patientId,
            'employee_id' => $data['employee_id'] ?? null,
            'service_id' => $service->id,
            'guest_name' => $data['guest_name'] ?? null,
            'guest_email' => $data['guest_email'] ?? null,
            'guest_phone' => $data['guest_phone'] ?? null,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'status' => BookingStatus::Pending,
            'notes' => $data['notes'] ?? null,
        ]);

        $this->notifyTenant($booking);

        return $booking;
    }

    private function notifyTenant(Booking $booking): void
    {
        $tenant = tenancy()->tenant;

        if ($tenant === null) {
            return;
        }

        $ownerEmail = (string) $tenant->owner_email;

        if (! $ownerEmail) {
            return;
        }

        $locale = ClinicSetting::current()->locale ?? 'en';
        $dashboardUrl = $tenant->tenantBaseUrl().'/clinic/bookings';

        Mail::to($ownerEmail)->queue(new NewBookingAlertMail($booking, $dashboardUrl, $locale));
    }
}
