@extends('layouts.master')
@section('title', 'Users')
@section('content')
<div class="row mt-2">
    <div class="col col-8">
        @if(auth()->check() && auth()->user()->isEmployee() && !auth()->user()->isAdmin())
            <h1>Customers</h1>
        @else
            <h1>Users</h1>
        @endif
    </div>
    @if(auth()->check() && auth()->user()->isAdmin())
    <div class="col col-4">
        <a href="{{ route('users_create_employee') }}" class="btn btn-success">Create Employee Account</a>
    </div>
    @endif
</div>

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

<form>
    <div class="row">
        <div class="col col-sm-2">
            <input name="keywords" type="text"  class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}" />
        </div>
        <div class="col col-sm-1">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="col col-sm-1">
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>
    </div>
</form>

@if(!empty(request()->keywords))
   <div class="card mt-2">
    <div class="card-body">
       view search results: <span>{{request()->keywords}}</span>
    </div>
  </div>
@endif


<div class="card mt-2">
  <div class="card-body">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Roles</th>
          <th scope="col"></th>
        </tr>
      </thead>
      @foreach($users as $user)
      <tr>
        <td scope="col">{{$user->id}}</td>
        <td scope="col">{{$user->name}}</td>
        <td scope="col">{{$user->email}}</td>
        <td scope="col">
          @foreach($user->roles as $role)
            @if($role->name == 'admin')
              <span class="badge bg-danger">Admin</span>
            @elseif($role->name == 'employee')
              <span class="badge bg-warning text-dark">Employee</span>
            @elseif($role->name == 'customer')
              <span class="badge bg-success">Customer</span>
            @else
              <span class="badge bg-info">{{ $role->name }}</span>
            @endif
          @endforeach
        </td>
        <td scope="col">
          @if($user->hasRole('customer'))
            <a class="btn btn-success btn-sm" href="{{ route('credits.add_form', $user->id) }}">Add Credit</a>
          @endif
          @can('edit_users')
          <a class="btn btn-primary" href='{{route('users_edit', [$user->id])}}'>Edit</a>
          @endcan
          @can('admin_users')
          <a class="btn btn-primary" href='{{route('edit_password', [$user->id])}}'>Change Password</a>
          @endcan
          @if(auth()->user()->isAdmin() || auth()->user()->isEmployee())
          <a href="{{ route('users_toggle_block', $user->id) }}" class="btn {{ $user->isBlocked() ? 'btn-warning' : 'btn-danger' }} btn-sm">
            {{ $user->isBlocked() ? 'Unblock' : 'Block' }}
          </a>
          @endif
          @can('delete_users')
          <a class="btn btn-danger" href='{{route('users_delete', [$user->id])}}'>Delete</a>
          @endcan
        </td>
      </tr>
      @endforeach
    </table>
  </div>
</div>


@endsection
