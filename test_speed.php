<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$start = microtime(true);
echo "Sending request...\n";

try {
    $response = \Prism\Prism\Facades\Prism::text()
        ->using(\Prism\Prism\Enums\Provider::Ollama, 'llama3.1:8b-instruct-q4_K_M')
        ->withPrompt('1+1=?')
        ->generate();
    
    $end = microtime(true);
    echo "Response: " . $response->text . "\n";
    echo "Time taken: " . round($end - $start, 2) . " seconds\n";
    
    // Check context size
    echo "Total Products: " . \App\Models\Product::count() . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
