<?php

use App\Services\AIService;
use Illuminate\Support\Facades\Log;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(AIService::class);

echo "\n--- TEST 1: Benefits of Moringa (expecting 'Energy') ---\n";
try {
    $response = $service->askAssistant("ما هي فائدة عسل المورينجا؟");
    echo "Response: " . $response . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n--- TEST 2: Usage Advice (expecting 'on empty stomach') ---\n";
try {
    $response = $service->askAssistant("كيف أستخدم عسل السدر للمناعة؟");
    echo "Response: " . $response . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n--- TEST 3: Product Description for Dahiana (expecting 'Creamy') ---\n";
try {
    $desc = $service->generateProductDescription("عسل الضهيان");
    echo "Description: " . $desc . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n--- TEST 4: Colon Issues (expecting 'Talh') ---\n";
try {
    $response = $service->askAssistant("عندي مشاكل بالقولون، وش تنصحني؟");
    echo "Response: " . $response . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
