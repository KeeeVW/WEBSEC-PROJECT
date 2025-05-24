@extends('layouts.master')
@section('title', 'Login')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
      
      @if(session('error'))
        <div class="alert alert-danger">
          {{ session('error') }}
        </div>
      @endif

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
          <a href="{{ route('password.request') }}" class="ms-2">Forgot Password?</a>
        </div>
      </form>
      
      <div class="login-divider my-4">
        <span>OR</span>
      </div>
      
      <!-- Social Login Buttons -->
      <div class="mb-3">
        <a href="{{ route('auth.github') }}" class="btn btn-github w-100 mb-2">
          <i class="fab fa-github me-2"></i> Login with GitHub
        </a>
        <a href="{{ route('auth.google') }}" class="btn btn-google w-100 mb-2">
          <i class="fab fa-google me-2"></i> Login with Google
        </a>
        <a href="{{ route('auth.linkedin') }}" class="btn btn-linkedin w-100 mb-2">
          <i class="fab fa-linkedin me-2"></i> Login with LinkedIn
        </a>
        <a href="{{ route('auth.twitter') }}" class="btn btn-twitter w-100">
          <i class="fab fa-twitter me-2"></i> Login with Twitter
        </a>
      </div>
      
      <div class="text-center">
        <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
        @if(auth()->check() && !auth()->user()->hasVerifiedEmail())
          <p class="mt-2">
            <a href="{{ route('verification.resend') }}" class="text-warning">
              Click here to resend verification email
            </a>
          </p>
        @endif
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
  .btn-google {
    background-color: #db4437;
    color: white;
    padding: 10px 15px;
    font-size: 16px;
  }
  .btn-google:hover {
    background-color: #c53929;
    color: white;
  }
  .btn-linkedin {
    background-color: #0077b5;
    color: white;
    padding: 10px 15px;
    font-size: 16px;
  }
  .btn-linkedin:hover {
    background-color: #006699;
    color: white;
  }
  .btn-twitter {
    background-color: #1da1f2;
    color: white;
    padding: 10px 15px;
    font-size: 16px;
  }
  .btn-twitter:hover {
    background-color: #1a91da;
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
