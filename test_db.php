<?php
try {
    $host = '127.0.0.1';
    $db   = 'websec';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    echo "Attempting to connect to MySQL server...\n";
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected to MySQL server successfully!\n\n";
    
    echo "Attempting to create database if it doesn't exist...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`");
    echo "Database check/creation complete!\n\n";
    
    echo "Attempting to connect to the database...\n";
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);
    echo "Connected to database successfully!\n";

} catch (\PDOException $e) {
    echo "Database Connection Error: " . $e->getMessage() . "\n";
    exit(1);
} 