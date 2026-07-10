<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Extends geographic_areas.level to support urban local bodies:
 * city_corporation (under district, ~ upazila level) and
 * pourashava (municipality, under district alongside upazilas).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE geographic_areas MODIFY level
            ENUM('division','district','upazila','city_corporation','pourashava','union','ward','village')
            NOT NULL DEFAULT 'village'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE geographic_areas MODIFY level
            ENUM('division','district','upazila','union','ward','village')
            NOT NULL DEFAULT 'village'");
    }
};
