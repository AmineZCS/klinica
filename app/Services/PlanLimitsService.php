<?php

declare(strict_types=1);

namespace App\Services;

class PlanLimitsService
{
    /** @return array{max_patients: int, max_employees: int, max_monthly_bookings: int, storage_gb: int, email_reminders: bool, audit_logs: bool} */
    public static function starter(): array
    {
        return [
            'max_patients' => 20,
            'max_employees' => 1,
            'max_monthly_bookings' => 30,
            'storage_gb' => 1,
            'email_reminders' => false,
            'audit_logs' => true,
        ];
    }

    /** @return array{max_patients: int, max_employees: int, max_monthly_bookings: int, storage_gb: int, email_reminders: bool, audit_logs: bool} */
    public static function pro(): array
    {
        return [
            'max_patients' => PHP_INT_MAX,
            'max_employees' => PHP_INT_MAX,
            'max_monthly_bookings' => PHP_INT_MAX,
            'storage_gb' => 10,
            'email_reminders' => true,
            'audit_logs' => true,
        ];
    }
}
