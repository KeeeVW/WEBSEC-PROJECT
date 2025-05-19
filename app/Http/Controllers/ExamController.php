<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function start()
    {
        $questions = Question::inRandomOrder()->take(5)->get(); // limit to 5 questions
        return view('users.exam.start', compact('questions'));
    }

    public function submit(Request $request)
    {
        $answers = $request->except('_token');
        $score = 0;

        foreach ($answers as $id => $selected) {
            $question = Question::find($id);
            if ($question && $question->correct_answer === $selected) {
                $score++;
            }
        }

        return view('users.exam.result', [
            'score' => $score,
            'total' => count($answers)
        ]);
    }
}
