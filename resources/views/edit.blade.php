@extends('layouts.master')

@section('content')
<h2>Edit Question</h2>
<form action="{{ route('questions.update', $question) }}" method="POST">
    @csrf @method('PUT')
    <input name="question" value="{{ $question->question }}"><br>
    <input name="option_a" value="{{ $question->option_a }}"><br>
    <input name="option_b" value="{{ $question->option_b }}"><br>
    <input name="option_c" value="{{ $question->option_c }}"><br>
    <input name="option_d" value="{{ $question->option_d }}"><br>
    <input name="correct_answer" value="{{ $question->correct_answer }}"><br>
    <button type="submit">Update</button>
</form>
@endsection
