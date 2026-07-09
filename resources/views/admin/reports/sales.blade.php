@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Sales Performance Report</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/reports/dashboard') }}" class="text-decoration-none">BI Analytics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <!-- Summary stats widget -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 text-center" style="background: rgba(34, 197, 94, 0.08);">
                        <span class="text-secondary small fw-medium text-uppercase text-white">Net Sales Revenue</span>
                        <h2 class="fw-bold mt-1 text-success">${{ number_format($netRevenue, 2) }}</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 text-center" style="background: rgba(59, 130, 246, 0.08);">
                        <span class="text-secondary small fw-medium text-uppercase text-white">Gross Sales Total</span>
                        <h2 class="fw-bold mt-1 text-white">${{ number_format($grossSales, 2) }}</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 text-center" style="background: rgba(239, 68, 68, 0.08);">
                        <span class="text-secondary small fw-medium text-uppercase text-white">Discounts Claimed</span>
                        <h2 class="fw-bold mt-1 text-danger">-${{ number_format($discounts, 2) }}</h2>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3" id="filterForm">
                        <!-- Date Preset Buttons -->
                        <div class="col-12 mb-2">
                            <span class="small fw-semibold text-white d-block mb-2">Date Presets</span>
                            <div class="btn-group w-100" role="group">
                                <a href="{{ request()->fullUrlWithQuery(['date_preset' => 'today']) }}" class="btn btn-outline-secondary {{ request('date_preset') === 'today' ? 'active' : '' }}">Today</a>
                                <a href="{{ request()->fullUrlWithQuery(['date_preset' => 'yesterday']) }}" class="btn btn-outline-secondary {{ request('date_preset') === 'yesterday' ? 'active' : '' }}">Yesterday</a>
                                <a href="{{ request()->fullUrlWithQuery(['date_preset' => 'this_week']) }}" class="btn btn-outline-secondary {{ request('date_preset') === 'this_week' ? 'active' : '' }}">This Week</a>
                                <a href="{{ request()->fullUrlWithQuery(['date_preset' => 'this_month']) }}" class="btn btn-outline-secondary {{ request('date_preset') === 'this_month' ? 'active' : '' }}">This Month</a>
                                <a href="{{ request()->fullUrlWithQuery(['date_preset' => 'this_year']) }}" class="btn btn-outline-secondary {{ request('date_preset') === 'this_year' ? 'active' : '' }}">This Year</a>
                            </div>
                        </div>

                        <!-- Custom Date Range -->
                        <div class="col-md-3">
                            <label for="start_date" class="form-label small fw-medium">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label small fw-medium">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        <!-- Customer filter -->
                        <div class="col-md-3">
                            <label for="customer_id" class="form-label small fw-medium">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value="">All Customers</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search keyword -->
                        <div class="col-md-3">
                            <label for="search" class="form-label small fw-medium">Keyword Search</label>
                            <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Invoice # or customer...">
                        </div>

                        <input type="hidden" name="date_preset" value="{{ request('date_preset') }}">

                        <!-- Actions -->
                        <div class="col-12 text-end mt-3">
                            <a href="{{ url('admin/reports/sales') }}" class="btn btn-secondary me-2">Clear</a>
                            <button type="submit" class="btn btn-primary px-4">Filter Report</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Invoices Ledger -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Sales Invoices Records</h4>
                    <div class="d-flex gap-2 no-print">
                        <a href="{{ url('admin/reports/sales/excel?' . http_build_query(request()->all())) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
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
                                    <th>Invoice Number</th>
                                    <th>Customer Name</th>
                                    <th>Invoice Date</th>
                                    <th>Gross Subtotal</th>
                                    <th>Discount</th>
                                    <th>Tax Rate</th>
                                    <th>Net Grand Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value->invoice_number }}</td>
                                        <td>{{ $value->customer_name }} <small class="d-block text-muted">{{ $value->customer_phone }}</small></td>
                                        <td>{{ date('M d, Y', strtotime($value->invoice_date)) }}</td>
                                        <td>${{ number_format($value->total_amount, 2) }}</td>
                                        <td class="text-danger">-${{ number_format($value->total_discount, 2) }}</td>
                                        <td>{{ number_format($value->tax, 1) }}%</td>
                                        <td class="fw-bold text-success">${{ number_format($value->net_total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-danger py-4">
                                            No sales invoice matches found.
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
