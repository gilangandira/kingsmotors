@extends('layouts.main')

<head>
    <link rel="stylesheet" href="/style/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

</head>
@section('container')

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">Inventory List</div>
            <div class="card-body">
                <form action="{{ route('inventory.showInventories') }}" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search Inventory"
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-success">Search</button>
                    <div class="p-2 g-col-6"><a href="{{ route('inventory.create') }}" class="btn btn-success">
                            Add Item
                        </a></div>

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
                                <th class="align-middle text-center" scope="'col">#</th>
                                <th class="align-middle text-center" scope="col"><a
                                        href="{{ route('inventory.showInventories', ['sort' => 'name', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">Name @if($sort == 'name') <i
                                            class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif</a></th>
                                <th class="align-middle text-center" scope="col"><a
                                        href="{{ route('inventory.showInventories', ['sort' => 'quantity', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">Quantity @if($sort == 'quantity') <i
                                            class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif</a></th>
                                <th class="align-middle text-center" scope="col"><a
                                        href="{{ route('inventory.showInventories', ['sort' => 'price', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">Price @if($sort == 'price') <i
                                            class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif</a></th>
                                <th class="align-middle text-center" scope="col"><a
                                        href="{{ route('inventory.showInventories', ['sort' => 'category.name', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">Category @if($sort == 'category.name') <i
                                            class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif</a></th>
                                <th class="align-middle text-center" scope="col"><a
                                        href="{{ route('inventory.showInventories', ['sort' => 'brand.name', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">Brand @if($sort == 'brand.name') <i
                                            class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif</a></th>
                                <th class="align-middle text-center" scope="col"><a
                                        href="{{ route('inventory.showInventories', ['sort' => 'location.name', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">Location @if($sort == 'location.name') <i
                                            class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif</a></th>
                                <th class="align-middle text-center" scope="col"><a
                                        href="{{ route('inventory.showInventories', ['sort' => 'barcode', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                        class="sortable">Barcode @if($sort == 'barcode') <i
                                            class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif</a></th>
                                <th class="align-middle text-center" scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventories as $inventory)
                                <tr class="align-middle text-center" scope="row">
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $inventory->name }}</td>
                                    <td class="align-middle text-center">{{ $inventory->quantity }}</td>
                                    <td class="align-middle text-center">
                                        {{ isset($inventory->price) ? 'Rp. ' . number_format($inventory->price, 0, ',', '.') : 'N/A' }}
                                    </td>
                                    <td class="align-middle text-center">{{ $inventory->category->name }}</td>
                                    <td class="align-middle text-center">{{ $inventory->brand->name }}</td>
                                    <td class="align-middle text-center">{{ $inventory->location->name }}</td>
                                    <td class="align-middle text-center"><svg class="barcode" jsbarcode-format="CODE128"
                                            jsbarcode-value="{{ $inventory->barcode }}" jsbarcode-textmargin="0"
                                            jsbarcode-fontoptions="bold"></svg></td>
                                    <td class="align-middle"><a href="{{ route('inventory.edit', $inventory->id) }}"
                                            class="btn btn-warning">
                                            Edit
                                        </a>
                                        <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST"
                                            class="mt-4">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this inventory?')">Delete</button>
                                    </td>
                                    </form>
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        JsBarcode(".barcode").init();
    });
</script>

@endsection