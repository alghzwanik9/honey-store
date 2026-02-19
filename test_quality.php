<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(\App\Services\AIService::class);

echo "Generating description for 'Sidr Honey' (عسل السدر)...\n";
$start = microtime(true);

// Test generating a description
$desc = $service->generateProductDescription('عسل السدر البلدي', 'عسل طبيعي');

$end = microtime(true);

echo "\n--- Generated Description ---\n";
echo $desc;
echo "\n-----------------------------\n";
echo "Time taken: " . round($end - $start, 2) . "s\n";
