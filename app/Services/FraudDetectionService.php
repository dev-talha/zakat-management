<?php

namespace App\Services;

use App\Models\ModuleSetting;
use App\Models\Beneficiary;
use App\Models\FraudRiskScore;
use App\Models\FraudAlert;
use App\Models\DuplicateCandidate;
use App\Models\Blacklist;
use Illuminate\Support\Collection;

/**
 * FraudDetectionService
 *
 * Rule-based fraud detection engine.
 * সব সেটিংস Admin Dashboard থেকে কনফিগার করা যায়।
 * Admin থেকে পুরো মডিউল disable করলে score সর্বদা 0 ফেরত দেয়।
 */
class FraudDetectionService
{
    private const MODULE = 'fraud_guard';

    // ─── Main Entry Point ───────────────────────────────────────────────────────

    /**
     * একটি নতুন আবেদনের জন্য সম্পূর্ণ fraud risk score গণনা করুন
     * এবং FraudRiskScore রেকর্ড সংরক্ষণ করুন।
     */
    public function evaluate(Beneficiary $beneficiary): FraudRiskScore
    {
        // মডিউল বন্ধ থাকলে skip করুন
        if (! ModuleSetting::isEnabled(self::MODULE)) {
            return $this->createScore($beneficiary, 0, [], 'FraudGuard module is disabled.');
        }

        $factors = [];
        $totalScore = 0;

        // ১. NID ডুপ্লিকেট চেক
        if (ModuleSetting::get(self::MODULE, 'dedup_nid_check', true)) {
            $result = $this->checkNidDuplicate($beneficiary);
            if ($result['found']) {
                $factors[] = $result;
                $totalScore += $result['weight'];
            }
        }

        // ২. মোবাইল ডুপ্লিকেট চেক
        if (ModuleSetting::get(self::MODULE, 'dedup_mobile_check', true)) {
            $result = $this->checkMobileDuplicate($beneficiary);
            if ($result['found']) {
                $factors[] = $result;
                $totalScore += $result['weight'];
            }
        }

        // ৩. নামের ফাজি ম্যাচ চেক
        if (ModuleSetting::get(self::MODULE, 'dedup_fuzzy_name_check', true)) {
            $result = $this->checkFuzzyNameMatch($beneficiary);
            if ($result['found']) {
                $factors[] = $result;
                $totalScore += $result['weight'];
            }
        }

        // ৪. একই GPS এলাকায় একাধিক আবেদন চেক
        if (ModuleSetting::get(self::MODULE, 'geo_cluster_check', true)) {
            $result = $this->checkGeoCluster($beneficiary);
            if ($result['found']) {
                $factors[] = $result;
                $totalScore += $result['weight'];
            }
        }

        // ৫. Blacklist চেক
        if (ModuleSetting::get(self::MODULE, 'blacklist_check_enabled', true)) {
            $result = $this->checkBlacklist($beneficiary);
            if ($result['found']) {
                $factors[] = $result;
                $totalScore += $result['weight'];
            }
        }

        // Score 100 এর বেশি হতে পারবে না
        $finalScore = min(100, $totalScore);

        $riskScore = $this->createScore($beneficiary, $finalScore, $factors);

        // High risk হলে alert তৈরি করুন
        $this->maybeCreateAlert($beneficiary, $riskScore);

        // Duplicate candidates রেকর্ড করুন
        $this->recordDuplicateCandidates($beneficiary, $factors);

        return $riskScore;
    }

    // ─── Individual Check Methods ───────────────────────────────────────────────

    private function checkNidDuplicate(Beneficiary $beneficiary): array
    {
        if (empty($beneficiary->identity_no) || $beneficiary->identity_type !== 'nid') {
            return ['found' => false];
        }

        $existing = Beneficiary::where('identity_type', 'nid')
            ->where('identity_no', $beneficiary->identity_no)
            ->where('id', '!=', $beneficiary->id)
            ->where('status', '!=', 'blacklisted')
            ->first();

        if ($existing) {
            return [
                'found'      => true,
                'factor'     => 'duplicate_nid',
                'weight'     => 50,
                'detail_bn'  => "একই NID নম্বর দিয়ে আবেদন #{$existing->application_no} বিদ্যমান।",
                'match_id'   => $existing->id,
                'match_no'   => $existing->application_no,
            ];
        }

        return ['found' => false];
    }

    private function checkMobileDuplicate(Beneficiary $beneficiary): array
    {
        if (empty($beneficiary->mobile)) {
            return ['found' => false];
        }

        $existing = Beneficiary::where('mobile', $beneficiary->mobile)
            ->where('id', '!=', $beneficiary->id)
            ->where('status', '!=', 'blacklisted')
            ->first();

        if ($existing) {
            return [
                'found'      => true,
                'factor'     => 'duplicate_mobile',
                'weight'     => 30,
                'detail_bn'  => "একই মোবাইল নম্বর দিয়ে আবেদন #{$existing->application_no} বিদ্যমান।",
                'match_id'   => $existing->id,
                'match_no'   => $existing->application_no,
            ];
        }

        return ['found' => false];
    }

