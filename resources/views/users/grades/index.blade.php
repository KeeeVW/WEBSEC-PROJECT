@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Grades by Term</h2>
    <a href="{{ route('grades.create') }}">Add Grade</a>

    @foreach($termSummaries as $term => $summary)
        <h3>{{ $term }}</h3>
        <table>
            <tr>
                <th>Course</th><th>CH</th><th>Grade</th><th>Actions</th>
            </tr>
            @foreach($summary['grades'] as $grade)
                <tr>
                    <td>{{ $grade->course_name }}</td>
                    <td>{{ $grade->credit_hours }}</td>
                    <td>{{ $grade->grade }}</td>
                    <td>
                        <a href="{{ route('grades.edit', $grade) }}">Edit</a>
                        <form method="POST" action="{{ route('grades.destroy', $grade) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        <p><strong>Term GPA:</strong> {{ $summary['gpa'] }} | <strong>Total CH:</strong> {{ $summary['ch'] }}</p>
        <hr>
    @endforeach

    <h3>Cumulative CGPA: {{ $cgpa }} | Total CCH: {{ $cumulativeCredits }}</h3>
</div>
@endsection
