<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::all();
        return view('users.exam.questions', compact('questions'));
    }

    public function create()
    {
        return view('users.exam.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'correct_answer' => 'required|in:A,B,C,D',
        ]);

        Question::create($request->all());
        return redirect()->route('questions.index')->with('success', 'Question added.');
    }

    public function edit(Question $question)
    {
        return view('users.exam.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $question->update($request->all());
        return redirect()->route('questions.index')->with('success', 'Updated.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Deleted.');
    }
}
