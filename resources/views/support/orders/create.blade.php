@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Order</h5>
                    <a href="{{ route('support.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('support.orders.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="customer_id" class="form-label">Customer</label>
                                <select name="customer_id" id="customer_id" class="form-select" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Order Items</h6>
                            </div>
                            <div class="card-body">
                                <div id="items-container">
                                    <div class="row mb-3 item-row">
                                        <div class="col-md-6">
                                            <label class="form-label">Product</label>
                                            <select name="items[0][product_id]" class="form-select product-select" required>
                                                <option value="">Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" name="items[0][quantity]" class="form-control quantity-input" min="1" value="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger d-block w-100 remove-item">Remove</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary" id="add-item">Add Another Item</button>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Create Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('items-container');
    const addButton = document.getElementById('add-item');
    let itemCount = 1;

    addButton.addEventListener('click', function() {
        const template = container.querySelector('.item-row').cloneNode(true);
        template.querySelectorAll('select, input').forEach(input => {
            input.value = '';
            input.name = input.name.replace('[0]', `[${itemCount}]`);
        });
        container.appendChild(template);
        itemCount++;
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            if (container.querySelectorAll('.item-row').length > 1) {
                e.target.closest('.item-row').remove();
            }
        }
    });
});
</script>
@endpush
@endsection 