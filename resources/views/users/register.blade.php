@extends('layouts.master')
@section('title', 'Register')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <form action="{{route('do_register')}}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
      @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong>Error!</strong> {{$error}}
        </div>
      @endforeach
      <div class="form-group mb-2">
        <label for="code" class="form-label">Name:</label>
        <input type="text" class="form-control" placeholder="name" name="name" required>
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Email:</label>
        <input type="email" class="form-control" placeholder="email" name="email" required>
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Password:</label>
        <input type="password" class="form-control" placeholder="password" name="password" required>
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Password Confirmation:</label>
        <input type="password" class="form-control" placeholder="Confirmation" name="password_confirmation" required>
      </div>
      <div class="form-group mb-2">
        <label for="security_question" class="form-label">Security Question:</label>
        <select class="form-select" name="security_question" required>
          <option value="">Select a security question</option>
          <option value="What was your childhood nickname?">What was your childhood nickname?</option>
          <option value="What is the name of your first pet?">What is the name of your first pet?</option>
          <option value="In what city were you born?">In what city were you born?</option>
          <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
          <option value="What was your favorite food as a child?">What was your favorite food as a child?</option>
        </select>
      </div>
      <div class="form-group mb-2">
        <label for="security_answer" class="form-label">Answer:</label>
        <input type="text" class="form-control" placeholder="Answer to security question" name="security_answer" required>
      </div>
      <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Register</button>
      </div>
    </form>
    </div>
  </div>
</div>
@endsection
