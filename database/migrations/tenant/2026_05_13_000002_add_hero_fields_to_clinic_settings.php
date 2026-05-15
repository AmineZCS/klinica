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
            $table->string('hero_title')->nullable()->after('tagline');
            $table->string('hero_subtitle')->nullable()->after('hero_title');
            $table->string('hero_cta_text')->nullable()->default('Book Now')->after('hero_subtitle');
            $table->timestamp('onboarding_completed_at')->nullable()->after('timezone');
        });
    }

    public function down(): void
    {
        Schema::table('clinic_settings', function (Blueprint $table) {
            $table->dropColumn(['hero_title', 'hero_subtitle', 'hero_cta_text', 'onboarding_completed_at']);
        });
    }
};
