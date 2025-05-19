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
    
    // Create roles if they don't exist yet
    $roles = ['admin', 'employee', 'customer'];
    
    // Check if roles table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'roles'");
    $rolesTableExists = $stmt->rowCount() > 0;
    
    if ($rolesTableExists) {
        echo "Roles table exists, checking for existing roles...\n";
        
        // Create roles if they don't exist
        foreach ($roles as $roleName) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM roles WHERE name = ?");
            $stmt->execute([$roleName]);
            $roleExists = $stmt->fetchColumn() > 0;
            
            if (!$roleExists) {
                $stmt = $conn->prepare("INSERT INTO roles (name, guard_name, created_at, updated_at) VALUES (?, 'web', NOW(), NOW())");
                $stmt->execute([$roleName]);
                echo "Role '{$roleName}' created successfully!\n";
            } else {
                echo "Role '{$roleName}' already exists, skipping...\n";
            }
        }
    } else {
        echo "Roles table does not exist! Creating roles table...\n";
        
        // Create roles table
        $conn->exec("CREATE TABLE IF NOT EXISTS roles (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            guard_name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        )");
        
        echo "Roles table created successfully!\n";
        
        // Create roles
        foreach ($roles as $roleName) {
            $stmt = $conn->prepare("INSERT INTO roles (name, guard_name, created_at, updated_at) VALUES (?, 'web', NOW(), NOW())");
            $stmt->execute([$roleName]);
            echo "Role '{$roleName}' created successfully!\n";
        }
    }
    
    // Create model_has_roles table if it doesn't exist
    $stmt = $conn->query("SHOW TABLES LIKE 'model_has_roles'");
    $modelHasRolesTableExists = $stmt->rowCount() > 0;
    
    if (!$modelHasRolesTableExists) {
        echo "Model_has_roles table does not exist! Creating model_has_roles table...\n";
        
        $conn->exec("CREATE TABLE IF NOT EXISTS model_has_roles (
            role_id BIGINT UNSIGNED NOT NULL,
            model_type VARCHAR(255) NOT NULL,
            model_id BIGINT UNSIGNED NOT NULL,
            PRIMARY KEY (role_id, model_id, model_type)
        )");
        
        echo "Model_has_roles table created successfully!\n";
    }
    
    // Hash the password (using Laravel's bcrypt algorithm)
    $password = password_hash('Admin@123', PASSWORD_BCRYPT);
    
    // Create users for each role with similar emails
    $users = [
        [
            'name' => 'Admin User',
            'email' => 'admin@google.com',
            'password' => $password,
            'role' => 'admin'
        ],
        [
            'name' => 'Employee User',
            'email' => 'employee@google.com',
            'password' => $password,
            'role' => 'employee'
        ],
        [
            'name' => 'Customer User',
            'email' => 'customer@google.com',
            'password' => $password,
            'role' => 'customer'
        ]
    ];
    
    foreach ($users as $user) {
        // Check if user already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$user['email']]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingUser) {
            echo "User with email '{$user['email']}' already exists. Updating role...\n";
            $userId = $existingUser['id'];
        } else {
            // Create new user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                $user['name'],
                $user['email'],
                $user['password']
            ]);
            
            $userId = $conn->lastInsertId();
            echo "User '{$user['name']}' with email '{$user['email']}' created successfully!\n";
        }
        
        // Get role ID
        $stmt = $conn->prepare("SELECT id FROM roles WHERE name = ?");
        $stmt->execute([$user['role']]);
        $roleId = $stmt->fetchColumn();
        
        if ($roleId) {
            // Clear existing role assignments
            $stmt = $conn->prepare("DELETE FROM model_has_roles WHERE model_id = ? AND model_type = 'App\\\\Models\\\\User'");
            $stmt->execute([$userId]);
            
            // Assign role to user
            $stmt = $conn->prepare("INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES (?, 'App\\\\Models\\\\User', ?)");
            $stmt->execute([$roleId, $userId]);
            
            echo "Role '{$user['role']}' assigned to user '{$user['name']}' successfully!\n";
        } else {
            echo "Role '{$user['role']}' not found. Could not assign role.\n";
        }
    }
    
    echo "All users created and roles assigned successfully!\n";
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
} 