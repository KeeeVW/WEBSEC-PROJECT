<?php
echo "<h1>MySQL Connection Test</h1>";

// Connection variables
$host = "localhost";
$username = "root";
$password = "";

try {
    // Connect to MySQL server only (no specific database)
    $conn = new mysqli($host, $username, $password);

    if ($conn->connect_error) {
        die("Connection to MySQL server failed: " . $conn->connect_error);
    }
    
    echo "<p>Successfully connected to MySQL server!</p>";
    
    // Try to create the database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS websec";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Database 'websec' created or already exists.</p>";
    } else {
        echo "<p>Error creating database: " . $conn->error . "</p>";
    }
    
    // Select the database
    $conn->select_db("websec");
    echo "<p>Successfully connected to 'websec' database!</p>";
    
    // Create a test table
    $sql = "CREATE TABLE IF NOT EXISTS test_table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        test_field VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>Test table created or already exists.</p>";
    } else {
        echo "<p>Error creating test table: " . $conn->error . "</p>";
    }
    
    // Insert a test record
    $sql = "INSERT INTO test_table (test_field) VALUES ('Test record created at " . date('Y-m-d H:i:s') . "')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Test record inserted successfully.</p>";
    } else {
        echo "<p>Error inserting test record: " . $conn->error . "</p>";
    }
    
    // Query the table
    $sql = "SELECT * FROM test_table ORDER BY id DESC LIMIT 5";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<h2>Last 5 Test Records:</h2>";
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li>ID: " . $row["id"] . " - " . $row["test_field"] . " (Created: " . $row["created_at"] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No test records found.</p>";
    }
    
    // Close the connection
    $conn->close();
    
    echo "<p>All database operations completed successfully!</p>";
    
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
} 