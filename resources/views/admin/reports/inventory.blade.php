@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Inventory Valuation Report</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/reports/dashboard') }}" class="text-decoration-none">BI Analytics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inventory Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <!-- Summary stats row -->
            <div class="row mb-4 text-center">
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm p-4 h-100" style="background: rgba(34, 197, 94, 0.08);">
                        <span class="text-secondary small fw-medium text-uppercase text-white">Current Inventory Value (at cost)</span>
                        <h2 class="fw-bold mt-1 text-success">${{ number_format($totalValuation, 2) }}</h2>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm p-4 h-100" style="background: rgba(59, 130, 246, 0.08);">
                        <span class="text-secondary small fw-medium text-uppercase text-white">Total Stock Units tracked</span>
                        <h2 class="fw-bold mt-1 text-white">{{ number_format($totalStockUnits) }} units</h2>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <div class="col-md-6">
                            <label for="supplier_id" class="form-label small fw-medium">Filter by Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-select">
                                <option value="">All Suppliers</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="stock_status" class="form-label small fw-medium">Filter stock Status</label>
                            <select name="stock_status" id="stock_status" class="form-select">
                                <option value="">All Batches</option>
                                <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock Batches (< 20)</option>
                                <option value="expired" {{ request('stock_status') === 'expired' ? 'selected' : '' }}>Expired Batches</option>
                            </select>
                        </div>
                        <div class="col-12 text-end mt-3">
                            <a href="{{ url('admin/reports/inventory') }}" class="btn btn-secondary me-2">Clear</a>
                            <button type="submit" class="btn btn-primary px-4">Filter Report</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Valued Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Stock Valuation Statements</h4>
                    <div class="d-flex gap-2 no-print">
                        <a href="{{ url('admin/reports/inventory/excel?' . http_build_query(request()->all())) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                            <i class="bi bi-printer me-1"></i> Print Statement
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Medicine Product</th>
                                    <th>Packaging</th>
                                    <th>Batch Number</th>
                                    <th>Expiry Date</th>
                                    <th>Available Quantity</th>
                                    <th>Purchase Rate</th>
                                    <th>Selling MRP</th>
                                    <th>Asset Valuation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value->medicine_name }}</td>
                                        <td>{{ $value->packaging }}</td>
                                        <td><span class="badge bg-secondary">{{ $value->batch_id }}</span></td>
                                        <td>
                                            @if($value->expiry_date < date('Y-m-d'))
                                                <span class="text-danger fw-bold">{{ date('M d, Y', strtotime($value->expiry_date)) }}</span>
                                            @else
                                                {{ date('M d, Y', strtotime($value->expiry_date)) }}
                                            @endif
                                        </td>
                                        <td class="fw-bold text-white">{{ $value->quantity }} units</td>
                                        <td>${{ number_format($value->rate, 2) }}</td>
                                        <td>${{ number_format($value->mrp, 2) }}</td>
                                        <td class="fw-bold text-success">${{ number_format($value->quantity * $value->rate, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-danger py-4">
                                            No stock records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        {!! $getRecord->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
