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
    
    // Insert questions
    $questions = [
        [
            'question' => 'What is the correct way to declare a variable in PHP?',
            'option_a' => '$variable = value;',
            'option_b' => 'variable = value;',
            'option_c' => 'var variable = value;',
            'option_d' => 'variable := value;',
            'correct_answer' => 'A'
        ],
        [
            'question' => 'Which SQL statement is used to retrieve data from a database?',
            'option_a' => 'GET',
            'option_b' => 'SELECT',
            'option_c' => 'EXTRACT',
            'option_d' => 'OPEN',
            'correct_answer' => 'B'
        ],
        [
            'question' => 'Which tag is used to define an HTML hyperlink?',
            'option_a' => '<link>',
            'option_b' => '<a>',
            'option_c' => '<href>',
            'option_d' => '<hyperlink>',
            'correct_answer' => 'B'
        ],
        [
            'question' => 'What does CSS stand for?',
            'option_a' => 'Cascading Style Sheets',
            'option_b' => 'Computer Style Sheets',
            'option_c' => 'Creative Style Selector',
            'option_d' => 'Content Styling System',
            'correct_answer' => 'A'
        ],
        [
            'question' => 'Which HTTP method is used to submit data to be processed?',
            'option_a' => 'GET',
            'option_b' => 'POST',
            'option_c' => 'PUT',
            'option_d' => 'HEAD',
            'correct_answer' => 'B'
        ]
    ];

    // Check if questions table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'questions'");
    $questionsTableExists = $stmt->rowCount() > 0;
    
    if ($questionsTableExists) {
        echo "Questions table exists, checking for data...\n";
        
        // Check if there are already records
        $stmt = $conn->query("SELECT COUNT(*) FROM questions");
        $questionCount = $stmt->fetchColumn();
        
        if ($questionCount == 0) {
            echo "Inserting questions data...\n";
            
            $stmt = $conn->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_answer, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
            
            foreach ($questions as $question) {
                $stmt->execute([
                    $question['question'],
                    $question['option_a'],
                    $question['option_b'],
                    $question['option_c'],
                    $question['option_d'],
                    $question['correct_answer']
                ]);
            }
            
            echo "Questions inserted successfully!\n";
        } else {
            echo "Questions already exist, skipping...\n";
        }
    } else {
        echo "Questions table does not exist!\n";
    }
    
    // Insert grades
    $grades = [
        [
            'course_name' => 'Web and Security Technologies',
            'term' => '2023 Fall',
            'credit_hours' => 3,
            'grade' => 'A'
        ],
        [
            'course_name' => 'Linux and Shell Programming',
            'term' => '2023 Fall',
            'credit_hours' => 3,
            'grade' => 'B+'
        ],
        [
            'course_name' => 'Network Operation and Management',
            'term' => '2023 Fall',
            'credit_hours' => 3,
            'grade' => 'A-'
        ],
        [
            'course_name' => 'Digital Forensics Fundamentals',
            'term' => '2024 Spring',
            'credit_hours' => 3,
            'grade' => 'B'
        ]
    ];

    // Check if grades table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'grades'");
    $gradesTableExists = $stmt->rowCount() > 0;
    
    if ($gradesTableExists) {
        echo "Grades table exists, checking for data...\n";
        
        // Check if there are already records
        $stmt = $conn->query("SELECT COUNT(*) FROM grades");
        $gradeCount = $stmt->fetchColumn();
        
        if ($gradeCount == 0) {
            echo "Inserting grades data...\n";
            
            $stmt = $conn->prepare("INSERT INTO grades (course_name, term, credit_hours, grade, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
            
            foreach ($grades as $grade) {
                $stmt->execute([
                    $grade['course_name'],
                    $grade['term'],
                    $grade['credit_hours'],
                    $grade['grade']
                ]);
            }
            
            echo "Grades inserted successfully!\n";
        } else {
            echo "Grades already exist, skipping...\n";
        }
    } else {
        echo "Grades table does not exist!\n";
    }
    
    // Insert products
    $products = [
        [
            'code' => 'LT-001',
            'name' => 'Laptop Pro 2023',
            'model' => 'LTP-2023',
            'description' => 'Powerful laptop for professionals with 16GB RAM and 512GB SSD',
            'price' => 1299.99,
            'photo' => 'laptop.jpg'
        ],
        [
            'code' => 'SP-002',
            'name' => 'SmartPhone X',
            'model' => 'SPX-12',
            'description' => 'Latest smartphone with 6.5" OLED display and 128GB storage',
            'price' => 899.99,
            'photo' => 'smartphone.jpg'
        ],
        [
            'code' => 'TB-003',
            'name' => 'TabletPad Mini',
            'model' => 'TPM-10',
            'description' => 'Compact tablet with 10" display perfect for reading and browsing',
            'price' => 499.99,
            'photo' => 'tablet.jpg'
        ]
    ];

    // Check if products table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'products'");
    $productsTableExists = $stmt->rowCount() > 0;
    
    if ($productsTableExists) {
        echo "Products table exists, checking for data...\n";
        
        // Check if there are already records
        $stmt = $conn->query("SELECT COUNT(*) FROM products");
        $productCount = $stmt->fetchColumn();
        
        if ($productCount == 0) {
            echo "Inserting products data...\n";
            
            $stmt = $conn->prepare("INSERT INTO products (code, name, model, description, price, photo, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
            
            foreach ($products as $product) {
                $stmt->execute([
                    $product['code'],
                    $product['name'],
                    $product['model'],
                    $product['description'],
                    $product['price'],
                    $product['photo']
                ]);
            }
            
            echo "Products inserted successfully!\n";
        } else {
            echo "Products already exist, skipping...\n";
        }
    } else {
        echo "Products table does not exist!\n";
    }
    
    echo "All sample data inserted successfully!\n";
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
} 