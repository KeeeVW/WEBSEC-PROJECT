@extends('layouts.master')

@section('content')
<h2>Add Grade</h2>
<form action="{{ route('grades.store') }}" method="POST">
    @csrf
    <input type="text" name="course_name" placeholder="Course Name" required><br>
    <input type="text" name="term" placeholder="Term (e.g., Spring 2025)" required><br>
    <input type="number" name="credit_hours" placeholder="Credit Hours" required><br>
    <input type="text" name="grade" placeholder="Grade (A, B+, etc.)" required><br>
    <button type="submit">Add</button>
</form>
@endsection
