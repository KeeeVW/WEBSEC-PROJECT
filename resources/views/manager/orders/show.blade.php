@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order #{{ $order->id }}</h5>
                    <a href="{{ route('manager.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Customer Information</h6>
                            @if($order->user)
                                <p class="mb-1"><strong>Name:</strong> {{ $order->user->name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                            @else
                                <p class="mb-1 text-muted">Customer information not available</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Order Information</h6>
                            <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                            <p class="mb-1"><strong>Status:</strong> 
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : ($order->status === 'processing' ? 'warning' : 'info')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->status !== 'completed' && $order->status !== 'cancelled')
                        <form action="{{ route('manager.orders.update', $order) }}" method="POST" class="mb-4">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Update Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $order->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Order</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 