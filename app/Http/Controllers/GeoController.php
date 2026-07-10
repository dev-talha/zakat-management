<?php

namespace App\Http\Controllers;

use App\Models\GeographicArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Read-only endpoints that feed the cascading location picker
 * (Division → District → Upazila → Union). Public — no auth needed
 * for registration forms.
 */
class GeoController extends Controller
{
    /** Top-level divisions. */
    public function divisions()
    {
        $data = Cache::remember('geo:divisions', now()->addDay(), function () {
            return GeographicArea::where('level', 'division')
                ->where('is_active', true)
                ->orderBy('name_en')
                ->get(['id', 'name_en', 'name_bn', 'level'])
                ->toArray();
        });

        return response()->json($data);
    }

    /** Direct children of a given area (district under division, etc.). */
    public function children(GeographicArea $area)
    {
        $data = Cache::remember("geo:children:{$area->id}", now()->addDay(), function () use ($area) {
            $query = $area->children()->where('is_active', true);

            // Wards are named "Ward No. N" — sort numerically, not lexically.
            if ($area->level === 'city_corporation') {
                $query->orderByRaw('LENGTH(name_en), name_en');
            } else {
                $query->orderBy('name_en');
            }

            return $query->get(['id', 'name_en', 'name_bn', 'level', 'parent_id'])->toArray();
        });

        return response()->json($data);
    }
}
