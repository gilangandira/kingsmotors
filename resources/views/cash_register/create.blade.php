@extends('layouts.main')

@section('container')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <h1>Cash Register</h1>

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

            <form action="{{ route('cash_register.addToCart') }}" method="POST">
                @csrf
                <input type="text" name="barcode" placeholder="Barcode">
                <input type="text" name="name" placeholder="Name">
                <input type="number" name="quantity" min="1" placeholder="Quantity" required>
                <button type="submit" class="btn btn-primary">Add to Cart</button>
            </form>

            <h2>Cart</h2>

            @if(session()->has('cart') && !empty(session('cart')))
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('cart') as $inventoryId => $details)
                            <tr>
                                <td>{{ $details['name'] }}</td>
                                <td>{{ $details['quantity'] }}</td>
                                <td>{{ number_format($details['price'], 2) }}</td>
                                <td>
                                    <form action="{{ route('cash_register.removeFromCart', $inventoryId) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <form action="{{ route('cash_register.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Checkout</button>
                </form>
            @else
                <p>Your cart is empty.</p>
            @endif
        </div>
    </div>
</div>
@endsection