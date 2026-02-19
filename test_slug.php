<?php

require 'vendor/autoload.php';

$text = "عسل سمرة";

echo "Original: $text\n";
echo "Str::slug: " . \Illuminate\Support\Str::slug($text) . "\n";
echo "Str::ascii: " . \Illuminate\Support\Str::ascii($text) . "\n";

if (class_exists('ArPHP\I18N\Arabic')) {
    $arabic = new \ArPHP\I18N\Arabic();
    // Try different methods if available (guessing common methods based on library history)
    // urlSafe is common in ArPHP 
    if (method_exists($arabic, 'urlSafe')) {
        echo "ArPHP urlSafe: " . $arabic->urlSafe($text) . "\n";
    }
    
    // ar2en used for transliteration
    if (method_exists($arabic, 'ar2en')) {
         echo "ArPHP ar2en: " . $arabic->ar2en($text) . "\n";
    }
} else {
    echo "ArPHP class not found.\n";
}
