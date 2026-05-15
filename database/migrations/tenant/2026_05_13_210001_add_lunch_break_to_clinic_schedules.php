<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_schedules', function (Blueprint $table) {
            // Optional lunch / mid-day break per day.
            $table->boolean('has_lunch_break')->default(false)->after('is_open');
            $table->time('lunch_break_from')->nullable()->after('has_lunch_break');
            $table->time('lunch_break_to')->nullable()->after('lunch_break_from');
        });
    }

    public function down(): void
    {
        Schema::table('clinic_schedules', function (Blueprint $table) {
            $table->dropColumn(['has_lunch_break', 'lunch_break_from', 'lunch_break_to']);
        });
    }
};
