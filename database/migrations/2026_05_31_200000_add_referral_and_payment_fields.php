<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Add referral_code to organizations
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('referral_code', 12)->nullable()->unique()->after('org_code');
            $table->decimal('total_collected_via_referral', 12, 2)->default(0)->after('referral_code');
            $table->unsignedInteger('total_donors_via_referral')->default(0)->after('total_collected_via_referral');
        });

        // Add referral_code to volunteers
        Schema::table('volunteers', function (Blueprint $table) {
            $table->string('referral_code', 12)->nullable()->unique()->after('volunteer_code');
            $table->decimal('total_collected_via_referral', 12, 2)->default(0)->after('referral_code');
            $table->unsignedInteger('total_donors_via_referral')->default(0)->after('total_collected_via_referral');
        });

        // Extend collections table for online payment & referral tracking
        Schema::table('collections', function (Blueprint $table) {
            $table->string('referral_code', 12)->nullable()->after('notes')->index();
            $table->string('referral_type', 20)->nullable()->after('referral_code'); // 'org' or 'volunteer'
            $table->string('payment_gateway', 30)->nullable()->after('referral_type'); // bkash, nagad, sslcommerz, bank
            $table->string('gateway_transaction_id', 100)->nullable()->after('payment_gateway');
            $table->string('payment_status', 20)->default('pending')->after('gateway_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'total_collected_via_referral', 'total_donors_via_referral']);
        });
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'total_collected_via_referral', 'total_donors_via_referral']);
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'referral_type', 'payment_gateway', 'gateway_transaction_id', 'payment_status']);
        });
    }
};
