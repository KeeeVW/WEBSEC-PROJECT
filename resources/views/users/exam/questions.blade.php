@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h2>Exam Questions</h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('questions.create') }}" class="btn btn-primary">Add New Question</a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Options</th>
                    <th>Correct Answer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $index => $question)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $question->question }}</td>
                        <td>
                            <strong>A:</strong> {{ $question->option_a }}<br>
                            <strong>B:</strong> {{ $question->option_b }}<br>
                            <strong>C:</strong> {{ $question->option_c }}<br>
                            <strong>D:</strong> {{ $question->option_d }}
                        </td>
                        <td>{{ $question->correct_answer }}</td>
                        <td>
                            <a href="{{ route('questions.edit', $question) }}" class="btn btn-sm btn-info">Edit</a>
                            <form method="POST" action="{{ route('questions.destroy', $question) }}" style="display:inline;">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No questions available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('exam.start') }}" class="btn btn-success">Start Exam</a>
    </div>
</div>
@endsection 