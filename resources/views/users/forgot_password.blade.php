@extends('layouts.master')
@section('title', 'Forgot Password')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <h3 class="card-title">Forgot Password</h3>
      <p>Please enter your email to reset your password</p>
      
      <form action="{{ route('process_forgot_password') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
          @foreach($errors->all() as $error)
            <div class="alert alert-danger">
              <strong>Error!</strong> {{$error}}
            </div>
          @endforeach
        </div>
        
        @if(session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
        @endif
        
        <div class="form-group mb-3">
          <label for="email" class="form-label">Email Address:</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="{{ route('login') }}" class="btn btn-link">Back to Login</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection 