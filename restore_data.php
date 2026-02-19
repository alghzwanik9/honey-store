<?php
// restore_data.php

// SQLite Connection
try {
    $sqlite = new PDO('sqlite:database/database.sqlite');
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to SQLite.\n";
} catch (PDOException $e) {
    die("SQLite Connection Error: " . $e->getMessage());
}

// MySQL Connection
$mysql_host = 'localhost';
$mysql_db   = 'honey_store';
$mysql_user = 'root';
$mysql_pass = '';

try {
    $mysql = new PDO("mysql:host=$mysql_host;dbname=$mysql_db;charset=utf8mb4", $mysql_user, $mysql_pass);
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to MySQL.\n";
} catch (PDOException $e) {
    die("MySQL Connection Error: " . $e->getMessage());
}

// Helper function to transfer data
function transferTable($sqlite, $mysql, $tableName) {
    echo "Transferring table: $tableName...\n";
    
    // Get data from SQLite
    $stmt = $sqlite->query("SELECT * FROM $tableName");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($rows)) {
        echo " - No data found in SQLite for $tableName.\n";
        return;
    }

    // Prepare insert statement for MySQL
    $columns = array_keys($rows[0]);
    $colNames = implode('`, `', $columns);
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    
    $sql = "INSERT INTO `$tableName` (`$colNames`) VALUES ($placeholders) ON DUPLICATE KEY UPDATE id=id";
    $insertStmt = $mysql->prepare($sql);

    $count = 0;
    foreach ($rows as $row) {
        try {
            // Handle null values explicitly slightly better for PDO if needed, but standard execute works for nulls
            $insertStmt->execute(array_values($row));
            $count++;
        } catch (PDOException $e) {
            echo " - Error inserting row ID " . ($row['id'] ?? '?') . ": " . $e->getMessage() . "\n";
        }
    }
    echo " - Migrated $count rows.\n";
}

// Disable foreign key checks to avoid ordering issues
$mysql->exec("SET FOREIGN_KEY_CHECKS=0");

// Transfer Data (Order matters slightly for logic, but FK checks disabled helps)
transferTable($sqlite, $mysql, 'users');
transferTable($sqlite, $mysql, 'categories');
transferTable($sqlite, $mysql, 'products');
transferTable($sqlite, $mysql, 'orders'); // Assuming table exists
transferTable($sqlite, $mysql, 'order_items'); // Assuming table exists

// Re-enable foreign key checks
$mysql->exec("SET FOREIGN_KEY_CHECKS=1");

echo "\nData restoration complete! 🚀\n";
