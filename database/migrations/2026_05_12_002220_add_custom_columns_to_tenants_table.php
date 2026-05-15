<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            $table->string('name')->nullable()->after('id');
            $table->timestamp('trial_ends_at')->nullable()->after('name');
            $table->string('plan')->default('trial')->after('trial_ends_at');
            $table->timestamp('plan_expires_at')->nullable()->after('plan');
            $table->string('polar_customer_id')->nullable()->after('plan_expires_at');
            $table->string('polar_subscription_id')->nullable()->after('polar_customer_id');
            $table->string('discount_code')->nullable()->after('polar_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            $table->dropColumn([
                'name',
                'trial_ends_at',
                'plan',
                'plan_expires_at',
                'polar_customer_id',
                'polar_subscription_id',
                'discount_code',
            ]);
        });
    }
};
