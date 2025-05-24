@extends('layouts.master')
@section('title', $product->name)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            @if ($product->photo)
                @if (str_starts_with($product->photo, 'storage/'))
                    <img src="{{ asset($product->photo) }}" class="img-fluid rounded shadow" alt="{{ $product->name }}" style="max-height: 400px; width: auto;">
                @else
                    @php
                        $imagePath = 'uploads/products/' . $product->photo;
                        if (!file_exists(public_path($imagePath))) {
                            $imagePath = 'images/' . $product->photo;
                        }
                    @endphp
                    <img src="{{ asset($imagePath) }}" class="img-fluid rounded shadow" alt="{{ $product->name }}" style="max-height: 400px; width: auto;">
                @endif
            @else
                <div class="bg-light rounded p-5 text-center" style="height: 400px;">
                    <span class="text-muted">No image available</span>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <h1 class="mb-4">{{ $product->name }}</h1>
            
            <div class="mb-4">
                <h4 class="text-primary">${{ number_format($product->price, 2) }}</h4>
            </div>

            <div class="mb-4">
                <h5>Product Details</h5>
                <table class="table">
                    <tr>
                        <th>Model:</th>
                        <td>{{ $product->model }}</td>
                    </tr>
                    <tr>
                        <th>Code:</th>
                        <td>{{ $product->code }}</td>
                    </tr>
                    <tr>
                        <th>Availability:</th>
                        <td>
                            @if($product->isInStock())
                                <span class="badge bg-success">In Stock</span>
                                <span class="ms-2">{{ $product->getAvailableQuantity() }} available</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="mb-4">
                <h5>Description</h5>
                <p>{{ $product->description }}</p>
            </div>

            @auth
                @if(auth()->user()->isCustomer())
                    {{-- Favorite Button --}}
                    @php
                        $isFavorited = auth()->user()->favorites->contains($product->id);
                    @endphp

                    <form action="{{ route('products.favorite', $product->id) }}" method="POST" class="d-inline mb-3">
                        @csrf
                        @if ($isFavorited)
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning">Unfavorite</button>
                        @else
                            <button type="submit" class="btn btn-outline-primary">Favorite</button>
                        @endif
                    </form>

                    @if($product->isInStock())
                        <form action="{{ route('products_purchase', $product->id) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">Quantity</span>
                                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->getAvailableQuantity() }}" required>
                                        <button type="submit" class="btn btn-primary">Buy Now</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning mt-3">
                            This product is currently out of stock.
                        </div>
                    @endif
                @endif
            @endauth

            <div class="mt-4">
                <a href="{{ route('products_list') }}" class="btn btn-secondary">Back to Products</a>
            </div>
        </div>
    </div>
</div>
@endsection 