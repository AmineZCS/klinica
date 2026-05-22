<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('trials:send-reminders')->hourly();
Schedule::command('bookings:send-reminders')->everyFiveMinutes();
Schedule::command('plans:enforce-expired')->hourly();
