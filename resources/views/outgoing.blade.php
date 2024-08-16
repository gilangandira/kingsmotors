@extends('layouts.main')

<head>
    <link rel="stylesheet" href="/style/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy9qZL9KpLxR19L84jXy5b3/4btiyWgEz4dH5l0P"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-6m5g5I6Q0F6ST28iHboXsBiw4q8qOH6iWo9hUURr5F4C6X4SK0TPzDkeN5ThDgz4"
        crossorigin="anonymous"></script>
</head>
@section('container')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">Outgoing Item</div>
            <div class="card-body">
                <h1>Outgoing Items</h1>
                <form action="{{ route('inventory.showOutgoingForm') }}" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search Inventory"
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-success">Search</button>
                </form>


                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                


                <!-- Pesan sukses -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Filter Form -->
                <form method="GET" action="{{ route('inventory.showOutgoingForm') }}">
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ old('start_date', $startDate) }}">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ old('end_date', $endDate) }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </form>

                <!-- Tabel Outgoing Items -->
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th class="align-middle text-center" scope="'col">#</th>
                            <th class="align-middle text-center"><a
                                    href="{{ route('inventory.showOutgoingForm', ['sort' => 'quantity', 'direction' => $direction == 'asc' ? 'desc' : 'asc', 'start_date' => $startDate, 'end_date' => $endDate]) }}">Quantity</a>
                            </th>
                            <th class="align-middle text-center"><a
                                    href="{{ route('inventory.showOutgoingForm', ['sort' => 'created_at', 'direction' => $direction == 'asc' ? 'desc' : 'asc', 'start_date' => $startDate, 'end_date' => $endDate]) }}">Date</a>
                            </th>
                            <th class="align-middle text-center">Inventory Item</th>
                            <th class="align-middle text-center">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outgoingItems as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->created_at->addHours(7)->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $item->inventory->name ?? 'N/A' }}</td>
                                <td class="align-middle text-center">
                                    {{ isset($item->inventory->price) ? 'Rp. ' . number_format($item->inventory->price, 0, ',', '.') : 'N/A' }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">{{ $outgoingItems->links('pagination::bootstrap-4') }}</div>

            </div>
        </div>
    </div>
</div>




@endsection