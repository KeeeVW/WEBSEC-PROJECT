<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertSampleData extends Command
{
    protected $signature = 'app:insert-sample-data';
    protected $description = 'Insert sample data into the database';

    public function handle()
    {
        // Insert questions data
        try {
            $this->info('Inserting questions data...');
            
            // Check if questions table exists
            if (!$this->tableExists('questions')) {
                $this->error('Questions table does not exist!');
                return 1;
            }
            
            // Check if there are already records
            if (DB::table('questions')->count() > 0) {
                $this->info('Questions already exist, skipping...');
            } else {
                DB::table('questions')->insert([
                    [
                        'question' => 'What is the correct way to declare a variable in PHP?',
                        'option_a' => '$variable = value;',
                        'option_b' => 'variable = value;',
                        'option_c' => 'var variable = value;',
                        'option_d' => 'variable := value;',
                        'correct_answer' => 'A',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'question' => 'Which SQL statement is used to retrieve data from a database?',
                        'option_a' => 'GET',
                        'option_b' => 'SELECT',
                        'option_c' => 'EXTRACT',
                        'option_d' => 'OPEN',
                        'correct_answer' => 'B',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'question' => 'Which tag is used to define an HTML hyperlink?',
                        'option_a' => '<link>',
                        'option_b' => '<a>',
                        'option_c' => '<href>',
                        'option_d' => '<hyperlink>',
                        'correct_answer' => 'B',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'question' => 'What does CSS stand for?',
                        'option_a' => 'Cascading Style Sheets',
                        'option_b' => 'Computer Style Sheets',
                        'option_c' => 'Creative Style Selector',
                        'option_d' => 'Content Styling System',
                        'correct_answer' => 'A',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'question' => 'Which HTTP method is used to submit data to be processed?',
                        'option_a' => 'GET',
                        'option_b' => 'POST',
                        'option_c' => 'PUT',
                        'option_d' => 'HEAD',
                        'correct_answer' => 'B',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]);
                $this->info('Questions inserted successfully!');
            }
            
            // Insert grades data
            $this->info('Inserting grades data...');
            
            // Check if grades table exists
            if (!$this->tableExists('grades')) {
                $this->error('Grades table does not exist!');
                return 1;
            }
            
            // Check if there are already records
            if (DB::table('grades')->count() > 0) {
                $this->info('Grades already exist, skipping...');
            } else {
                DB::table('grades')->insert([
                    [
                        'course_name' => 'Web and Security Technologies',
                        'term' => '2023 Fall',
                        'credit_hours' => 3,
                        'grade' => 'A',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'course_name' => 'Linux and Shell Programming',
                        'term' => '2023 Fall',
                        'credit_hours' => 3,
                        'grade' => 'B+',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'course_name' => 'Network Operation and Management',
                        'term' => '2023 Fall',
                        'credit_hours' => 3,
                        'grade' => 'A-',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'course_name' => 'Digital Forensics Fundamentals',
                        'term' => '2024 Spring',
                        'credit_hours' => 3,
                        'grade' => 'B',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]);
                $this->info('Grades inserted successfully!');
            }
            
            // Insert products data
            $this->info('Inserting products data...');
            
            // Check if products table exists
            if (!$this->tableExists('products')) {
                $this->error('Products table does not exist!');
                return 1;
            }
            
            // Check if there are already records
            if (DB::table('products')->count() > 0) {
                $this->info('Products already exist, skipping...');
            } else {
                DB::table('products')->insert([
                    [
                        'code' => 'LT-001',
                        'name' => 'Laptop Pro 2023',
                        'model' => 'LTP-2023',
                        'description' => 'Powerful laptop for professionals with 16GB RAM and 512GB SSD',
                        'price' => 1299.99,
                        'photo' => 'laptop.jpg',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'code' => 'SP-002',
                        'name' => 'SmartPhone X',
                        'model' => 'SPX-12',
                        'description' => 'Latest smartphone with 6.5" OLED display and 128GB storage',
                        'price' => 899.99,
                        'photo' => 'smartphone.jpg',
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'code' => 'TB-003',
                        'name' => 'TabletPad Mini',
                        'model' => 'TPM-10',
                        'description' => 'Compact tablet with 10" display perfect for reading and browsing',
                        'price' => 499.99,
                        'photo' => 'tablet.jpg',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]);
                $this->info('Products inserted successfully!');
            }
            
            $this->info('All sample data inserted successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }

    private function tableExists($table)
    {
        try {
            // Try to query the table to see if it exists
            DB::select('SELECT 1 FROM ' . $table . ' LIMIT 1');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
} 