<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Altering enum requires raw SQL or dropping/re-creating the column, raw SQL is usually safest for this specific change in MySQL
        DB::statement("ALTER TABLE users MODIFY COLUMN user_type ENUM('staff', 'donor', 'beneficiary', 'agent', 'volunteer', 'org_admin') DEFAULT 'staff'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN user_type ENUM('staff', 'donor', 'beneficiary', 'agent') DEFAULT 'staff'");
    }
};
