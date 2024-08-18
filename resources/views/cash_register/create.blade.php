@extends('layouts.main')

@section('container')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">Shopping Cart</div>
            <div class="card-body">
                <!-- Tampilkan Pesan Sukses dan Error -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Form Pencarian atau Input Barcode -->
                <form action="{{ route('cash_register.addToCart') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="barcode">Barcode or Name</label>
                        <input type="text" id="barcode" name="barcode" class="form-control"
                            placeholder="Enter barcode or name">
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>

                <!-- Daftar Item di Cart -->
                @if(session('cart') && count(session('cart')) > 0)
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('cart') as $id => $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>
                                        <input type="number" name="cart[{{ $id }}][quantity]"
                                            value="{{ $item['quantity'] }}" min="1" max="{{ $item['quantity'] }}"
                                            class="form-control update-quantity" data-id="{{ $id }}">
                                    </td>
                                    <td>{{ number_format($item['price'], 2) }}</td>
                                    <td>{{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('cash_register.removeFromCart', $id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No items in cart.</p>
                @endif

                <!-- Tombol Checkout -->
                @if(session('cart') && count(session('cart')) > 0)
    <form action="{{ route('cash_register.checkout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success mt-3">Checkout</button>
    </form>
@endif

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const updateQuantityInputs = document.querySelectorAll('.update-quantity');

    updateQuantityInputs.forEach(input => {
        input.addEventListener('change', function () {
            const id = this.dataset.id;
            const quantity = this.value;

            fetch('{{ route('cash_register.updateCart') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id: id,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to reflect the updated cart
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to update cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});

</script>
@endsection
