@extends('layouts.master')

@section('content')
<h2>Start Exam</h2>
<form action="{{ route('exam.submit') }}" method="POST">
    @csrf
    @foreach($questions as $q)
        <div>
            <p><strong>{{ $q->question }}</strong></p>
            <label><input type="radio" name="{{ $q->id }}" value="A"> {{ $q->option_a }}</label><br>
            <label><input type="radio" name="{{ $q->id }}" value="B"> {{ $q->option_b }}</label><br>
            <label><input type="radio" name="{{ $q->id }}" value="C"> {{ $q->option_c }}</label><br>
            <label><input type="radio" name="{{ $q->id }}" value="D"> {{ $q->option_d }}</label><br>
        </div><hr>
    @endforeach
    <button type="submit">Submit Exam</button>
</form>
@endsection
