<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

$merchantToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiI2YWM1NDVhYS0zOWIzLTRhOTMtOTZhYS0wZTA0NTQ1ZDI1YjEiLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiM2Q4MTc4MmYtZTc3Yy00ODRmLWJmZjUtNTQyZDBmYjYyYWI4Iiwicm9sZXMiOlsiUk9MRV9NRVJDSEFOVCJdLCJpc010bHMiOmZhbHNlLCJpYXQiOjE3NzIwMTY1OTgsImlzcyI6IlRhbWFyYSBQUCJ9.p_fDShdhdgwipuZldpPvvn4Sv69TZrhn8mJjtLLpQYD4tyKZNFC5VTjBHZEXcIFambpHMC8c6R2R1wRyf0QNtoJXhOn9Q-6abHY7E_yVop0zNr8in8tWGo4zOzAIGV3v-HJ7G6FbFCqgYZvQ3oqUKs4JELjV68W385RbxVnfkQQ2OUk90fAb5JPYuNu7ubamO3iQ8YCVI51fG8jQrZ-y88SO4JHhH4bWPBsTRG4c0uU-imL4on5b3xtmncCQZ5R7epf-25s2ugvrxSu1dbjCCzGHKHcdyuWt9pZyOTmLUHE-lLcmzAFQo9tO6Knj-ag5pI2Hjv9xfk0MfDDa6_sbsA';

function testTamara($apiUrl, $token) {
    $payload = [
        'order_reference_id' => '999999',
        'total_amount' => [
            'amount' => 100,
            'currency' => 'SAR',
        ],
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
                'unit_price' => [
                    'amount' => 100,
                    'currency' => 'SAR'
                ],
                'total_amount' => [
                    'amount' => 100,
                    'currency' => 'SAR'
                ]
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
            'success' => 'https://example.com/success',
            'failure' => 'https://example.com/failure',
            'cancel' => 'https://example.com/cancel',
            'notification' => 'https://example.com/notification',
        ]
    ];

    try {
        echo "Testing $apiUrl...\n";
        $response = Http::withToken($token)->post($apiUrl . '/checkout', $payload);
        echo "Status: " . $response->status() . "\n";
        echo "Body: " . $response->body() . "\n\n";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n\n";
    }
}

testTamara('https://api-sandbox.tamara.co', $merchantToken);
testTamara('https://api.tamara.co', $merchantToken);
