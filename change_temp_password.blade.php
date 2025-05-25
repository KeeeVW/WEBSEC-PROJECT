@extends('layouts.master')
@section('title', 'Change Temporary Password')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <h3 class="card-title">Change Temporary Password</h3>
      <p>You have logged in with a temporary password. Please create a new password.</p>
      
      <form action="{{ route('update_temp_password') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
          @foreach($errors->all() as $error)
            <div class="alert alert-danger">
              <strong>Error!</strong> {{$error}}
            </div>
          @endforeach
        </div>
        
        <div class="form-group mb-3">
          <label for="password" class="form-label">New Password:</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <div class="form-group mb-3">
          <label for="password_confirmation" class="form-label">Confirm New Password:</label>
          <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Change Password</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection 