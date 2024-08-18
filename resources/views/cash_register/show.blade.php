<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/style/home.css">

    <style>
        /* CSS untuk media print */
        @media print {
            .print-hide {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header print-hide">Invoice List</div> <!-- Menyembunyikan header saat mencetak -->
                <div class="card-body">
                    <h1>KingsMotor #{{ $invoice->id }}</h1>
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

                    <a href="#" class="btn btn-primary print-hide" onclick="window.print()">Print</a>
                    <!-- Menyembunyikan tombol print saat mencetak -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>