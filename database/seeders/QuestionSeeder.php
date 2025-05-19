<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if questions already exist
        if (Question::count() > 0) {
            return; // Skip if questions already exist
        }
        
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
                'question' => 'What does CSRF stand for in web security?',
                'option_a' => 'Cross-Site Request Forgery',
                'option_b' => 'Client Server Response Format',
                'option_c' => 'Common Security Resolution Framework',
                'option_d' => 'Cross-Site Response Filter',
                'correct_answer' => 'A'
            ],
            [
                'question' => 'Which of the following is NOT a valid HTTP method?',
                'option_a' => 'GET',
                'option_b' => 'POST',
                'option_c' => 'DELETE',
                'option_d' => 'REMOVE',
                'correct_answer' => 'D'
            ]
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
