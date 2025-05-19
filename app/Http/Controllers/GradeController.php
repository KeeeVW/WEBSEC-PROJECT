<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::all()->groupBy('term');

        $termSummaries = [];
        $cumulativeCredits = 0;
        $cumulativePoints = 0;

        foreach ($grades as $term => $termGrades) {
            $termCH = 0;
            $termPoints = 0;

            foreach ($termGrades as $grade) {
                $termCH += $grade->credit_hours;
                $termPoints += $grade->getGradePoint() * $grade->credit_hours;
            }

            $termGPA = $termCH > 0 ? round($termPoints / $termCH, 2) : 0;

            $cumulativeCredits += $termCH;
            $cumulativePoints += $termPoints;

            $termSummaries[$term] = [
                'grades' => $termGrades,
                'gpa' => $termGPA,
                'ch' => $termCH
            ];
        }

        $cgpa = $cumulativeCredits > 0 ? round($cumulativePoints / $cumulativeCredits, 2) : 0;

        return view('users.grades.index', compact('termSummaries', 'cgpa', 'cumulativeCredits'));
    }

    public function create()
    {
        return view('users.grades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required',
            'term' => 'required',
            'credit_hours' => 'required|integer|min:1',
            'grade' => 'required',
        ]);

        Grade::create($request->all());

        return redirect()->route('grades.index')->with('success', 'Grade added successfully');
    }

    public function edit(Grade $grade)
    {
        return view('users.grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade)
    {
        $grade->update($request->all());
        return redirect()->route('grades.index')->with('success', 'Grade updated');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Grade deleted');
    }
}
