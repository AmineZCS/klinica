<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_settings', function (Blueprint $table) {
            // How many minutes to leave between consecutive appointments (buffer/cleanup time).
            $table->unsignedSmallInteger('session_interval_minutes')->default(10)->after('timezone');
            // Maximum number of simultaneous bookings allowed for the same time slot.
            $table->unsignedTinyInteger('max_sessions_per_slot')->default(1)->after('session_interval_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('clinic_settings', function (Blueprint $table) {
            $table->dropColumn(['session_interval_minutes', 'max_sessions_per_slot']);
        });
    }
};
