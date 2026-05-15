<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clinic_settings', function (Blueprint $table): void {
            $table->string('currency', 10)->default('DA');
        });
    }

    public function down(): void
    {
        Schema::table('clinic_settings', function (Blueprint $table): void {
            $table->dropColumn('currency');
        });
    }
};
