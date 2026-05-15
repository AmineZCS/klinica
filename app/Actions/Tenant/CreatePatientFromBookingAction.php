<?php

declare(strict_types=1);

namespace App\Actions\Tenant;

use App\Models\Booking;
use App\Models\Patient;
use Illuminate\Validation\ValidationException;

class CreatePatientFromBookingAction
{
    public function execute(Booking $booking): Patient
    {
        if ($booking->patient_id) {
            throw ValidationException::withMessages([
                'patient' => 'This booking already has a linked patient.',
            ]);
        }

        if (! $booking->guest_name && ! $booking->guest_email && ! $booking->guest_phone) {
            throw ValidationException::withMessages([
                'patient' => 'No guest information found on this booking to create a patient from.',
            ]);
        }

        $nameParts = explode(' ', trim((string) $booking->guest_name), 2);
        $firstName = $nameParts[0] ?? 'Unknown';
        $lastName = $nameParts[1] ?? '';

        // Try to find existing patient by email or phone first.
        $existingPatient = null;

        if ($booking->guest_email) {
            $existingPatient = Patient::query()->where('email', $booking->guest_email)->first();
        }

        if (! $existingPatient && $booking->guest_phone) {
            $existingPatient = Patient::query()->where('phone', $booking->guest_phone)->first();
        }

        if ($existingPatient) {
            $booking->update(['patient_id' => $existingPatient->id]);

            return $existingPatient;
        }

        $patient = Patient::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $booking->guest_email,
            'phone' => $booking->guest_phone ?? '',
            'is_active' => true,
        ]);

        $booking->update(['patient_id' => $patient->id]);

        return $patient;
    }
}
