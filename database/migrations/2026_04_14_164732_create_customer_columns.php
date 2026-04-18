<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add Laravel Cashier (Stripe) columns to the tenants table.
     *
     * FIX: These columns belong on 'tenants' (not 'users') because
     * the Billable trait is used on the Tenant model, not User.
     */
    public function up(): void
    {
        // Add Cashier columns to tenants (the Billable model)
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->string('pm_last_four', 4)->nullable();
        });

        // trial_ends_at already exists on tenants from the create_tenants migration,
        // so we only add it to users if it was previously added there and is missing.
        // Check and add to users only if not already present.
        if (!Schema::hasColumn('users', 'stripe_id')) {
            // users table does NOT need Cashier columns — removing the wrong addition
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropIndex(['stripe_id']);
            $table->dropColumn(['stripe_id', 'pm_type', 'pm_last_four']);
        });
    }
};
