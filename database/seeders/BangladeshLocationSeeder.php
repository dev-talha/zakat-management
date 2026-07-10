<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Imports the FULL Bangladesh geographic hierarchy
 * (Division → District → Upazila → Union) with Bangla names.
 *
 * Source: database/data/bd-geo-full.json
 *   (derived from github.com/nuhil/bangladesh-geocode)
 *   8 divisions · 64 districts · 494 upazilas · 4,540 unions
 *
 * Authoritative rebuild: wipes and rebuilds the tree, then re-points
 * dependent FKs (volunteers.primary_area_id) to a valid area.
 */
class BangladeshLocationSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/bd-geo-full.json');
        if (! is_file($path)) {
            $this->command?->error("Location data file not found: {$path}");
            return;
        }

        $data = json_decode(file_get_contents($path), true);
        if (empty($data['divisions'])) {
            $this->command?->error('No divisions found in geo JSON.');
            return;
        }

        $now = now();

        // Authoritative rebuild.
        Schema::disableForeignKeyConstraints();
        DB::table('geographic_areas')->truncate();
        Schema::enableForeignKeyConstraints();

        $divMap = $distMap = $upMap = [];
        $fallbackAreaId = null;

        DB::transaction(function () use ($data, $now, &$divMap, &$distMap, &$upMap, &$fallbackAreaId) {
            $insert = function (array $attrs) use ($now) {
                return DB::table('geographic_areas')->insertGetId(array_merge([
                    'poverty_index'        => 0,
                    'population_estimate'  => 0,
                    'is_active'            => 1,
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ], $attrs));
            };

            foreach ($data['divisions'] as $d) {
                $divMap[$d['id']] = $insert([
                    'level' => 'division', 'name_en' => $d['name'], 'name_bn' => $d['bn'], 'area_type' => 'urban',
                ]);
            }

            foreach ($data['districts'] as $d) {
                $distMap[$d['id']] = $insert([
                    'parent_id' => $divMap[$d['division_id']] ?? null,
                    'level' => 'district', 'name_en' => $d['name'], 'name_bn' => $d['bn'], 'area_type' => 'semi_urban',
                ]);
                $fallbackAreaId ??= $distMap[$d['id']];
            }

            foreach ($data['upazilas'] as $u) {
                $upMap[$u['id']] = $insert([
                    'parent_id' => $distMap[$u['district_id']] ?? null,
                    'level' => 'upazila', 'name_en' => $u['name'], 'name_bn' => $u['bn'], 'area_type' => 'rural',
                ]);
            }

            // Unions are the bulk (~4,540) — chunked bulk insert for speed.
            $unionRows = [];
            foreach ($data['unions'] as $un) {
                $parent = $upMap[$un['upazila_id']] ?? null;
                if ($parent === null) {
                    continue;
                }
                $unionRows[] = [
                    'parent_id' => $parent, 'level' => 'union',
                    'name_en' => $un['name'], 'name_bn' => $un['bn'], 'area_type' => 'rural',
                    'poverty_index' => 0, 'population_estimate' => 0, 'is_active' => 1,
                    'created_at' => $now, 'updated_at' => $now,
                ];
            }
            foreach (array_chunk($unionRows, 500) as $chunk) {
                DB::table('geographic_areas')->insert($chunk);
            }
        });

        // Re-point dependents that referenced now-removed areas.
        if ($fallbackAreaId && Schema::hasColumn('volunteers', 'primary_area_id')) {
            DB::table('volunteers')->update(['primary_area_id' => $fallbackAreaId]);
        }

        $counts = DB::table('geographic_areas')
            ->select('level', DB::raw('COUNT(*) as c'))->groupBy('level')->pluck('c', 'level');

        $this->command?->info(sprintf(
            'Bangladesh geo imported: %d divisions, %d districts, %d upazilas, %d unions.',
            $counts['division'] ?? 0, $counts['district'] ?? 0, $counts['upazila'] ?? 0, $counts['union'] ?? 0
        ));
    }
}
