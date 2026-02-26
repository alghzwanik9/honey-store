<?php
require __DIR__ . '/vendor/autoload.php';

$rawToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiI0YTQzNDFmNy01MTIwLT Q5ZjktYTZkZC04MGVkYzUzODM3ZDkiLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiNjZjZGVkZj RjNDZmY2FlN2ZjYjZhNzMzY2QxYWFjMmQiLCJyb2xlcyI6WyJST0xFX01FUkNIQU5UIl0sImlhdC I6MTc3MjAxNjcyOCwiaXNzIjoiVGFtYXJhIn0.F_MaRDWyegV7LcxgxXhLo1IY063ymN1AmhuIkD -9XVPRMS7L8ErYaar9LUyt5cbOWNo-OfB1WSWwl-CI9DEnifl3MVGX4ig2AavC1wcx59nKPgWAfu 2jHEtXJNZYOFyOJ9eEoHf1Fk42vgy2omxhIY8mXJya-ci1zXVFhMfg8G3aP-DJ0pLkKNA_R1gm7G yiCEMH7Kfk7eYbnXSMVxoxysqmOl9fEuuRr-uESKd43zTWARg0ZoHYl1R0qXfygiWTJtnRd2WuMT4bM6TJWAOCRdbgXiCKmVs4Sd6BZWS8CDbATli1nZhfHMfaBN6bd5DOGEB8rU4vbSpg4kU-n7BmKw';

$token = preg_replace('/\s+/', '', str_replace(["\r", "\n"], '', trim($rawToken)));

$envPath = __DIR__ . '/.env';
$envContent = file_get_contents($envPath);

$envContent = preg_replace('/^TAMARA_MERCHANT_TOKEN=.*$/m', 'TAMARA_MERCHANT_TOKEN=' . $token, $envContent);
$envContent = preg_replace('/^TAMARA_NOTIFICATION_TOKEN=.*$/m', 'TAMARA_NOTIFICATION_TOKEN=5cb2dbb6-371a-4b4e-beb8-f7807c359560', $envContent);

if (!preg_match('/^TAMARA_NOTIFICATION_TOKEN=/m', $envContent)) {
    $envContent .= "\nTAMARA_NOTIFICATION_TOKEN=5cb2dbb6-371a-4b4e-beb8-f7807c359560\n";
}

file_put_contents($envPath, $envContent);
echo "Updated .env successfully\n";
