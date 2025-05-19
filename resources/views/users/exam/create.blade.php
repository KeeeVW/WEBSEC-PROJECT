@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Add New Question</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('questions.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="question" class="form-label">Question</label>
                    <textarea class="form-control @error('question') is-invalid @enderror" id="question" name="question" rows="3" required>{{ old('question') }}</textarea>
                    @error('question')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="option_a" class="form-label">Option A</label>
                        <input type="text" class="form-control @error('option_a') is-invalid @enderror" id="option_a" name="option_a" value="{{ old('option_a') }}" required>
                        @error('option_a')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="option_b" class="form-label">Option B</label>
                        <input type="text" class="form-control @error('option_b') is-invalid @enderror" id="option_b" name="option_b" value="{{ old('option_b') }}" required>
                        @error('option_b')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="option_c" class="form-label">Option C</label>
                        <input type="text" class="form-control @error('option_c') is-invalid @enderror" id="option_c" name="option_c" value="{{ old('option_c') }}" required>
                        @error('option_c')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="option_d" class="form-label">Option D</label>
                        <input type="text" class="form-control @error('option_d') is-invalid @enderror" id="option_d" name="option_d" value="{{ old('option_d') }}" required>
                        @error('option_d')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="correct_answer" class="form-label">Correct Answer</label>
                    <select class="form-select @error('correct_answer') is-invalid @enderror" id="correct_answer" name="correct_answer" required>
                        <option value="">Select correct answer</option>
                        <option value="A" {{ old('correct_answer') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('correct_answer') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ old('correct_answer') == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ old('correct_answer') == 'D' ? 'selected' : '' }}>D</option>
                    </select>
                    @error('correct_answer')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('questions.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Save Question</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 