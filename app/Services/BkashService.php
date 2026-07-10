<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BkashService
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $appKey;
    protected $appSecret;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('bkash.base_url'), '/');
        $this->username = config('bkash.username');
        $this->password = config('bkash.password');
        $this->appKey = config('bkash.app_key');
        $this->appSecret = config('bkash.app_secret');
    }

    /**
     * Get or generate bKash id_token
     */
    public function grantToken()
    {
        if (Cache::has('bkash_id_token')) {
            return Cache::get('bkash_id_token');
        }

        $response = Http::withHeaders([
            'username' => $this->username,
            'password' => $this->password,
            'app_key' => $this->appKey,
        ])->post("{$this->baseUrl}/tokenized/checkout/token/grant", [
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
        ]);

        if ($response->successful() && isset($response['id_token'])) {
            // Token expires in 3600 seconds (1 hour). We cache it for 55 minutes.
            Cache::put('bkash_id_token', $response['id_token'], now()->addMinutes(55));
            return $response['id_token'];
        }

        throw new \Exception('Failed to generate bKash token: ' . $response->body());
    }

    /**
     * Create a payment intent
     */
    public function createPayment(float $amount, string $invoiceNo)
    {
        $idToken = $this->grantToken();

        $response = Http::withHeaders([
            'Authorization' => $idToken,
            'X-APP-Key' => $this->appKey,
        ])->post("{$this->baseUrl}/tokenized/checkout/create", [
            'mode' => '0011',
            'payerReference' => ' ',
            'callbackURL' => route('payment.bkash.callback'),
            'amount' => $amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $invoiceNo,
        ]);

        if ($response->successful() && isset($response['bkashURL'])) {
            return $response->json();
        }

        throw new \Exception('Failed to create bKash payment: ' . $response->body());
    }

    /**
     * Execute the payment after callback
     */
    public function executePayment(string $paymentId)
    {
        $idToken = $this->grantToken();

        $response = Http::withHeaders([
            'Authorization' => $idToken,
            'X-APP-Key' => $this->appKey,
        ])->post("{$this->baseUrl}/tokenized/checkout/execute", [
            'paymentID' => $paymentId,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to execute bKash payment: ' . $response->body());
    }
}
