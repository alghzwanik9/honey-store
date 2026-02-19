<?php

$apiKey = "AIzaSyA5-TMxQm0lIe-tSwL3mbFltyZ2eE7b15w";
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;

$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => "Hello"]
            ]
        ]
    ]
];

$options = [
    "http" => [
        "header" => "Content-type: application/json\r\n",
        "method" => "POST",
        "content" => json_encode($data),
        "ignore_errors" => true,
        "timeout" => 10 
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "Error reading URL";
}

echo "Response:\n" . $result . "\n";
