@extends('layouts.master')

@section('title', 'Employee Dashboard')
@section('content')
<div class="container">
    <div class="row mt-2">
        <div class="col-12">
            <h1>Employee Dashboard</h1>
        </div>
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

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-warning text-dark mb-4">
                <div class="card-body">
                    <h5 class="card-title">Customer Management</h5>
                    <p class="card-text">Manage customer accounts and credits</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('employee.customers') }}" class="btn btn-dark">View All Customers</a>
                        <a href="{{ route('employee_customers.index') }}" class="btn btn-dark">My Customers ({{ $myCustomerCount }})</a>
                        <a href="{{ route('credits.index') }}" class="btn btn-dark">Manage Credits</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-warning text-dark mb-4">
                <div class="card-body">
                    <h5 class="card-title">Product Management</h5>
                    <p class="card-text">Add, edit, and delete products</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('products_list') }}" class="btn btn-dark">View Products</a>
                        <a href="{{ route('products_edit') }}" class="btn btn-dark">Add New Product</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-warning text-dark mb-4">
                <div class="card-body">
                    <h5 class="card-title">Statistics</h5>
                    <p class="card-text">System statistics</p>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Customers
                            <span class="badge bg-dark rounded-pill">{{ $customerCount }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            My Managed Customers
                            <span class="badge bg-dark rounded-pill">{{ $myCustomerCount }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 