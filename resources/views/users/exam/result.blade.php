@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Exam Results</h2>
        </div>
        <div class="card-body text-center">
            <div class="mb-4">
                <h1 class="display-4">{{ $score }} / {{ $total }}</h1>
                <p class="lead">Your Score</p>
            </div>
            
            <div class="mb-4">
                <h3>{{ number_format(($score / $total) * 100, 1) }}%</h3>
                <p>Percentage Score</p>
            </div>
            
            <div class="mb-4">
                @php
                    $percentage = ($score / $total) * 100;
                @endphp
                
                @if($percentage >= 90)
                    <div class="alert alert-success">
                        <h4>Excellent!</h4>
                        <p>You've demonstrated an outstanding understanding of the material.</p>
                    </div>
                @elseif($percentage >= 80)
                    <div class="alert alert-success">
                        <h4>Great Job!</h4>
                        <p>You've shown a strong grasp of the concepts.</p>
                    </div>
                @elseif($percentage >= 70)
                    <div class="alert alert-info">
                        <h4>Good Work!</h4>
                        <p>You have a solid understanding, but there's room for improvement.</p>
                    </div>
                @elseif($percentage >= 60)
                    <div class="alert alert-warning">
                        <h4>Satisfactory</h4>
                        <p>You've passed, but should review the material again.</p>
                    </div>
                @else
                    <div class="alert alert-danger">
                        <h4>Needs Improvement</h4>
                        <p>You should revisit the material and try again.</p>
                    </div>
                @endif
            </div>
            
            <div class="mt-4">
                <a href="{{ route('questions.index') }}" class="btn btn-primary">Back to Questions</a>
                <a href="{{ route('exam.start') }}" class="btn btn-success ms-2">Take Another Exam</a>
            </div>
        </div>
    </div>
</div>
@endsection 