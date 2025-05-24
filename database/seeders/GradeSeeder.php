<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $gradeLetters = ['A', 'B', 'C', 'D', 'F'];
        
        foreach ($users as $user) {
            // Generate random scores
            $score = rand(60, 100);
            $totalPoints = 100;
            $percentage = ($score / $totalPoints) * 100;
            
            // Determine grade letter
            $gradeLetter = match(true) {
                $percentage >= 90 => 'A',
                $percentage >= 80 => 'B',
                $percentage >= 70 => 'C',
                $percentage >= 60 => 'D',
                default => 'F'
            };
            
            // Generate feedback based on grade
            $feedback = match($gradeLetter) {
                'A' => 'Excellent work! You have demonstrated a strong understanding of web security concepts.',
                'B' => 'Good job! You have a solid understanding of web security concepts with some areas for improvement.',
                'C' => 'Satisfactory performance. Consider reviewing the material to strengthen your understanding.',
                'D' => 'Needs improvement. Please review the material and consider additional study.',
                'F' => 'Failed. Please review the material thoroughly and retake the assessment.',
                default => 'No feedback available.'
            };

            DB::table('grades')->insert([
                'user_id' => $user->id,
                'score' => $score,
                'total_points' => $totalPoints,
                'percentage' => $percentage,
                'grade_letter' => $gradeLetter,
                'feedback' => $feedback,
                'is_passed' => $percentage >= 60,
                'completed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
