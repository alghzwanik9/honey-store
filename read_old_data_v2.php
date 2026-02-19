<?php
try {
    $db = new SQLite3('database/database.sqlite');
    
    echo "=== Categories ===\n";
    $results = $db->query('SELECT name FROM categories');
    while ($row = $results->fetchArray()) {
        echo "- " . $row['name'] . "\n";
    }

    echo "\n=== Products ===\n";
    $results = $db->query('SELECT name, price, description, weight FROM products');
    while ($row = $results->fetchArray()) {
        echo "- " . $row['name'] . " (" . $row['price'] . " SAR)\n";
        echo "  Weight: " . ($row['weight'] ?? 'N/A') . "\n"; 
        echo "  Desc: " . substr($row['description'], 0, 50) . "...\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
