<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ClinicSetting extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;

    protected $fillable = [
        'clinic_name',
        'tagline',
        'hero_title',
        'hero_subtitle',
        'hero_cta_text',
        'primary_color',
        'secondary_color',
        'contact_phone',
        'contact_email',
        'contact_address',
        'online_booking_enabled',
        'locale',
        'timezone',
        'session_interval_minutes',
        'max_sessions_per_slot',
        'currency',
        'onboarding_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'online_booking_enabled' => 'boolean',
            'onboarding_completed_at' => 'datetime',
            'session_interval_minutes' => 'integer',
            'max_sessions_per_slot' => 'integer',
            'currency' => 'string',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public static function current(): self
    {
        return static::firstOrCreate([]);
    }

    public function getTimezone(): string
    {
        return $this->timezone ?: 'UTC';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }
}
