@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Add Credit to Your Account</h2>

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

    <div class="card mb-4">
        <div class="card-header">
            <h4>Your Information</h4>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
            <p><strong>Current Balance:</strong> ${{ number_format(auth()->user()->getCreditAmount(), 2) }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Add Credit</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('credits.self_add_store') }}">
                @csrf

                <div class="form-group mb-3">
                    <label for="amount">Amount ($)</label>
                    <input type="number" class="form-control" id="amount" name="amount" min="0.01" step="0.01" required>
                    @error('amount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="payment_method">Payment Method</label>
                    <select class="form-control" id="payment_method" name="payment_method" required>
                        <option value="">Select payment method</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                    @error('payment_method')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="description">Description (Optional)</label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="Personal deposit">
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Add Credit</button>
                <a href="{{ route('credits.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 