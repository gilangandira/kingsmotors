<!-- resources/views/dashboard.blade.php -->
@extends('layouts.main')
@section('container')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card"></div>
        <h1 class="mb-4">Inventory Dashboard</h1>
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Inventories</h5>
                        <p class="card-text">{{ number_format($totalInventories) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Quantity</h5>
                        <p class="card-text">{{ number_format($totalQuantity) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Value</h5>
                        <p class="card-text">RP. {{ number_format($totalValue, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Low Stock Items</h5>
                        <p class="card-text">{{ $lowStockInventories->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Inventory by Category</div>
                    <div class="card-body">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Inventory by Brands</div>
                    <div class="card-body">
                        <canvas id="locationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Recent Ingoing Items</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($recentIngoing as $ingoing)
                                <li class="list-group-item">
                                    {{ $ingoing->inventory->name }} - Quantity: {{ $ingoing->quantity }} - Date:
                                    {{ $ingoing->created_at->format('d M Y') }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Recent Outgoing Items</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($recentOutgoing as $outgoing)
                                <li class="list-group-item">
                                    {{ $outgoing->inventory->name }} - Quantity: {{ $outgoing->quantity }} - Date:
                                    {{ $outgoing->created_at->format('d M Y') }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Items Table -->
        @if($lowStockInventories->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">Items Low in Stock</div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockInventories as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->category->name }}</td>
                                            <td>{{ $item->location->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data for Category Chart
    var categoryLabels = {!! json_encode($inventoryByCategory->pluck('name')) !!};
    var categoryData = {!! json_encode($inventoryByCategory->pluck('inventories_count')) !!};

    // Data for Brand Chart
    var brandLabels = {!! json_encode($inventoryByBrand->pluck('name')) !!};
    var brandData = {!! json_encode($inventoryByBrand->pluck('inventories_count')) !!};

    // Category Pie Chart
    var ctx1 = document.getElementById('categoryChart').getContext('2d');
    var categoryChart = new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryData,
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#dc3545',
                    '#ffc107',
                    '#17a2b8',
                    '#6f42c1',
                    '#e83e8c',
                    '#fd7e14'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Location Doughnut Chart
    var ctx2 = document.getElementById('locationChart').getContext('2d');
    var brandChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: brandLabels,
            datasets: [{
                data: brandData,
                backgroundColor: [
                    '#17a2b8',
                    '#6f42c1',
                    '#e83e8c',
                    '#fd7e14',
                    '#007bff',
                    '#28a745',
                    '#dc3545',
                    '#ffc107'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endsection