@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="row">
    <div class="m-4 col-sm-6">
        @if(auth()->check() && auth()->user()->isEmployee())
        <div class="mb-3">
            <a href="{{ route('employee.customers') }}" class="btn btn-secondary">Back to Customer Management</a>
        </div>
        @endif
        
        <table class="table table-striped">
            <tr>
                <th>Name</th><td>{{$user->name}}</td>
            </tr>
            <tr>
                <th>Email</th><td>{{$user->email}}</td>
            </tr>
            @if($user->isCustomer())
            <tr>
                <th>Credit Balance</th>
                <td>
                    <span class="fs-4 fw-bold text-success">${{ number_format($user->getCreditAmount(), 2) }}</span>
                </td>
            </tr>
            <tr>
                <th>Customer Status</th>
                <td>
                    <span class="badge bg-primary">Active Customer</span>
                    <small class="text-muted ms-2">Customer since: {{ $user->created_at->format('M d, Y') }}</small>
                </td>
            </tr>
            @endif
        
            <tr>
                <th>User Type</th>
                <td>
                    @if($user->hasRole('admin'))
                        Admin
                    @elseif($user->hasRole('employee'))
                        Employee
                    @elseif($user->hasRole('customer'))
                        Customer
                    @endif
                </td>
            </tr>
            <tr>
                <th>Security Question</th>
                <td>{{ $user->security_question ? $user->security_question : 'Not set' }}</td>
            </tr>
            <tr>
                <th>Permissions</th>
                <td>
                    @foreach($permissions as $permission)
                        <span class="badge bg-success">{{$permission->display_name}}</span>
                    @endforeach
                </td>
            </tr>
        </table>

        <div class="row">
            <div class="col col-6">
            </div>
            @if(auth()->check() && (auth()->user()->hasPermissionTo('admin_users')||auth()->id()==$user->id))
            <div class="col col-4">
                <a class="btn btn-primary" href='{{route('edit_password', $user->id)}}'>Change Password</a>
            </div>
            @else
            <div class="col col-4">
            </div>
            @endif
            @if(auth()->check() && (auth()->user()->hasPermissionTo('edit_users')||auth()->id()==$user->id))
            <div class="col col-2">
                <a href="{{route('users_edit', $user->id)}}" class="btn btn-success form-control">Edit</a>
            </div>
            @endif
        </div>

        @if($user->isCustomer())
        <div class="mt-4">
            <h4>Customer Options</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('credits.index') }}" class="btn btn-primary">View Credit History</a>
                <a href="{{ route('purchase_history') }}" class="btn btn-info">View Purchase History</a>
                <a href="{{ route('products.favorites.list') }}" class="btn btn-warning">View Favorite Products</a>
            </div>
        </div>

        @if(isset($creditHistory) && $creditHistory->count() > 0)
        <div class="mt-4">
            <h4>Recent Credit Transactions</h4>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($creditHistory as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                        <td>${{ number_format($transaction->amount, 2) }}</td>
                        <td>{{ $transaction->type }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        @if(isset($purchaseHistory) && $purchaseHistory->count() > 0)
        <div class="mt-4">
            <h4>Recent Purchases</h4>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseHistory as $purchase)
                    <tr>
                        <td>{{ $purchase->created_at->format('M d, Y') }}</td>
                        <td>{{ $purchase->product->name }}</td>
                        <td>${{ number_format($purchase->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
