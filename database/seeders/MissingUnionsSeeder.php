<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GeographicArea;

/**
 * Fills in unions for the handful of newly-created upazilas that the
 * base geocode dataset predates (Eidgaon, Guimara, Dasar, Naldanga,
 * Madhyanagar). Union lists verified against Wikipedia.
 *
 * Idempotent — skips any upazila that already has union children.
 */
class MissingUnionsSeeder extends Seeder
{
    /**
     * upazila name_en (+ district for disambiguation) => [ [en, bn], ... ]
     */
    private array $data = [
        'Eidgaon|Coxsbazar' => [
            ['Eidgaon', 'ঈদগাঁও'], ['Islamabad', 'ইসলামাবাদ'], ['Islampur', 'ইসলামপুর'],
            ['Jalalabad', 'জালালাবাদ'], ['Pokkhali', 'পোকখালী'],
        ],
        'Guimara|Khagrachhari' => [
            ['Guimara', 'গুইমারা'], ['Hafchhari', 'হাফছড়ি'], ['Sindukchhari', 'সিন্দুকছড়ি'],
        ],
        'Dasar|Madaripur' => [
            ['Dasar', 'দাসার'], ['Gopalpur', 'গোপালপুর'], ['Kazibakai', 'কাজীবাকাই'],
            ['Baligram', 'বালিগ্রাম'], ['Nabagram', 'নবগ্রাম'],
        ],
        'Naldanga|Natore' => [
            ['Bipra Belgharia', 'বিপ্রবেলঘড়িয়া'], ['Brahmapur', 'ব্রহ্মপুর'], ['Khajuria', 'খাজুরিয়া'],
            ['Madhnagar', 'মাধনগর'], ['Piprul', 'পিপরুল'],
        ],
        'Madhyanagar|Sunamganj' => [
            ['Madhyanagar', 'মধ্যনগর'], ['Banshikunda Uttar', 'বংশীকুন্ডা উত্তর'],
            ['Banshikunda Dakkhin', 'বংশীকুন্ডা দক্ষিণ'], ['Chamradani', 'চামরদানী'],
        ],
    ];

    public function run(): void
    {
        $now = now();
        $added = 0;

        foreach ($this->data as $key => $unions) {
            [$upazilaName, $districtName] = explode('|', $key);

            $upazila = GeographicArea::where('level', 'upazila')
                ->where('name_en', $upazilaName)
                ->whereHas('parent', fn ($q) => $q->where('name_en', $districtName))
                ->first();

            if (! $upazila) {
                $this->command?->warn("Upazila not found: {$upazilaName} ({$districtName})");
                continue;
            }

            // Skip if it already has unions.
            if ($upazila->children()->where('level', 'union')->exists()) {
                continue;
            }

            $rows = [];
            foreach ($unions as [$en, $bn]) {
                $rows[] = [
                    'parent_id' => $upazila->id, 'level' => 'union',
                    'name_en' => $en, 'name_bn' => $bn, 'area_type' => 'rural',
                    'poverty_index' => 0, 'population_estimate' => 0, 'is_active' => 1,
                    'created_at' => $now, 'updated_at' => $now,
                ];
            }
            DB::table('geographic_areas')->insert($rows);
            $added += count($rows);
        }

        $this->command?->info("Missing unions added: {$added}.");
    }
}
