<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinic_schedules', function (Blueprint $table) {
            $table->id();
            // 0 = Sunday … 6 = Saturday (matches Carbon::dayOfWeek)
            $table->unsignedTinyInteger('day_of_week');
            $table->time('open_time')->default('09:00:00');
            $table->time('close_time')->default('17:00:00');
            $table->boolean('is_open')->default(true);
            $table->timestamps();

            $table->unique('day_of_week');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_schedules');
    }
};
