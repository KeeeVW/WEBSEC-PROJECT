@extends('layouts.master')

@section('content')
<h2>Edit Grade</h2>
<form action="{{ route('grades.update', $grade) }}" method="POST">
    @csrf @method('PUT')
    <input type="text" name="course_name" value="{{ $grade->course_name }}" required><br>
    <input type="text" name="term" value="{{ $grade->term }}" required><br>
    <input type="number" name="credit_hours" value="{{ $grade->credit_hours }}" required><br>
    <input type="text" name="grade" value="{{ $grade->grade }}" required><br>
    <button type="submit">Update</button>
</form>
@endsection
