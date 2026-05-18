<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop unused Polar package tables
        Schema::dropIfExists('webhook_calls');
        Schema::dropIfExists('polar_subscriptions');
        Schema::dropIfExists('polar_orders');
        Schema::dropIfExists('polar_customers');

        // Rename Polar columns to Paddle on the tenants table
        Schema::table('tenants', function (Blueprint $table): void {
            $table->renameColumn('polar_customer_id', 'paddle_customer_id');
            $table->renameColumn('polar_subscription_id', 'paddle_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            $table->renameColumn('paddle_customer_id', 'polar_customer_id');
            $table->renameColumn('paddle_subscription_id', 'polar_subscription_id');
        });
    }
};
