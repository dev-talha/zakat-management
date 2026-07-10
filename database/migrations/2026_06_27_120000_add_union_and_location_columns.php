<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds union + missing location columns so the cascading picker
 * (division → district → upazila → union) can be persisted uniformly
 * across the registration flows.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (! Schema::hasColumn('organizations', 'union_name')) {
                $table->string('union_name')->nullable()->after('upazila');
            }
        });

        Schema::table('donor_addresses', function (Blueprint $table) {
            if (! Schema::hasColumn('donor_addresses', 'union_name')) {
                $table->string('union_name')->nullable()->after('upazila');
            }
        });

        Schema::table('volunteers', function (Blueprint $table) {
            if (! Schema::hasColumn('volunteers', 'division')) {
                $table->string('division')->nullable()->after('address_bn');
            }
            if (! Schema::hasColumn('volunteers', 'district')) {
                $table->string('district')->nullable()->after('division');
            }
            if (! Schema::hasColumn('volunteers', 'upazila')) {
                $table->string('upazila')->nullable()->after('district');
            }
            if (! Schema::hasColumn('volunteers', 'union_name')) {
                $table->string('union_name')->nullable()->after('upazila');
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('union_name');
        });
        Schema::table('donor_addresses', function (Blueprint $table) {
            $table->dropColumn('union_name');
        });
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropColumn(['division', 'district', 'upazila', 'union_name']);
        });
    }
};
