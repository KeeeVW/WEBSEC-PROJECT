@extends('layouts.master')

@section('title', 'Purchase History')

@section('content')
<div class="container">
    <h2>Your Purchase History</h2>

    <div class="mb-3">
        <a href="{{ route('products_list') }}" class="btn btn-primary">Back to Products</a>
        <a href="{{ route('credits.index') }}" class="btn btn-secondary">View Credits</a>
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

    <div class="card">
        <div class="card-header">
            <h4>Purchased Products</h4>
        </div>
        <div class="card-body">
            @if($purchases->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    @if($purchase->product)
                                        {{ $purchase->product->name }}
                                    @else
                                        <em>Product no longer available</em>
                                    @endif
                                </td>
                                <td>{{ $purchase->quantity }}</td>
                                <td>${{ number_format($purchase->price_paid, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total Spent:</th>
                            <th>${{ number_format($purchases->sum('price_paid'), 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            @else
                <p>You haven't made any purchases yet.</p>
                <a href="{{ route('products_list') }}" class="btn btn-primary">Browse Products</a>
            @endif
        </div>
    </div>
</div>
@endsection 