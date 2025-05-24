@extends('layouts.master')
@section('title', 'Favorite Products')

@section('content')
    <div class="container">
        <h2>My Favorite Products</h2>

        @if ($favoriteProducts->isEmpty())
            <p>You haven't favorited any products yet.</p>
        @else
            <div class="row">
                @foreach ($favoriteProducts as $product)
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            @if ($product->photo)
                                @php
                                    $imagePath = 'uploads/products/' . $product->photo;
                                    if (!file_exists(public_path($imagePath))) {
                                        $imagePath = 'images/' . $product->photo;
                                    }
                                @endphp
                                @if (str_starts_with($product->photo, 'storage/'))
                                    <img src="{{ asset($product->photo) }}" class="bd-placeholder-img card-img-top" width="100%" height="225" alt="{{ $product->name }}">
                                @else
                                    <img src="{{ asset($imagePath) }}" class="bd-placeholder-img card-img-top" width="100%" height="225" alt="{{ $product->name }}">
                                @endif
                            @else
                                <div class="bg-light rounded-top p-5 text-center" style="height: 225px;">
                                    <span class="text-muted">No image available</span>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text text-primary">${{ number_format($product->price, 2) }}</p>
                                <p class="card-text small text-muted">{{ Str::limit($product->description, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{ route('products_show', $product) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                        <form action="{{ route('products.favorite', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                        </form>
                                    </div>
                                    <small class="text-muted">
                                        @if($product->isInStock())
                                            <span class="badge bg-success">In Stock</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection 