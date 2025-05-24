<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question' => 'What is XSS (Cross-Site Scripting)?',
                'description' => 'A security vulnerability that allows attackers to inject malicious scripts into web pages viewed by other users.',
                'type' => 'multiple_choice',
                'options' => json_encode([
                    'A security feature that prevents unauthorized access',
                    'A type of database injection attack',
                    'A method to encrypt data in transit',
                    'A way to validate user input'
                ]),
                'correct_answer' => 'A security vulnerability that allows attackers to inject malicious scripts into web pages viewed by other users.',
                'points' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'What is the purpose of CSRF tokens?',
                'description' => 'To prevent Cross-Site Request Forgery attacks by validating that requests come from legitimate sources.',
                'type' => 'multiple_choice',
                'options' => json_encode([
                    'To encrypt user passwords',
                    'To prevent SQL injection',
                    'To validate user sessions',
                    'To prevent unauthorized form submissions'
                ]),
                'correct_answer' => 'To prevent unauthorized form submissions',
                'points' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Which HTTP method should be used for sensitive data?',
                'description' => 'Understanding secure data transmission methods.',
                'type' => 'multiple_choice',
                'options' => json_encode([
                    'GET',
                    'POST',
                    'PUT',
                    'DELETE'
                ]),
                'correct_answer' => 'POST',
                'points' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($questions as $question) {
            DB::table('questions')->insert(array_merge($question, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
