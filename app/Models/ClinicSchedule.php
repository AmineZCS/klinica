<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicSchedule extends Model
{
    protected $fillable = [
        'day_of_week',
        'open_time',
        'close_time',
        'is_open',
        'has_lunch_break',
        'lunch_break_from',
        'lunch_break_to',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'is_open' => 'boolean',
            'has_lunch_break' => 'boolean',
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function dayNames(): array
    {
        return [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];
    }

    public function dayName(): string
    {
        return self::dayNames()[$this->day_of_week] ?? 'Unknown';
    }

    /**
     * Seed the default 7-day schedule (Mon–Fri open, Sat–Sun closed).
     */
    public static function seedDefaults(): void
    {
        $defaults = [
            ['day_of_week' => 0, 'open_time' => '09:00', 'close_time' => '17:00', 'is_open' => false, 'has_lunch_break' => false, 'lunch_break_from' => null, 'lunch_break_to' => null], // Sun
            ['day_of_week' => 1, 'open_time' => '09:00', 'close_time' => '17:00', 'is_open' => true, 'has_lunch_break' => false, 'lunch_break_from' => null, 'lunch_break_to' => null],  // Mon
            ['day_of_week' => 2, 'open_time' => '09:00', 'close_time' => '17:00', 'is_open' => true, 'has_lunch_break' => false, 'lunch_break_from' => null, 'lunch_break_to' => null],  // Tue
            ['day_of_week' => 3, 'open_time' => '09:00', 'close_time' => '17:00', 'is_open' => true, 'has_lunch_break' => false, 'lunch_break_from' => null, 'lunch_break_to' => null],  // Wed
            ['day_of_week' => 4, 'open_time' => '09:00', 'close_time' => '17:00', 'is_open' => true, 'has_lunch_break' => false, 'lunch_break_from' => null, 'lunch_break_to' => null],  // Thu
            ['day_of_week' => 5, 'open_time' => '09:00', 'close_time' => '17:00', 'is_open' => true, 'has_lunch_break' => false, 'lunch_break_from' => null, 'lunch_break_to' => null],  // Fri
            ['day_of_week' => 6, 'open_time' => '09:00', 'close_time' => '17:00', 'is_open' => false, 'has_lunch_break' => false, 'lunch_break_from' => null, 'lunch_break_to' => null], // Sat
        ];

        foreach ($defaults as $row) {
            static::firstOrCreate(['day_of_week' => $row['day_of_week']], $row);
        }
    }
}
