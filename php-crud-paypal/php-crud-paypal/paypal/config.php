<?php
/**
 * PayPal Sandbox REST API credentials.
 * Get these from https://developer.paypal.com/dashboard/applications/sandbox
 */
define('PAYPAL_CLIENT_ID', 'YOUR_SANDBOX_CLIENT_ID');
define('PAYPAL_CLIENT_SECRET', 'YOUR_SANDBOX_CLIENT_SECRET');
define('PAYPAL_API_BASE', 'https://api-m.sandbox.paypal.com'); // switch to api-m.paypal.com for live

/**
 * Get an OAuth2 access token from PayPal.
 */
function paypalGetAccessToken(): string {
    $ch = curl_init(PAYPAL_API_BASE . '/v1/oauth2/token');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
        CURLOPT_USERPWD        => PAYPAL_CLIENT_ID . ':' . PAYPAL_CLIENT_SECRET,
        CURLOPT_HTTPHEADER     => ['Accept: application/json'],
    ]);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) throw new Exception('PayPal auth cURL error: ' . $err);

    $json = json_decode($response, true);
    if (!isset($json['access_token'])) {
        throw new Exception('PayPal auth failed: ' . $response);
    }
    return $json['access_token'];
}

/**
 * Helper to call any PayPal REST endpoint with a bearer token.
 */
function paypalRequest(string $method, string $path, ?array $body = null): array {
    $token = paypalGetAccessToken();
    $ch = curl_init(PAYPAL_API_BASE . $path);
    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ],
    ];
    if ($body !== null) {
        $opts[CURLOPT_POSTFIELDS] = json_encode($body);
    }
    curl_setopt_array($ch, $opts);
    $response = curl_exec($ch);
    $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['status' => $status, 'body' => json_decode($response, true)];
}
