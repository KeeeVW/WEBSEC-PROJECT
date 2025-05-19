@extends('layouts.master')

@section('content')
<h2>Add Question</h2>
<form action="{{ route('questions.store') }}" method="POST">
    @csrf
    <input name="question" placeholder="Question"><br>
    <input name="option_a" placeholder="Option A"><br>
    <input name="option_b" placeholder="Option B"><br>
    <input name="option_c" placeholder="Option C"><br>
    <input name="option_d" placeholder="Option D"><br>
    <input name="correct_answer" placeholder="Correct Answer (A, B, C, D)"><br>
    <button type="submit">Save</button>
</form>
@endsection