    private function checkFuzzyNameMatch(Beneficiary $beneficiary): array
    {
        if (empty($beneficiary->primary_person_name)) {
            return ['found' => false];
        }

        // soundex বা levenshtein দিয়ে নামের মিল খোঁজা
        // সহজ প্রথম সংস্করণ: soundex match (DB-level)
        $soundex = soundex($beneficiary->primary_person_name);

        $matches = Beneficiary::whereRaw("SOUNDEX(primary_person_name) = ?", [$soundex])
            ->where('id', '!=', $beneficiary->id)
            ->where('status', '!=', 'blacklisted')
            ->limit(3)
            ->get();

        // levenshtein distance < 3 হলে সন্দেহজনক
        $closeMatches = $matches->filter(function ($m) use ($beneficiary) {
            return levenshtein(
                mb_strtolower($m->primary_person_name),
                mb_strtolower($beneficiary->primary_person_name)
            ) <= 2;
        });

        if ($closeMatches->isNotEmpty()) {
            $matchNos = $closeMatches->pluck('application_no')->join(', ');
            return [
                'found'      => true,
                'factor'     => 'fuzzy_name_match',
                'weight'     => 15,
                'detail_bn'  => "অনুরূপ নামে আবেদন পাওয়া গেছে: #{$matchNos}",
                'match_ids'  => $closeMatches->pluck('id')->toArray(),
            ];
        }

        return ['found' => false];
    }

    private function checkGeoCluster(Beneficiary $beneficiary): array
    {
        $household = $beneficiary->household;
        if (! $household || ! $household->geo_lat || ! $household->geo_lng) {
            return ['found' => false];
        }

        $radiusMeters = (int) ModuleSetting::get(self::MODULE, 'dedup_geo_radius_meters', 50);
        $maxApps      = (int) ModuleSetting::get(self::MODULE, 'dedup_geo_max_applications', 3);

        // Haversine formula approximation using MySQL/SQLite
        $lat  = $household->geo_lat;
        $lng  = $household->geo_lng;
        $degRadius = $radiusMeters / 111320; // approx meters per degree

        $nearbyCount = \App\Models\BeneficiaryHousehold::whereBetween('geo_lat', [$lat - $degRadius, $lat + $degRadius])
            ->whereBetween('geo_lng', [$lng - $degRadius, $lng + $degRadius])
            ->where('beneficiary_id', '!=', $beneficiary->id)
            ->count();

        if ($nearbyCount >= $maxApps) {
            return [
                'found'      => true,
                'factor'     => 'geo_cluster',
                'weight'     => 20,
                'detail_bn'  => "একই GPS এলাকায় ({$radiusMeters} মিটার) আরো {$nearbyCount}টি আবেদন রয়েছে।",
                'nearby_count' => $nearbyCount,
            ];
        }

        return ['found' => false];
    }

    private function checkBlacklist(Beneficiary $beneficiary): array
    {
        $query = Blacklist::where('is_active', true)
            ->where(function ($q) use ($beneficiary) {
                if ($beneficiary->identity_no) {
                    $q->orWhere(fn($sq) => $sq
                        ->where('identity_type', $beneficiary->identity_type)
                        ->where('identity_no', $beneficiary->identity_no));
                }
                if ($beneficiary->mobile) {
                    $q->orWhere('mobile', $beneficiary->mobile);
                }
            });

        $hit = $query->first();

        if ($hit) {
            return [
                'found'      => true,
                'factor'     => 'blacklisted',
                'weight'     => 100, // instant critical
                'detail_bn'  => "আবেদনকারী কালো তালিকাভুক্ত। কারণ: {$hit->reason}",
                'blacklist_id' => $hit->id,
            ];
        }

        return ['found' => false];
    }

    // ─── Helpers ────────────────────────────────────────────────────────────────

    private function createScore(Beneficiary $beneficiary, int $score, array $factors, string $explanation = ''): FraudRiskScore
    {
        $riskLevel = match (true) {
            $score >= 80 => 'critical',
            $score >= 60 => 'high',
            $score >= 30 => 'medium',
            default      => 'low',
        };

        if (empty($explanation)) {
            $explanation = empty($factors)
                ? 'কোনো ঝুঁকির লক্ষণ পাওয়া যায়নি।'
                : count($factors) . 'টি ঝুঁকির বিষয় চিহ্নিত হয়েছে।';
        }

        return FraudRiskScore::updateOrCreate(
            ['subject_type' => Beneficiary::class, 'subject_id' => $beneficiary->id],
            compact('score', 'risk_level', 'factors', 'explanation')
        );
    }

    private function maybeCreateAlert(Beneficiary $beneficiary, FraudRiskScore $riskScore): void
    {
        $threshold = (int) ModuleSetting::get(self::MODULE, 'risk_score_review_threshold', 60);

        if ($riskScore->score < $threshold) {
            return;
        }

        $severity = $riskScore->risk_level === 'critical' ? 'critical' : 'warning';

        FraudAlert::create([
            'alert_code'       => 'HIGH_RISK_APPLICATION',
            'severity'         => $severity,
            'subject_type'     => Beneficiary::class,
            'subject_id'       => $beneficiary->id,
            'description_bn'   => "আবেদন #{$beneficiary->application_no}: রিস্ক স্কোর {$riskScore->score}/100 ({$riskScore->risk_level})",
            'evidence'         => $riskScore->factors,
        ]);
    }

    private function recordDuplicateCandidates(Beneficiary $beneficiary, array $factors): void
    {
        foreach ($factors as $factor) {
            if (isset($factor['match_id'])) {
                DuplicateCandidate::firstOrCreate(
                    [
                        'primary_beneficiary_id'   => min($beneficiary->id, $factor['match_id']),
                        'duplicate_beneficiary_id' => max($beneficiary->id, $factor['match_id']),
                    ],
                    [
                        'match_types'      => [$factor['factor']],
                        'confidence_score' => $factor['weight'],
                        'match_details'    => $factor,
                    ]
                );
            }
        }
    }
}
