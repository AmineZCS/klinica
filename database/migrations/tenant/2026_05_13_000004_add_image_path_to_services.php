<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Stores the relative path on the `public` disk (e.g. services/image.png).
            // Recommended upload aspect ratio: 3:2 (e.g. 900×600 px).
            $table->string('image_path')->nullable()->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
