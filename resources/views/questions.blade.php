@extends('layouts.master')

@section('content')
<h2>MCQ Questions</h2>
<a href="{{ route('questions.create') }}">Add Question</a>
<table>
    <tr><th>Question</th><th>Actions</th></tr>
    @foreach($questions as $q)
    <tr>
        <td>{{ $q->question }}</td>
        <td>
            <a href="{{ route('questions.edit', $q) }}">Edit</a>
            <form action="{{ route('questions.destroy', $q) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
