@extends('layouts.master')

@section('title', 'Student Transcript ')

@section('content')
    <h2 class="mb-4 text-center">Student Transcript</h2>

    @php
        $student = "Kevin Wael";
        $courses = [
            ['course' => 'Web and Security Technologies', 'grade' => 'A'],
            ['course' => 'Network Operation and Managment', 'grade' => 'A'],
            ['course' => 'Linux and Shell Programming ', 'grade' => 'A'],
            ['course' => 'Digital Forensics Fundamental ', 'grade' => 'A'],
        ];
    @endphp

    <div class="card p-3">
        <h4>Student: {{ $student }}</h4>
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Course</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td>{{ $course['course'] }}</td>
                        <td>{{ $course['grade'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
@endsection
