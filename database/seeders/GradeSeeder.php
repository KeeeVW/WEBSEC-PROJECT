<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if grades already exist
        if (Grade::count() > 0) {
            return; // Skip if grades already exist
        }
        
        // Sample grades for Term 1
        $term1Grades = [
            [
                'course_name' => 'Web Security',
                'term' => 'Fall 2024',
                'credit_hours' => 3,
                'grade' => 'A'
            ],
            [
                'course_name' => 'Linux Programming',
                'term' => 'Fall 2024',
                'credit_hours' => 3,
                'grade' => 'B+'
            ],
            [
                'course_name' => 'Computer Networks',
                'term' => 'Fall 2024',
                'credit_hours' => 4,
                'grade' => 'A-'
            ],
        ];

        // Sample grades for Term 2
        $term2Grades = [
            [
                'course_name' => 'Database Systems',
                'term' => 'Spring 2025',
                'credit_hours' => 3,
                'grade' => 'B'
            ],
            [
                'course_name' => 'Digital Forensics',
                'term' => 'Spring 2025',
                'credit_hours' => 3,
                'grade' => 'A+'
            ],
            [
                'course_name' => 'Cybersecurity',
                'term' => 'Spring 2025',
                'credit_hours' => 3,
                'grade' => 'B-'
            ],
        ];

        // Create the grades
        foreach (array_merge($term1Grades, $term2Grades) as $grade) {
            Grade::create($grade);
        }
    }
}
