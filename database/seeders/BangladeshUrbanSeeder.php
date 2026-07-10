<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds urban local bodies from database/data/bd-urban.json
 * (derived from the official LGED "List of Paurashava / City Corporation"):
 *
 *   • 12 City Corporations  → under their district (upazila-level slot),
 *                             each with generated numbered Wards.
 *   • 328 Pourashavas       → under their district (alongside upazilas).
 *
 * Idempotent — clears previously-seeded city_corporation/pourashava
 * (and city-corp wards) rows first, then rebuilds.
 */
class BangladeshUrbanSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/bd-urban.json');
        if (! is_file($path)) {
            $this->command?->error("Urban data file not found: {$path}");
            return;
        }
        $data = json_decode(file_get_contents($path), true);
        $now = now();

        // Clean previous urban rows (wards of city corporations, then bodies).
        $ccIds = DB::table('geographic_areas')->where('level', 'city_corporation')->pluck('id');
        if ($ccIds->isNotEmpty()) {
            DB::table('geographic_areas')->whereIn('parent_id', $ccIds)->where('level', 'ward')->delete();
        }
        DB::table('geographic_areas')->whereIn('level', ['city_corporation', 'pourashava'])->delete();

        $cc = 0; $wards = 0; $paura = 0;

        // City Corporations + numbered wards.
        foreach ($data['city_corporations'] as $c) {
            if (empty($c['district_id'])) {
                continue;
            }
            $ccId = DB::table('geographic_areas')->insertGetId([
                'parent_id' => $c['district_id'], 'level' => 'city_corporation',
                'name_en' => $c['name'], 'name_bn' => $c['name_bn'], 'area_type' => 'urban',
                'poverty_index' => 0, 'population_estimate' => 0, 'is_active' => 1,
                'created_at' => $now, 'updated_at' => $now,
            ]);
            $cc++;

            $wardRows = [];
            for ($n = 1; $n <= (int) $c['wards']; $n++) {
                $wardRows[] = [
                    'parent_id' => $ccId, 'level' => 'ward',
                    'name_en' => "Ward No. {$n}", 'name_bn' => "ওয়ার্ড নং {$n}", 'area_type' => 'urban',
                    'poverty_index' => 0, 'population_estimate' => 0, 'is_active' => 1,
                    'created_at' => $now, 'updated_at' => $now,
                ];
            }
            foreach (array_chunk($wardRows, 200) as $chunk) {
                DB::table('geographic_areas')->insert($chunk);
            }
            $wards += count($wardRows);
        }

        // Pourashavas (under district, alongside upazilas). Suffixed to
        // disambiguate from an identically-named upazila.
        $pauraRows = [];
        foreach ($data['pourashavas'] as $p) {
            if (empty($p['district_id'])) {
                continue;
            }
            $pauraRows[] = [
                'parent_id' => $p['district_id'], 'level' => 'pourashava',
                'name_en' => $p['name'] . ' Pourashava',
                'name_bn' => $p['name'] . ' পৌরসভা',
                'area_type' => 'urban',
                'poverty_index' => 0, 'population_estimate' => 0, 'is_active' => 1,
                'created_at' => $now, 'updated_at' => $now,
            ];
        }
        foreach (array_chunk($pauraRows, 300) as $chunk) {
            DB::table('geographic_areas')->insert($chunk);
        }
        $paura = count($pauraRows);

        $this->command?->info("Urban seeded: {$cc} city corporations, {$wards} wards, {$paura} pourashavas.");
    }
}
