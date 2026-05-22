<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->timestamp('reminder_sent_at')->nullable()->after('cancelled_at');
            $table->index(['start_at', 'reminder_sent_at', 'status'], 'bookings_reminder_index');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropIndex('bookings_reminder_index');
            $table->dropColumn('reminder_sent_at');
        });
    }
};
