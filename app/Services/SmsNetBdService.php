<?php

namespace App\Services;

use App\Models\ModuleSetting;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SmsNetBdService — sms.net.bd (Alpha Net) SMS Gateway
 *
 * Endpoint: https://api.sms.net.bd/sendsms
 * Admin settings থেকে API key এবং sender_id কনফিগার করা হয়।
 * মডিউল disabled হলে SMS পাঠানো হয় না (log এ রেকর্ড থাকে)।
 */
class SmsNetBdService
{
    private const MODULE  = 'sms_gateway';
    private const API_URL = 'https://api.sms.net.bd/sendsms';

    // ─── Send Methods ───────────────────────────────────────────────────────────

    /**
     * একটি SMS পাঠান। SmsLog রেকর্ড সংরক্ষণ করে।
     *
     * @param string      $to             উপকারভোগীর মোবাইল নম্বর (01XXXXXXXXX)
     * @param string      $message        বাংলা বা ইংরেজি বার্তা
     * @param string|null $templateCode   কোন ধরনের বার্তা (status_update, otp, disbursement)
     * @param object|null $recipient      Morphable model (Beneficiary, User, Donor)
     */
    public function send(
        string $to,
        string $message,
        ?string $templateCode = null,
        ?object $recipient = null
    ): SmsLog {
        // লগ রেকর্ড তৈরি করুন
        $log = SmsLog::create([
            'recipient_type'  => $recipient ? get_class($recipient) : null,
            'recipient_id'    => $recipient?->id,
            'to_number'       => $this->normalizeNumber($to),
            'message'         => $message,
            'template_code'   => $templateCode,
            'sender_id'       => ModuleSetting::get(self::MODULE, 'sender_id', 'ZAKAT'),
            'status'          => 'pending',
        ]);

        // মডিউল বন্ধ থাকলে skip
        if (! ModuleSetting::isEnabled(self::MODULE)) {
            $log->update(['status' => 'failed', 'gateway_response' => ['error' => 'SMS module disabled']]);
            return $log;
        }

        $apiKey = ModuleSetting::get(self::MODULE, 'api_key', '');
        if (empty($apiKey)) {
            $log->update(['status' => 'failed', 'gateway_response' => ['error' => 'API key not configured']]);
            return $log;
        }

        try {
            $response = Http::timeout(10)->post(self::API_URL, [
                'api_key'   => $apiKey,
                'msg'       => $message,
                'to'        => $log->to_number,
                'sender_id' => $log->sender_id,
            ]);

            $body = $response->json();

            if ($response->successful() && isset($body['status']) && $body['status'] == 0) {
                $log->update([
                    'status'           => 'sent',
                    'gateway_ref'      => $body['data']['request_id'] ?? null,
                    'gateway_response' => $this->sanitizeResponse($body),
                    'sent_at'          => now(),
                ]);
            } else {
                $log->update([
                    'status'           => 'failed',
                    'gateway_response' => $this->sanitizeResponse($body),
                ]);
                Log::warning('[SMS] Send failed', ['to' => $log->to_number, 'response' => $body]);
            }
        } catch (\Throwable $e) {
            $log->update([
                'status'           => 'failed',
                'gateway_response' => ['error' => $e->getMessage()],
            ]);
            Log::error('[SMS] Exception', ['to' => $log->to_number, 'error' => $e->getMessage()]);
        }

        return $log;
    }

    // ─── Template-Based Shortcuts ───────────────────────────────────────────────

    /** আবেদনের স্ট্যাটাস আপডেট SMS */
    public function sendStatusUpdate(object $beneficiary, string $status): SmsLog
    {
        $statusBn = match ($status) {
            'pending'      => 'পর্যালোচনার অপেক্ষায়',
            'under_review' => 'যাচাই চলছে',
            'verified'     => 'যাচাই সম্পন্ন',
            'approved'     => 'অনুমোদিত ✓',
            'rejected'     => 'বাতিল - কারণ জানতে অফিসে যোগাযোগ করুন',
            default        => $status,
        };

        $msg = "জাকাত আবেদন #{$beneficiary->application_no} আপনার আবেদনের বর্তমান অবস্থা: {$statusBn}। বিস্তারিত: {$this->getPortalUrl()}";

        return $this->send($beneficiary->mobile, $msg, 'status_update', $beneficiary);
    }

    /** OTP SMS */
    public function sendOtp(string $mobile, string $otp): SmsLog
    {
        $msg = "আপনার জাকাত পোর্টাল OTP: {$otp}। এটি ৫ মিনিট বৈধ। কাউকে জানাবেন না।";
        return $this->send($mobile, $msg, 'otp');
    }

    /** বিতরণ নিশ্চিতকরণ SMS */
    public function sendDisbursementNotice(object $beneficiary, float $amount, string $method): SmsLog
    {
        $amountFormatted = number_format($amount, 2);
        $msg = "জাকাত বিতরণ: আপনার জন্য {$amountFormatted} টাকা {$method} মাধ্যমে পাঠানো হয়েছে। আবেদন: #{$beneficiary->application_no}।";
        return $this->send($beneficiary->mobile, $msg, 'disbursement', $beneficiary);
    }

    // ─── Incoming SMS Parser (ZAKAT STATUS BEN-2026-001234) ─────────────────────

    /**
     * ইনকামিং SMS parse করুন এবং উত্তর পাঠান।
     * sms.net.bd এর inbound webhook থেকে কল হবে।
     */
    public function handleIncomingSms(string $from, string $message): void
    {
        $query = \App\Models\SmsStatusQuery::create([
            'from_number' => $from,
            'raw_message' => $message,
            'received_at' => now(),
        ]);

        $message = mb_strtoupper(trim($message));

        // "ZAKAT STATUS BEN-2026-001234" বা "STATUS BEN-2026-001234"
        if (preg_match('/STATUS\s+(BEN-\d{4}-\d+)/i', $message, $matches)) {
            $applicationNo = strtoupper($matches[1]);
            $query->update(['query_type' => 'status', 'reference_no' => $applicationNo]);

            $beneficiary = \App\Models\Beneficiary::where('application_no', $applicationNo)->first();

            if ($beneficiary) {
                $reply = $this->buildStatusReply($beneficiary);
            } else {
                $reply = "আবেদন নম্বর {$applicationNo} পাওয়া যায়নি। অনুগ্রহ করে নম্বরটি পুনরায় দেখুন।";
            }

            $smsLog = $this->send($from, $reply, 'status_reply_sms');
            $query->update(['was_resolved' => true, 'sms_log_id' => $smsLog->id]);
            return;
        }

        // "HELP" বা "সাহায্য"
        if (str_contains($message, 'HELP') || str_contains($message, 'সাহায্য')) {
            $query->update(['query_type' => 'help']);
            $reply = "জাকাত পোর্টাল: আবেদনের অবস্থা জানতে: ZAKAT STATUS BEN-XXXX-XXXXXX। ওয়েবসাইট: {$this->getPortalUrl()}";
            $this->send($from, $reply, 'help_reply');
            return;
        }

        $query->update(['query_type' => 'unknown']);
    }

    // ─── Private Helpers ────────────────────────────────────────────────────────

    private function buildStatusReply(\App\Models\Beneficiary $beneficiary): string
    {
        $statusBn = match ($beneficiary->status) {
            'pending'      => 'পর্যালোচনার অপেক্ষায়',
            'under_review' => 'যাচাই চলছে',
            'verified'     => 'যাচাই সম্পন্ন, অনুমোদনের অপেক্ষায়',
            'approved'     => 'অনুমোদিত',
            'rejected'     => 'বাতিল',
            'blacklisted'  => 'অযোগ্য ঘোষিত',
            'graduated'    => 'সম্পন্ন',
            default        => $beneficiary->status,
        };

        return "আবেদন #{$beneficiary->application_no} - {$beneficiary->primary_person_name_bn ?? $beneficiary->primary_person_name}: অবস্থা: {$statusBn}। বিস্তারিত: {$this->getPortalUrl()}";
    }

    private function normalizeNumber(string $number): string
    {
        // 01XXXXXXXXX → 8801XXXXXXXXX (Bangladesh format)
        $number = preg_replace('/\D/', '', $number);
        if (strlen($number) === 11 && str_starts_with($number, '01')) {
            $number = '880' . $number;
        }
        return $number;
    }

    private function sanitizeResponse(array $response): array
    {
        // কোনো sensitive তথ্য response এ থাকলে বাদ দিন
        unset($response['api_key'], $response['password']);
        return $response;
    }

    private function getPortalUrl(): string
    {
        return ModuleSetting::get('general', 'portal_url', config('app.url'));
    }
}
