<?php

namespace App\Services;

use App\Models\Beneficiary;
use App\Models\DistributionQueue;
use App\Models\DistributionRound;
use App\Models\MisuseReport;
use App\Models\ModuleSetting;
use App\Models\SupportPolicy;

class DistributionEngineService
{
    /**
     * Calculate and return the Priority Score for a Beneficiary based on the defined rules.
     */
    public function calculatePriorityScore(Beneficiary $beneficiary, float $requestedAmount = 0): array
    {
        $vulnerabilityScore = $this->calculateVulnerabilityScore($beneficiary);
        $categoryWeight = $this->calculateCategoryWeight($beneficiary);
        $waitingTimeScore = $this->calculateWaitingTimeScore($beneficiary);
        $urgencyScore = $this->calculateUrgencyScore($beneficiary);
        $recommendationScore = $this->calculateRecommendationScore($beneficiary);
        
        $repeatReceiptPenalty = $this->calculateRepeatReceiptPenalty($beneficiary);
        $misuseHistoryPenalty = $this->calculateMisusePenalty($beneficiary);

        // Ramadan Priority Bonus
        $isRamadan = ModuleSetting::get('general', 'ramadan_mode_enabled', false);
        $ramadanBonus = $isRamadan ? 15 : 0;

        $totalScore = $vulnerabilityScore + $categoryWeight + $waitingTimeScore +
                      $urgencyScore + $recommendationScore + $ramadanBonus +
                      $repeatReceiptPenalty + $misuseHistoryPenalty;

        // Ensure score stays between 0 and 100
        $totalScore = max(0, min(100, $totalScore));

        return [
            'total_score' => $totalScore,
            'factors' => [
                'vulnerability_score' => $vulnerabilityScore,
                'category_weight' => $categoryWeight,
                'waiting_time_score' => $waitingTimeScore,
                'urgency_score' => $urgencyScore,
                'recommendation_score' => $recommendationScore,
                'ramadan_bonus' => $ramadanBonus,
                'repeat_receipt_penalty' => $repeatReceiptPenalty,
                'misuse_history_penalty' => $misuseHistoryPenalty,
            ]
        ];
    }

    /**
     * Add a beneficiary to a specific distribution round queue.
     */
    public function addToQueue(DistributionRound $round, Beneficiary $beneficiary, float $requestedAmount): DistributionQueue
    {
        $scoreData = $this->calculatePriorityScore($beneficiary, $requestedAmount);

        // check how many times received
        $previouslyReceivedCount = \App\Models\BeneficiarySupportHistory::where('beneficiary_id', $beneficiary->id)->count();
        $lastReceivedDate = \App\Models\BeneficiarySupportHistory::where('beneficiary_id', $beneficiary->id)->max('distribution_date');

        return DistributionQueue::updateOrCreate(
            [
                'beneficiary_id' => $beneficiary->id,
                'distribution_round_id' => $round->id,
            ],
            [
                'requested_amount' => $requestedAmount,
                'minimum_acceptable_amount' => $requestedAmount * 0.5, // Arbitrary 50% for now
                'priority_score' => $scoreData['total_score'],
                'priority_factors' => $scoreData['factors'],
                'vulnerability_score' => $scoreData['factors']['vulnerability_score'],
                'category_weight' => $scoreData['factors']['category_weight'],
                'waiting_time_score' => $scoreData['factors']['waiting_time_score'],
                'urgency_score' => $scoreData['factors']['urgency_score'],
                'recommendation_score' => $scoreData['factors']['recommendation_score'],
                'repeat_receipt_penalty' => $scoreData['factors']['repeat_receipt_penalty'],
                'misuse_history_penalty' => $scoreData['factors']['misuse_history_penalty'],
                'previously_received_count' => $previouslyReceivedCount,
                'last_received_date' => $lastReceivedDate,
                'queue_status' => 'waiting',
                'added_to_queue_at' => now(),
            ]
        );
    }

    // ─── Scoring Helpers ────────────────────────────────────────────────────────

    private function calculateVulnerabilityScore(Beneficiary $beneficiary): float
    {
        // Max 30. Calculate based on income, dependents, disability etc.
        // For implementation, let's assign a base score based on simple logic
        $score = 15;
        if ($beneficiary->marital_status === 'widowed') $score += 10;
        if ($beneficiary->is_disabled ?? false) $score += 10;
        return min(30, $score);
    }

    private function calculateCategoryWeight(Beneficiary $beneficiary): float
    {
        // Max 20. Find category logic. 
        // e.g. Fuqara = 20, Masakin = 15
        return 15; // default
    }

    private function calculateWaitingTimeScore(Beneficiary $beneficiary): float
    {
        // Max 15. Based on how long since registration or last support.
        $daysWaiting = now()->diffInDays($beneficiary->created_at);
        $score = min(15, $daysWaiting / 30); // 1 point per month
        return $score;
    }

    private function calculateUrgencyScore(Beneficiary $beneficiary): float
    {
        // Max 20. If emergency case.
        return 0; // Requires manual flag
    }

    private function calculateRecommendationScore(Beneficiary $beneficiary): float
    {
        // Max 10. If an imam or volunteer highly recommends
        $recommendationExists = \App\Models\ZakatVerification::where('beneficiary_id', $beneficiary->id)
            ->whereIn('verifier_type', ['imam', 'muezzin', 'volunteer'])
            ->where('recommendation', 'approve')
            ->exists();

        return $recommendationExists ? 10 : 0;
    }

    private function calculateRepeatReceiptPenalty(Beneficiary $beneficiary): float
    {
        // Max -15.
        $count = \App\Models\BeneficiarySupportHistory::where('beneficiary_id', $beneficiary->id)->count();
        $penalty = $count * -5;
        return max(-15, $penalty);
    }

    private function calculateMisusePenalty(Beneficiary $beneficiary): float
    {
        // Max -30.
        $hasMisuse = MisuseReport::where('beneficiary_id', $beneficiary->id)
            ->whereIn('status', ['investigating', 'confirmed'])
            ->exists();
            
        return $hasMisuse ? -30 : 0;
    }
}
