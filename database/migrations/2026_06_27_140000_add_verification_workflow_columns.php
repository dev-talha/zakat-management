<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Links applications to a geographic area (for union-scoped verification)
 * and records which volunteer is currently working a case.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            if (! Schema::hasColumn('beneficiaries', 'geo_area_id')) {
                $table->foreignId('geo_area_id')->nullable()->after('branch_id')
                    ->constrained('geographic_areas')->nullOnDelete();
            }
        });

        Schema::table('cases', function (Blueprint $table) {
            if (! Schema::hasColumn('cases', 'assigned_volunteer_id')) {
                $table->foreignId('assigned_volunteer_id')->nullable()->after('assigned_agent_id')
                    ->constrained('volunteers')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('geo_area_id');
        });
        Schema::table('cases', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_volunteer_id');
        });
    }
};
