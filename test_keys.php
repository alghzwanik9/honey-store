<?php
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

$rawToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiI0YTQzNDFmNy01MTIwLT Q5ZjktYTZkZC04MGVkYzUzODM3ZDkiLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiNjZjZGVkZj RjNDZmY2FlN2ZjYjZhNzMzY2QxYWFjMmQiLCJyb2xlcyI6WyJST0xFX01FUkNIQU5UIl0sImlhdC I6MTc3MjAxNjcyOCwiaXNzIjoiVGFtYXJhIn0.F_MaRDWyegV7LcxgxXhLo1IY063ymN1AmhuIkD -9XVPRMS7L8ErYaar9LUyt5cbOWNo-OfB1WSWwl-CI9DEnifl3MVGX4ig2AavC1wcx59nKPgWAfu 2jHEtXJNZYOFyOJ9eEoHf1Fk42vgy2omxhIY8mXJya-ci1zXVFhMfg8G3aP-DJ0pLkKNA_R1gm7G yiCEMH7Kfk7eYbnXSMVxoxysqmOl9fEuuRr-uESKd43zTWARg0ZoHYl1R0qXfygiWTJtnRd2WuMT4bM6TJWAOCRdbgXiCKmVs4Sd6BZWS8CDbATli1nZhfHMfaBN6bd5DOGEB8rU4vbSpg4kU-n7BmKw';

// Clean up any spaces or newlines from copy-pasting
$token = preg_replace('/\s+/', '', str_replace(["\r", "\n"], '', trim($rawToken)));

$payload = [
    'order_reference_id' => '999',
    'total_amount' => ['amount' => 100, 'currency' => 'SAR'],
    'description' => 'Test Order',
    'country_code' => 'SA',
    'payment_type' => 'PAY_BY_INSTALMENTS',
    'locale' => 'ar_SA',
    'items' => [
        [
            'reference_id' => '1',
            'type' => 'physical',
            'name' => 'Test Item',
            'sku' => 'SKU-1',
            'quantity' => 1,
            'unit_price' => ['amount' => 100, 'currency' => 'SAR'],
            'total_amount' => ['amount' => 100, 'currency' => 'SAR']
        ]
    ],
    'consumer' => [
        'first_name' => 'Test',
        'last_name' => 'User',
        'phone_number' => '0500000000',
        'email' => 'test@example.com'
    ],
    'shipping_address' => [
        'first_name' => 'Test',
        'last_name' => 'User',
        'line1' => 'Riyadh',
        'city' => 'Riyadh',
        'country_code' => 'SA'
    ],
    'merchant_url' => [
        'success' => 'http://example.com/success',
        'failure' => 'http://example.com/failure',
        'cancel' => 'http://example.com/cancel',
        'notification' => 'http://example.com/notification'
    ]
];

$client = new Client();

function testApi($url, $client, $token, $payload) {
    echo "Testing against $url...\n";
    try {
        $response = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ],
            'json' => $payload
        ]);
        echo "SUCCESS: " . $response->getStatusCode() . "\n";
        echo "Response: " . $response->getBody() . "\n\n";
    } catch (\Exception $e) {
        if (method_exists($e, 'getResponse') && $e->getResponse()) {
            echo "FAILED: " . $e->getResponse()->getStatusCode() . "\n";
            echo "Details: " . $e->getResponse()->getBody() . "\n\n";
        } else {
            echo "EXCEPTION: " . $e->getMessage() . "\n\n";
        }
    }
}

testApi('https://api-sandbox.tamara.co/checkout', $client, $token, $payload);
