<?php
try {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    
    // Connect to MySQL without specifying a database
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create the websec database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS websec CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database 'websec' created or already exists.\n";
    
    // Select the websec database
    $pdo->exec("USE websec");
    
    echo "All operations completed successfully!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 