<?php

// Database configuration
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'websec';

try {
    // Create connection without database
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    $conn->exec($sql);
    echo "Database created successfully\n";
    
    // Select the database
    $conn->exec("USE $database");
    
    // Create roles table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS roles (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        guard_name VARCHAR(255) NOT NULL DEFAULT 'web',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        UNIQUE KEY roles_name_guard_name_unique (name, guard_name)
    )";
    $conn->exec($sql);
    echo "Roles table created successfully\n";
    
    // Create permissions table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS permissions (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        guard_name VARCHAR(255) NOT NULL DEFAULT 'web',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        UNIQUE KEY permissions_name_guard_name_unique (name, guard_name)
    )";
    $conn->exec($sql);
    echo "Permissions table created successfully\n";
    
    // Create model_has_roles table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS model_has_roles (
        role_id BIGINT UNSIGNED NOT NULL,
        model_type VARCHAR(255) NOT NULL,
        model_id BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY (role_id, model_id, model_type),
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
    )";
    $conn->exec($sql);
    echo "Model has roles table created successfully\n";
    
    // Create model_has_permissions table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS model_has_permissions (
        permission_id BIGINT UNSIGNED NOT NULL,
        model_type VARCHAR(255) NOT NULL,
        model_id BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY (permission_id, model_id, model_type),
        FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
    )";
    $conn->exec($sql);
    echo "Model has permissions table created successfully\n";
    
    // Create role_has_permissions table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS role_has_permissions (
        permission_id BIGINT UNSIGNED NOT NULL,
        role_id BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY (permission_id, role_id),
        FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
    )";
    $conn->exec($sql);
    echo "Role has permissions table created successfully\n";
    
    // Insert manager role if not exists
    $stmt = $conn->prepare("INSERT IGNORE INTO roles (name, guard_name, created_at, updated_at) VALUES (?, 'web', NOW(), NOW())");
    $stmt->execute(['manager']);
    echo "Manager role created successfully\n";
    
    // Insert support role if not exists
    $stmt->execute(['support']);
    echo "Support role created successfully\n";
    
    // Insert manager permissions
    $managerPermissions = [
        'access_manager_dashboard',
        'manage_support_staff',
        'view_all_tickets',
        'manage_ticket_priorities',
        'manage_ticket_categories',
        'view_system_logs',
        'manage_system_settings',
        'view_financial_reports',
        'manage_pricing',
        'manage_discounts',
        'view_customer_details',
        'manage_customer_credits',
        'manage_product_categories',
        'manage_support_roles'
    ];
    
    // Insert support permissions
    $supportPermissions = [
        'access_support_dashboard',
        'create_tickets',
        'close_tickets',
        'reassign_tickets',
        'view_ticket_history',
        'manage_ticket_attachments',
        'view_customer_history',
        'view_product_details',
        'view_order_details',
        'manage_ticket_notes',
        'view_support_analytics'
    ];
    
    // Insert all permissions
    $stmt = $conn->prepare("INSERT IGNORE INTO permissions (name, guard_name, created_at, updated_at) VALUES (?, 'web', NOW(), NOW())");
    foreach (array_merge($managerPermissions, $supportPermissions) as $permission) {
        $stmt->execute([$permission]);
    }
    echo "Permissions created successfully\n";
    
    // Get role IDs
    $stmt = $conn->query("SELECT id, name FROM roles WHERE name IN ('manager', 'support')");
    $roles = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Assign permissions to roles
    $stmt = $conn->prepare("INSERT IGNORE INTO role_has_permissions (permission_id, role_id) 
        SELECT p.id, ? FROM permissions p WHERE p.name = ?");
    
    // Assign manager permissions
    foreach ($managerPermissions as $permission) {
        $stmt->execute([$roles['manager'], $permission]);
    }
    
    // Assign support permissions
    foreach ($supportPermissions as $permission) {
        $stmt->execute([$roles['support'], $permission]);
    }
    echo "Permissions assigned to roles successfully\n";
    
    echo "Database setup completed successfully!\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 