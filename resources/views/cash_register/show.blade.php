@extends('layouts.main')

@section('container')
<div class="container">
    <h1>Invoice #{{ $invoice->id }}</h1>
    <p>Date: {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
    <p>Cashier: {{ $invoice->user->name }}</p>

    <h3>Invoice Details</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->inventory->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Total Amount: Rp{{ number_format($invoice->total_amount, 2) }}</h4>

    <a href="#" class="btn btn-primary" onclick="window.print()">Print</a>
</div>
@endsection