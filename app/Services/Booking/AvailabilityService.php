<?php

declare(strict_types=1);

namespace App\Services\Booking;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\ClinicSchedule;
use App\Models\ClinicSetting;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class AvailabilityService
{
    /**
     * Get all slot start times for a given date, each tagged with its availability status.
     *
     * @return Collection<int, array{time: Carbon, available: bool, count: int, capacity: int}>
     */
    public function getSlotsWithStatus(
        Service $service,
        Carbon $date,
        ?int $employeeId = null,
    ): Collection {
        $schedule = ClinicSchedule::where('day_of_week', $date->dayOfWeek)->first();

        if ($schedule === null || ! $schedule->is_open) {
            return collect();
        }

        $settings = ClinicSetting::current();
        $intervalMinutes = $settings->session_interval_minutes ?? 10;
        $maxPerSlot = $settings->max_sessions_per_slot ?? 1;

        $workdayStart = substr((string) $schedule->open_time, 0, 5);
        $workdayEnd = substr((string) $schedule->close_time, 0, 5);

        $lunchFrom = $schedule->has_lunch_break && $schedule->lunch_break_from
            ? Carbon::parse($date->format('Y-m-d').' '.substr((string) $schedule->lunch_break_from, 0, 5))
            : null;
        $lunchTo = $schedule->has_lunch_break && $schedule->lunch_break_to
            ? Carbon::parse($date->format('Y-m-d').' '.substr((string) $schedule->lunch_break_to, 0, 5))
            : null;

        $slotStepMinutes = $service->duration_minutes + $intervalMinutes;
        $slots = collect();

        $cursor = Carbon::parse($date->format('Y-m-d').' '.$workdayStart);
        $end = Carbon::parse($date->format('Y-m-d').' '.$workdayEnd);

        while ($cursor->copy()->addMinutes($service->duration_minutes)->lte($end)) {
            $slotEnd = $cursor->copy()->addMinutes($service->duration_minutes);

            // Skip slots that overlap the lunch break.
            if ($lunchFrom !== null && $lunchTo !== null) {
                if ($cursor->lt($lunchTo) && $slotEnd->gt($lunchFrom)) {
                    $cursor->addMinutes($slotStepMinutes);

                    continue;
                }
            }

            $bookedCount = $this->countOverlappingBookings($employeeId, $cursor, $slotEnd);

            $slots->push([
                'time' => $cursor->copy(),
                'available' => $bookedCount < $maxPerSlot,
                'count' => $bookedCount,
                'capacity' => $maxPerSlot,
            ]);

            $cursor->addMinutes($slotStepMinutes);
        }

        return $slots;
    }

    /**
     * Get only available slot start times (backwards-compatible helper).
     *
     * @return Collection<int, Carbon>
     */
    public function getAvailableSlots(
        ?int $employeeId,
        Service $service,
        Carbon $date,
    ): Collection {
        return $this->getSlotsWithStatus($service, $date, $employeeId)
            ->filter(fn (array $slot): bool => $slot['available'])
            ->map(fn (array $slot): Carbon => $slot['time'])
            ->values();
    }

    /**
     * Count active bookings that overlap the given window (optionally scoped to an employee).
     */
    public function countOverlappingBookings(?int $employeeId, Carbon $startAt, Carbon $endAt): int
    {
        $query = Booking::query()
            ->whereNotIn('status', [BookingStatus::Cancelled->value, BookingStatus::NoShow->value])
            ->where('start_at', '<', $endAt)
            ->where('end_at', '>', $startAt);

        if ($employeeId !== null) {
            $query->where('employee_id', $employeeId);
        }

        return $query->count();
    }

    /** @deprecated Use countOverlappingBookings() instead. */
    public function isSlotAvailable(int $employeeId, Carbon $startAt, Carbon $endAt): bool
    {
        return $this->countOverlappingBookings($employeeId, $startAt, $endAt) === 0;
    }

    public function calculateEndTime(CarbonInterface $startAt, Service $service): Carbon
    {
        return Carbon::instance($startAt)->addMinutes($service->duration_minutes);
    }
}
