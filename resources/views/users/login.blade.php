@extends('layouts.master')
@section('title', 'Login')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <form action="{{route('do_login')}}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong>Error!</strong> {{$error}}
        </div>
        @endforeach
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
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="{{ route('forgot_password') }}" class="ms-2">Forgot Password?</a>
      </div>
    </form>
    
    <div class="login-divider my-4">
      <span>OR</span>
    </div>
    
    <!-- GitHub Login Button -->
    <div class="mb-3">
      <a href="{{ route('auth.github') }}" class="btn btn-github w-100">
        <i class="fab fa-github me-2"></i> Login with GitHub
      </a>
    </div>
    
    <div class="text-center">
      <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
    </div>
    </div>
  </div>
</div>

<style>
  .btn-github {
    background-color: #24292e;
    color: white;
    padding: 10px 15px;
    font-size: 16px;
  }
  .btn-github:hover {
    background-color: #1a1e22;
    color: white;
  }
  .login-divider {
    position: relative;
    text-align: center;
    margin: 20px 0;
  }
  .login-divider:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background-color: #ddd;
    z-index: 1;
  }
  .login-divider span {
    position: relative;
    background-color: #fff;
    padding: 0 15px;
    z-index: 2;
    font-weight: bold;
  }
</style>
@endsection
