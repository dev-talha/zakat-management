<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$baseUrl = 'https://tokenized.sandbox.bka.sh/v2';
$username = '01770618567';
$password = 'D7DaC<*E*eG';
$appKey = '0vWQuCRGiUX7EPVjQDr0EUAYtc';
$appSecret = 'jcUNPBgbcqEDedNKdvE4G1cAK7D3hCjmJccNPZZBq96QIxxwAMEx';

$response = Http::withHeaders([
    'username' => $username,
    'password' => $password,
    'app_key' => $appKey,
])->post("{$baseUrl}/tokenized/checkout/token/grant", [
    'app_key' => $appKey,
    'app_secret' => $appSecret,
]);

echo "Status Code: " . $response->status() . "\n";
echo "Response: " . $response->body() . "\n";
