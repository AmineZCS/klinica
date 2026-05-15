<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Patient;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GdprExportController extends Controller
{
    public function patients(Request $request): StreamedResponse
    {
        $this->authorizeExport($request);

        $filename = 'patients-export-'.now()->format('Y-m-d').'.csv';

        return response()->stream(function (): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'First Name', 'Last Name', 'Email', 'Phone',
                'Date of Birth', 'Gender', 'Is Active', 'Created At',
            ]);

            Patient::query()
                ->orderBy('id')
                ->chunk(500, function ($patients) use ($handle): void {
                    foreach ($patients as $patient) {
                        fputcsv($handle, [
                            $patient->id,
                            $patient->first_name,
                            $patient->last_name,
                            $patient->email,
                            $patient->phone,
                            $patient->date_of_birth?->toDateString(),
                            $patient->gender,
                            $patient->is_active ? 'Yes' : 'No',
                            $patient->created_at->toDateTimeString(),
                        ]);
                    }
                });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    public function bookings(Request $request): StreamedResponse
    {
        $this->authorizeExport($request);

        $filename = 'bookings-export-'.now()->format('Y-m-d').'.csv';

        return response()->stream(function (): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'Patient Name', 'Guest Name', 'Guest Email', 'Guest Phone',
                'Employee', 'Service', 'Start', 'End', 'Status',
                'Notes', 'Cancellation Reason', 'Cancelled At', 'Created At',
            ]);

            Booking::query()
                ->with(['patient', 'employee', 'service'])
                ->orderBy('id')
                ->chunk(500, function ($bookings) use ($handle): void {
                    foreach ($bookings as $booking) {
                        fputcsv($handle, [
                            $booking->id,
                            $booking->patient ? $booking->patient->first_name.' '.$booking->patient->last_name : null,
                            $booking->guest_name,
                            $booking->guest_email,
                            $booking->guest_phone,
                            $booking->employee?->name,
                            $booking->service?->name,
                            $booking->start_at->toDateTimeString(),
                            $booking->end_at->toDateTimeString(),
                            $booking->status->value,
                            $booking->notes,
                            $booking->cancellation_reason,
                            $booking->cancelled_at?->toDateTimeString(),
                            $booking->created_at->toDateTimeString(),
                        ]);
                    }
                });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    private function authorizeExport(Request $request): void
    {
        $user = auth('tenant')->user();

        abort_unless(
            $user?->is_super_admin || ($user?->hasPermissionTo('view_clinic_settings', 'tenant') ?? false),
            403,
            'Unauthorized.'
        );
    }
}
