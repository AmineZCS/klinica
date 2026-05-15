<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Actions\Tenant\CreateBookingAction;
use App\Http\Controllers\Controller;
use App\Models\ClinicSetting;
use App\Models\Service;
use App\Services\Booking\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicBookingController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availabilityService,
        private readonly CreateBookingAction $createBooking,
    ) {}

    public function create(Request $request): View
    {
        $services = Service::query()->where('is_active', true)->get();
        $settings = ClinicSetting::current();

        $serviceId = $request->integer('service_id');
        $dateParam = $request->input('date', now()->toDateString());
        $date = Carbon::parse((string) $dateParam)->startOfDay();

        $slots = collect();
        $selectedService = $serviceId ? Service::find($serviceId) : null;

        if ($selectedService) {
            // Return all slots (available + booked) for display; the view marks booked ones as disabled.
            $slots = $this->availabilityService->getSlotsWithStatus($selectedService, $date);
        }

        return view('tenant.booking.create', compact('services', 'settings', 'slots', 'date', 'selectedService'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'employee_id' => ['nullable', 'integer', 'exists:users,id'],
            'start_at' => ['required', 'date'],
            'guest_name' => ['nullable', 'string', 'max:255'],
            'guest_email' => ['nullable', 'email', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:30'],
        ]);

        $this->createBooking->execute($validated);

        return redirect()->route('book.create')->with('success', __('klinica.booking.confirmed'));
    }
}
