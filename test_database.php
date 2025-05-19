<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing database connection...\n";

try {
    $host = 'localhost';
    $dbname = 'websec';
    $username = 'root';
    $password = '';
    
    echo "Connecting to MySQL...\n";
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully to database: $dbname\n";
    
    // Try creating a test table
    $conn->exec("CREATE TABLE IF NOT EXISTS test_connection (id INT AUTO_INCREMENT PRIMARY KEY, test_column VARCHAR(255))");
    echo "Successfully created or confirmed test_connection table\n";
    
    // Insert a test record
    $conn->exec("INSERT INTO test_connection (test_column) VALUES ('Test record " . date('Y-m-d H:i:s') . "')");
    echo "Successfully inserted test record\n";
    
    // Query the test record
    $stmt = $conn->query("SELECT * FROM test_connection ORDER BY id DESC LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Successfully retrieved record: " . print_r($result, true) . "\n";
    
    echo "All database operations completed successfully!\n";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    
    // More detailed error reporting
    echo "\nDetailed error information:\n";
    echo "Error code: " . $e->getCode() . "\n";
    
    if (strpos($e->getMessage(), "Unknown database") !== false) {
        echo "\nThe 'websec' database doesn't exist. Creating it now...\n";
        try {
            $conn = new PDO("mysql:host=$host", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            echo "Database '$dbname' created successfully!\n";
            echo "Please run this script again.\n";
        } catch(PDOException $e2) {
            echo "Failed to create database: " . $e2->getMessage() . "\n";
        }
    }
} 