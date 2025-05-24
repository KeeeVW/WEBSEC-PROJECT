@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h2>Order Management</h2>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->product->name }}</td>
                            <td>${{ number_format($order->amount, 2) }}</td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                    View Details
                                </button>
                            </td>
                        </tr>

                        <!-- Order Modal -->
                        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Order #{{ $order->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Customer:</strong> {{ $order->user->name }}</p>
                                        <p><strong>Product:</strong> {{ $order->product->name }}</p>
                                        <p><strong>Amount:</strong> ${{ number_format($order->amount, 2) }}</p>
                                        <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                                        <p><strong>Status:</strong> {{ $order->status }}</p>
                                        
                                        @if($order->shipping_address)
                                        <hr>
                                        <h6>Shipping Information</h6>
                                        <p>{{ $order->shipping_address }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection 