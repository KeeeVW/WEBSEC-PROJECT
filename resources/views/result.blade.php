@extends('layouts.master')

@section('content')
<h2>Exam Result</h2>
<p>You scored {{ $score }} out of {{ $total }}</p>
@endsection
