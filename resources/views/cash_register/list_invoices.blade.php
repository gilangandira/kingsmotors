@extends('layouts.main')

@section('container')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">Invoice List</div>
            <div class="card-body">

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

                <!-- Formulir Pencarian dan Filter -->
                <form method="GET" action="{{ route('cash_register.listInvoices') }}">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="search" placeholder="Search..."
                            value="{{ $search }}">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="date" class="form-control" name="start_date" placeholder="Start Date"
                                value="{{ $startDate }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" name="end_date" placeholder="End Date"
                                value="{{ $endDate }}">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" type="submit">Filter by Date</button>
                        </div>
                    </div>
                </form>

                <!-- Tabel Daftar Invoice -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <a
                                    href="{{ route('cash_register.listInvoices', ['search' => $search, 'start_date' => $startDate, 'end_date' => $endDate, 'sort_by' => 'id', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                    Invoice ID
                                    @if ($sortBy === 'id')
                                        <span
                                            class="badge badge-secondary">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a
                                    href="{{ route('cash_register.listInvoices', ['search' => $search, 'start_date' => $startDate, 'end_date' => $endDate, 'sort_by' => 'total_amount', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                    Total Amount
                                    @if ($sortBy === 'total_amount')
                                        <span
                                            class="badge badge-secondary">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a
                                    href="{{ route('cash_register.listInvoices', ['search' => $search, 'start_date' => $startDate, 'end_date' => $endDate, 'sort_by' => 'created_at', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                    Created At
                                    @if ($sortBy === 'created_at')
                                        <span
                                            class="badge badge-secondary">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                <td>{{ $invoice->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('cash_register.show', $invoice->id) }}" class="btn btn-info">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination Links -->
                {{ $invoices->appends(['search' => $search, 'start_date' => $startDate, 'end_date' => $endDate, 'sort_by' => $sortBy, 'sort_direction' => $sortDirection])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection