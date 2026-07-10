<?php

namespace App\Services;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS via sms.bd or log in dev mode.
     */
    public function send(string $mobile, string $message, $recipient = null): bool
    {
        $devMode = config('services.sms.dev_mode', true);
        $apiKey = config('services.sms.api_key');

        $status = 'failed';
        $gatewayRef = null;
        $gatewayResponse = null;

        if ($devMode) {
            Log::info("SMS (DEV MODE) sent to {$mobile}: {$message}");
            $status = 'sent';
            $gatewayRef = 'DEV-REF-' . uniqid();
            $gatewayResponse = ['mode' => 'dev', 'status' => 'success'];
        } else {
            try {
                // sms.bd API integration (POST https://api.sms.net.bd/sendsms)
                $response = Http::asForm()->post('https://api.sms.net.bd/sendsms', [
                    'api_key' => $apiKey,
                    'msg' => $message,
                    'to' => $mobile,
                ]);

                $responseData = $response->json();
                $gatewayResponse = $responseData;

                if ($response->successful() && isset($responseData['error']) && $responseData['error'] == 0) {
                    $status = 'sent';
                    $gatewayRef = $responseData['request_id'] ?? null;
                } else {
                    $status = 'failed';
                    $gatewayRef = $responseData['request_id'] ?? null;
                    Log::warning("SMS failed to send to {$mobile}: " . json_encode($responseData));
                }
            } catch (\Exception $e) {
                $status = 'failed';
                $gatewayResponse = ['error' => $e->getMessage()];
                Log::error("SMS service exception sending to {$mobile}: " . $e->getMessage());
            }
        }

        // Log the SMS send event
        SmsLog::create([
            'recipient_type' => $recipient ? get_class($recipient) : null,
            'recipient_id' => $recipient ? $recipient->id : null,
            'to_number' => $mobile,
            'message' => $message,
            'status' => $status,
            'gateway_ref' => $gatewayRef,
            'gateway_response' => $gatewayResponse,
            'sent_at' => now(),
        ]);

        return $status === 'sent';
    }
}
