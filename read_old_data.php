<?php
try {
    $sqlite = new PDO('sqlite:database/database.sqlite');
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== Categories ===\n";
    $stm = $sqlite->query("SELECT name FROM categories");
    $cats = $stm->fetchAll(PDO::FETCH_COLUMN);
    foreach($cats as $c) echo "- $c\n";

    echo "\n=== Products ===\n";
    $stm = $sqlite->query("SELECT name, price, description FROM products");
    $prods = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach($prods as $p) {
        echo "- {$p['name']} ({$p['price']} SAR)\n";
        echo "  Desc: " . substr($p['description'], 0, 50) . "...\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
