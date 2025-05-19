<?php
// Load the database configuration from .env
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
            putenv("$name=$value");
        }
    }
}

// Database connection parameters
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_DATABASE') ?: 'websec';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully\n";
    
    // Update admin user
    $stmt = $conn->prepare("UPDATE users SET is_admin = 1 WHERE email = 'admin@google.com'");
    $stmt->execute();
    $adminCount = $stmt->rowCount();
    echo "Updated {$adminCount} admin users to have is_admin = 1\n";
    
    // Update employee user (not admin)
    $stmt = $conn->prepare("UPDATE users SET is_admin = 0 WHERE email = 'employee@google.com'");
    $stmt->execute();
    $employeeCount = $stmt->rowCount();
    echo "Updated {$employeeCount} employee users to have is_admin = 0\n";
    
    // Update customer user (not admin)
    $stmt = $conn->prepare("UPDATE users SET is_admin = 0 WHERE email = 'customer@google.com'");
    $stmt->execute();
    $customerCount = $stmt->rowCount();
    echo "Updated {$customerCount} customer users to have is_admin = 0\n";
    
    // Set is_admin to 0 for any other users where it's currently NULL
    $stmt = $conn->prepare("UPDATE users SET is_admin = 0 WHERE is_admin IS NULL");
    $stmt->execute();
    $otherCount = $stmt->rowCount();
    echo "Updated {$otherCount} other users to have is_admin = 0\n";
    
    echo "All users updated successfully!\n";
    
    // Show the current user status
    $stmt = $conn->query("SELECT id, name, email, is_admin FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nCurrent users in the system:\n";
    echo "-----------------------------\n";
    foreach ($users as $user) {
        echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}, Is Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . "\n";
    }
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
} 