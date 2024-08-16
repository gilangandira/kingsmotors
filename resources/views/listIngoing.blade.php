@extends('layouts.main')

<head>
    <link rel="stylesheet" href="/style/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

@section('container')

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">Inventory List</div>
            <div class="card-body">

                <form action="{{ route('inventory.listIngoingItem') }}" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search Inventory"
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-success">Search</button>
                </form>

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table vertical-align">
                        <thead>
                            <tr class="align-middle">
                                <th class="align-middle text-center" scope="col">#</th>
                                <th class="align-middle text-center" scope="col">
                                    <a href="{{ route('inventory.listIngoingItem', ['sort' => 'name', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">
                                        Name
                                        @if($sort == 'name')
                                            <i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle text-center" scope="col">
                                    <a href="{{ route('inventory.listIngoingItem', ['sort' => 'quantity', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">
                                        Quantity
                                        @if($sort == 'quantity')
                                            <i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle text-center" scope="col">
                                    <a href="{{ route('inventory.listIngoingItem', ['sort' => 'price', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">
                                        Price
                                        @if($sort == 'price')
                                            <i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle text-center" scope="col">
                                    <a href="{{ route('inventory.listIngoingItem', ['sort' => 'category.name', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">
                                        Category
                                        @if($sort == 'category.name')
                                            <i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle text-center" scope="col">
                                    <a href="{{ route('inventory.listIngoingItem', ['sort' => 'brand.name', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">
                                        Brand
                                        @if($sort == 'brand.name')
                                            <i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle text-center" scope="col">
                                    <a href="{{ route('inventory.listIngoingItem', ['sort' => 'location.name', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">
                                        Location
                                        @if($sort == 'location.name')
                                            <i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle text-center" scope="col">Ingoing Item</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventories as $inventory)
                                <tr class="align-middle" scope="row">
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $inventory->name }}</td>
                                    <td class="align-middle">{{ $inventory->quantity }}</td>
                                    <td class="align-middle text-center">
                                        {{ isset($inventory->price) ? 'Rp. ' . number_format($inventory->price, 0, ',', '.') : 'N/A' }}
                                    </td>
                                    <td class="align-middle">{{ $inventory->category->name }}</td>
                                    <td class="align-middle">{{ $inventory->brand->name }}</td>
                                    <td class="align-middle">{{ $inventory->location->name }}</td>
                                    <td class="align-middle">
                                        <form action="{{ route('inventory.storeIngoing', $inventory->id) }}" method="POST">
                                            @csrf
                                            <input type="number" class="form-control" id="quantity-{{ $inventory->id }}"
                                                name="quantity" min="1" max="{{ $inventory->quantity }}" required>
                                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center">
                    {{ $inventories->appends(['sort' => $sort, 'direction' => $direction])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection