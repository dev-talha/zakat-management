<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Heals donor accounts left without a donor profile by the earlier
 * "Anonymous Donor" bug (user created, then Donor insert failed on the
 * donor_type enum). Creates a minimal valid donor profile so these
 * accounts work again. No users are deleted.
 */
return new class extends Migration
{
    public function up(): void
    {
        $orphans = DB::table('users')
            ->where('user_type', 'donor')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))->from('donors')->whereColumn('donors.user_id', 'users.id');
            })
            ->get(['id', 'name']);

        $now = now();
        foreach ($orphans as $u) {
            DB::table('donors')->insert([
                'user_id'           => $u->id,
                'donor_type'        => 'individual',
                'display_name'      => $u->name ?: 'Donor',
                'anonymous_default' => 1, // most were attempting an anonymous registration
                'kyc_status'        => 'pending',
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
        }
    }

    public function down(): void
    {
        // No safe automatic rollback (cannot distinguish healed rows). No-op.
    }
};
