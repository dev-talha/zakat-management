<?php

namespace App\Services;

use App\Models\ModuleSetting;
use App\Models\PorichoyVerification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * PorichoyService — Bangladesh National Identity Verification
 *
 * পরিচয় (Porichoy) API ব্যবহার করে NID নম্বর দিয়ে ব্যক্তির
 * নাম, জন্ম তারিখ ও ছবি যাচাই করে।
 *
 * Default: DISABLED — Admin থেকে enable করতে হবে।
 * API credentials Admin Settings থেকে কনফিগার করা হয়।
 */
class PorichoyService
{
    private const MODULE  = 'porichoy';
    private const API_URL = 'https://api.porichoy.gov.bd/v1';

    // ─── Main Verification ──────────────────────────────────────────────────────

    /**
     * NID ও DOB দিয়ে পরিচয় API যাচাই করুন।
     * PorichoyVerification রেকর্ড সংরক্ষণ করে।
     *
     * @param object      $model     Beneficiary বা Donor
     * @param string      $nidNo     NID নম্বর
     * @param string|null $dob       জন্ম তারিখ (YYYY-MM-DD)
     * @param string|null $name      নাম (cross-check এর জন্য)
     */
    public function verify(object $model, string $nidNo, ?string $dob = null, ?string $name = null): PorichoyVerification
    {
        // মডিউল বন্ধ হলে skip করুন
        if (! ModuleSetting::isEnabled(self::MODULE)) {
            return $this->createRecord($model, $nidNo, $dob, 'skipped', [], 'Porichoy module is disabled.');
        }

        $apiKey = ModuleSetting::get(self::MODULE, 'api_key', '');
        if (empty($apiKey)) {
            return $this->createRecord($model, $nidNo, $dob, 'error', [], 'API key not configured.');
        }

        try {
            $response = Http::withHeaders([
                'x-api-key'    => $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post(self::API_URL . '/verify/nid', [
                'national_id' => $nidNo,
                'dob'         => $dob,
            ]);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? '') === 'found') {
                $matchedName = $body['data']['en']['name'] ?? null;
                $matchedDob  = $body['data']['dob'] ?? null;

                // নামের মিল যাচাই (যদি দেওয়া হয়)
                $nameOk = $name
                    ? $this->compareNames($name, $matchedName)
                    : true;

                $status = ($nameOk) ? 'matched' : 'mismatch';

                return $this->createRecord($model, $nidNo, $dob, $status, [
                    'matched_name'   => $matchedName,
                    'matched_dob'    => $matchedDob,
                    'name_matched'   => $nameOk,
                    'request_id'     => $body['request_id'] ?? null,
                ], null, $body['request_id'] ?? null, $matchedName, $matchedDob);
            }

            if ($response->status() === 404) {
                return $this->createRecord($model, $nidNo, $dob, 'not_found', $body);
            }

            return $this->createRecord($model, $nidNo, $dob, 'error', $body);

        } catch (\Throwable $e) {
            Log::error('[Porichoy] Exception', ['nid' => substr($nidNo, 0, 6) . '****', 'error' => $e->getMessage()]);
            return $this->createRecord($model, $nidNo, $dob, 'error', [], $e->getMessage());
        }
    }

    // ─── Status Helpers ─────────────────────────────────────────────────────────

    public function isModuleEnabled(): bool
    {
        return ModuleSetting::isEnabled(self::MODULE);
    }

    public function getLatestVerification(object $model): ?PorichoyVerification
    {
        return PorichoyVerification::where('verifiable_type', get_class($model))
            ->where('verifiable_id', $model->id)
            ->latest()
            ->first();
    }

    // ─── Private Helpers ────────────────────────────────────────────────────────

    private function createRecord(
        object $model,
        string $nidNo,
        ?string $dob,
        string $status,
        array $apiResponse = [],
        ?string $errorMessage = null,
        ?string $requestId = null,
        ?string $matchedName = null,
        ?string $matchedDob = null
    ): PorichoyVerification {
        // NID নম্বর DB তে mask করে সংরক্ষণ (শুধু শেষ ৪ সংখ্যা দৃশ্যমান)
        return PorichoyVerification::create([
            'verifiable_type'  => get_class($model),
            'verifiable_id'    => $model->id,
            'nid_no'           => substr($nidNo, 0, -4) . '****', // Partial mask
            'dob'              => $dob,
            'status'           => $status,
            'api_response'     => $errorMessage ? ['error' => $errorMessage] : $this->sanitize($apiResponse),
            'matched_name'     => $matchedName,
            'matched_dob'      => $matchedDob,
            'api_request_id'   => $requestId,
            'verified_at'      => in_array($status, ['matched', 'mismatch']) ? now() : null,
        ]);
    }

    private function compareNames(string $submitted, ?string $verified): bool
    {
        if (! $verified) return false;
        // Levenshtein distance: max 20% of name length
        $threshold = (int) ceil(mb_strlen($submitted) * 0.2);
        return levenshtein(
            mb_strtolower(trim($submitted)),
            mb_strtolower(trim($verified))
        ) <= $threshold;
    }

    private function sanitize(array $data): array
    {
        // Remove any fields that could expose raw PII in logs
        $safe = $data;
        unset($safe['image'], $safe['photo'], $safe['face_image']);
        return $safe;
    }
}
