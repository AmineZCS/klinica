<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BookingStatus;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Booking extends Model implements Eventable
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'employee_id',
        'service_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'start_at',
        'end_at',
        'status',
        'notes',
        'cancellation_reason',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => BookingStatus::class,
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            BookingStatus::Cancelled->value,
            BookingStatus::NoShow->value,
        ]);
    }

    public function toCalendarEvent(): CalendarEvent
    {
        $statusColors = [
            BookingStatus::Pending->value => '#F59E0B',
            BookingStatus::Confirmed->value => '#2F8082',
            BookingStatus::Completed->value => '#8DAA91',
            BookingStatus::Cancelled->value => '#CC4E4E',
            BookingStatus::NoShow->value => '#6B7280',
        ];

        $patientName = $this->patient
            ? "{$this->patient->first_name} {$this->patient->last_name}"
            : ($this->guest_name ?? 'Guest');

        $title = $patientName.' — '.($this->service?->name ?? '');
        $backgroundColor = $statusColors[$this->status->value] ?? '#2F8082';
        $endAt = $this->end_at ?? $this->start_at->addHour();

        return CalendarEvent::make($this)
            ->title($title)
            ->start($this->start_at)
            ->end($endAt)
            ->backgroundColor($backgroundColor)
            ->textColor('#ffffff')
            ->resourceId($this->employee_id ?? 0);
    }
}
