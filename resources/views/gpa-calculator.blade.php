@extends('layouts.master')

@section('title', 'GPA Calculator')

@section('content')

<div class="container mt-4">
    <h2 class="text-center">GPA Calculator</h2>

    <table border="1" width="100%" cellpadding="5">
        <thead>
            <tr>
                <th>Course</th>
                <th>Credits</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody id="courseTable">
            @foreach ($courses as $course)
            <tr>
                <td>{{ $course['code'] }}</td>
                <td><input type="number" value="{{ $course['credit'] }}" class="credit" min="1"></td>
                <td>
                    <select class="grade">
                        <option value="4">A</option>
                        <option value="3.7">A-</option>
                        <option value="3.3">B+</option>
                        <option value="3">B</option>
                        <option value="2.7">B-</option>
                        <option value="2.3">C+</option>
                        <option value="2">C</option>
                        <option value="1.7">C-</option>
                        <option value="1.3">D+</option>
                        <option value="1">D</option>
                        <option value="0">F</option>
                    </select>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button onclick="calculateGPA()">Calculate GPA</button>
    
    <h3>Your GPA: <span id="gpaResult">0.00</span></h3>
</div>

<script>
    function calculateGPA() {
        let totalCredits = 0, totalPoints = 0;
        document.querySelectorAll("#courseTable tr").forEach(row => {
            let credit = parseFloat(row.querySelector(".credit").value);
            let grade = parseFloat(row.querySelector(".grade").value);
            totalCredits += credit;
            totalPoints += grade * credit;
        });

        let gpa = totalPoints / totalCredits || 0;
        document.getElementById("gpaResult").innerText = gpa.toFixed(2);
    }
</script>
@endsection
