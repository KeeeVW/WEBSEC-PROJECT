@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>MCQ Exam</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('exam.submit') }}" id="examForm">
                @csrf
                <div class="alert alert-info">
                    <p><strong>Instructions:</strong> Select the best answer for each question. You have {{ count($questions) }} questions to answer.</p>
                </div>
                
                @foreach($questions as $index => $question)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5>Question {{ $index + 1 }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">{{ $question->question }}</p>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="{{ $question->id }}" id="q{{ $question->id }}_a" value="A" required>
                                <label class="form-check-label" for="q{{ $question->id }}_a">
                                    A. {{ $question->option_a }}
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="{{ $question->id }}" id="q{{ $question->id }}_b" value="B">
                                <label class="form-check-label" for="q{{ $question->id }}_b">
                                    B. {{ $question->option_b }}
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="{{ $question->id }}" id="q{{ $question->id }}_c" value="C">
                                <label class="form-check-label" for="q{{ $question->id }}_c">
                                    C. {{ $question->option_c }}
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="{{ $question->id }}" id="q{{ $question->id }}_d" value="D">
                                <label class="form-check-label" for="q{{ $question->id }}_d">
                                    D. {{ $question->option_d }}
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('questions.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 