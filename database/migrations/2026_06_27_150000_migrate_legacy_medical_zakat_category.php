<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * "Medical" is no longer a Zakat category. Any beneficiary previously stored
 * with zakat_category='medical' is remapped to a valid category ('faqir')
 * while the medical context is preserved as the assistance_reason inside
 * category_specific_data_json (backward compatibility, no data loss).
 */
return new class extends Migration
{
    public function up(): void
    {
        $rows = DB::table('beneficiaries')->where('zakat_category', 'medical')->get(['id', 'category_specific_data_json']);

        foreach ($rows as $row) {
            $data = json_decode($row->category_specific_data_json ?? '{}', true) ?: [];
            $data['assistance_reason'] = 'medical';
            $data['legacy_category'] = 'medical';

            DB::table('beneficiaries')->where('id', $row->id)->update([
                'zakat_category' => 'faqir',
                'category_specific_data_json' => json_encode($data),
            ]);
        }
    }

    public function down(): void
    {
        // Restore rows that were migrated from the legacy 'medical' category.
        $rows = DB::table('beneficiaries')
            ->where('category_specific_data_json', 'like', '%"legacy_category":"medical"%')
            ->get(['id']);

        foreach ($rows as $row) {
            DB::table('beneficiaries')->where('id', $row->id)->update(['zakat_category' => 'medical']);
        }
    }
};
