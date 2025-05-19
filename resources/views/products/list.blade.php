@extends('layouts.master')
@section('title', 'Test Page')
@section('content')
<div class="row mt-2">
    <div class="col col-10">
        <h1>Products</h1>
    </div>
    <div class="col col-2">
        @if(auth()->check() && (auth()->user()->isEmployee() || auth()->user()->isAdmin() || auth()->user()->hasPermissionTo('add_products') || auth()->user()->hasPermissionTo('manage_products')))
        <a href="{{route('products_edit')}}" class="btn btn-success form-control">Add Product</a>
        @endif
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

<form>
    <div class="row">
        <div class="col col-sm-2">
            <input name="keywords" type="text"  class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}" />
        </div>
        <div class="col col-sm-2">
            <input name="min_price" type="numeric"  class="form-control" placeholder="Min Price" value="{{ request()->min_price }}"/>
        </div>
        <div class="col col-sm-2">
            <input name="max_price" type="numeric"  class="form-control" placeholder="Max Price" value="{{ request()->max_price }}"/>
        </div>
        <div class="col col-sm-2">
            <select name="order_by" class="form-select">
                <option value="" {{ request()->order_by==""?"selected":"" }} disabled>Order By</option>
                <option value="name" {{ request()->order_by=="name"?"selected":"" }}>Name</option>
                <option value="price" {{ request()->order_by=="price"?"selected":"" }}>Price</option>
            </select>
        </div>
        <div class="col col-sm-2">
            <select name="order_direction" class="form-select">
                <option value="" {{ request()->order_direction==""?"selected":"" }} disabled>Order Direction</option>
                <option value="ASC" {{ request()->order_direction=="ASC"?"selected":"" }}>ASC</option>
                <option value="DESC" {{ request()->order_direction=="DESC"?"selected":"" }}>DESC</option>
            </select>
        </div>
        <div class="col col-sm-1">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="col col-sm-1">
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>
    </div>
</form>


@foreach($products as $product)
    <div class="card mt-2">
        <div class="card-body">
            <div class="row">
                <div class="col col-sm-12 col-lg-4">
                    <img src="{{asset("images/$product->photo")}}" class="img-thumbnail" alt="{{$product->name}}" width="100%">
                </div>
                <div class="col col-sm-12 col-lg-8 mt-3">
                    <div class="row mb-2">
					    <div class="col-8">
					        <h3>{{$product->name}}</h3>
					    </div>
					    <div class="col col-2">
                            @if(auth()->check() && (auth()->user()->isEmployee() || auth()->user()->isAdmin() || auth()->user()->hasPermissionTo('edit_products') || auth()->user()->hasPermissionTo('manage_products')))
					        <a href="{{route('products_edit', $product->id)}}" class="btn btn-success form-control">Edit</a>
                            @endif
					    </div>
					    <div class="col col-2">
                            @if(auth()->check() && (auth()->user()->isEmployee() || auth()->user()->isAdmin() || auth()->user()->hasPermissionTo('delete_products') || auth()->user()->hasPermissionTo('manage_products')))
					        <a href="{{route('products_delete', $product->id)}}" class="btn btn-danger form-control">Delete</a>
                            @endif
					    </div>
					</div>

                    <table class="table table-striped">
                        <tr><th width="20%">Name</th><td>{{$product->name}}</td></tr>
                        <tr><th>Model</th><td>{{$product->model}}</td></tr>
                        <tr><th>Code</th><td>{{$product->code}}</td></tr>
                        <tr><th>Price</th><td>${{ number_format($product->price, 2) }}</td></tr>
                        <tr><th>Description</th><td>{{$product->description}}</td></tr>
                        <tr>
                            <th>Availability</th>
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
                    
                    @auth
                        @if(auth()->user()->isCustomer())
                            {{-- Favorite Button --}}
                            @php
                                $isFavorited = auth()->user()->favorites->contains($product->id);
                            @endphp

                            <form action="{{ route('products.favorite', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @if ($isFavorited)
                                    @method('DELETE') {{-- Use DELETE method for unfavorite --}}
                                    <button type="submit" class="btn btn-warning btn-sm">Unfavorite</button>
                                @else
                                    <button type="submit" class="btn btn-outline-primary btn-sm">Favorite</button>
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
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection