<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Product;
use App\Models\Grade;

class CustomSeeder extends Seeder
{
    public function run()
    {
        // Sample exam questions
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
            ],
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }

        // Sample products
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
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Sample grades
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
            ],
        ];

        foreach ($grades as $grade) {
            Grade::create($grade);
        }
    }
} 