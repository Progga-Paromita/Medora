@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Sales Invoices</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Invoices</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/invoices/create') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-receipt me-1"></i> Generate New Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            @include('message')

            <!-- Dashboard Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(250, 202, 90, 0.08);">
                        <div class="card-body py-1 px-2">
                            <i class="bi bi-cash text-primary fs-3 mb-1 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Today's Sales</span>
                            <h5 class="mb-0 fw-bold mt-1 text-white">{{ number_format($todaySales, 2) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(34, 197, 94, 0.08);">
                        <div class="card-body py-1 px-2">
                            <i class="bi bi-graph-up-arrow text-success fs-3 mb-1 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Monthly Sales</span>
                            <h5 class="mb-0 fw-bold mt-1 text-success">{{ number_format($monthlySales, 2) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(59, 130, 246, 0.08);">
                        <div class="card-body py-1 px-2">
                            <i class="bi bi-wallet2 text-info fs-3 mb-1 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Total Revenue</span>
                            <h5 class="mb-0 fw-bold mt-1 text-white">{{ number_format($totalRevenue, 2) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(168, 85, 247, 0.08);">
                        <div class="card-body py-1 px-2">
                            <i class="bi bi-receipt-cutoff text-warning fs-3 mb-1 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Total Invoices</span>
                            <h5 class="mb-0 fw-bold mt-1 text-white">{{ $totalInvoices }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(236, 72, 153, 0.08);">
                        <div class="card-body py-1 px-2">
                            <i class="bi bi-calculator text-danger fs-3 mb-1 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Avg Invoice</span>
                            <h5 class="mb-0 fw-bold mt-1 text-white">{{ number_format($avgInvoiceValue, 2) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(14, 165, 233, 0.08);">
                        <div class="card-body py-1 px-2">
                            <i class="bi bi-cart4 text-white fs-3 mb-1 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Sold Today</span>
                            <h5 class="mb-0 fw-bold mt-1 text-white">{{ $medicinesSoldToday }} <span class="small text-muted fw-normal">units</span></h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <!-- Keyword Search -->
                        <div class="col-md-6">
                            <label for="search" class="form-label small fw-medium">Search Invoice / Customer</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" id="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Invoice # or customer name...">
                            </div>
                        </div>

                        <!-- Customer select -->
                        <div class="col-md-4">
                            <label for="customer_id" class="form-label small fw-medium">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value="">All Customers</option>
                                @foreach($getCustomers as $cust)
                                    <option value="{{ $cust->id }}" {{ request('customer_id') == $cust->id ? 'selected' : '' }}>{{ $cust->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Page limit -->
                        <div class="col-md-2">
                            <label for="limit" class="form-label small fw-medium">Show</label>
                            <select name="limit" id="limit" class="form-select">
                                <option value="10" {{ request('limit') == '10' ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('limit') == '20' ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('limit') == '50' ? 'selected' : '' }}>50</option>
                            </select>
                        </div>

                        <!-- preserved sorting states -->
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                        <!-- Actions -->
                        <div class="col-12 text-end mt-3">
                            <a href="{{ url('admin/invoices') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-clockwise"></i> Clear
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-funnel-fill me-1"></i> Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Invoices Registry Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Invoice Records</h4>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $getRecord->total() }} invoices generated</span>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'invoice_no', 'sort_order' => request('sort_by') == 'invoice_no' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Invoice Number
                                            @if(request('sort_by') == 'invoice_no')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'customer', 'sort_order' => request('sort_by') == 'customer' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Customer / Patient
                                            @if(request('sort_by') == 'customer')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'invoice_date', 'sort_order' => request('sort_by') == 'invoice_date' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Invoice Date
                                            @if(request('sort_by') == 'invoice_date')
                                                <i class="bi bi-sort-numeric-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'net_total', 'sort_order' => request('sort_by') == 'net_total' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Net Grand Total
                                            @if(request('sort_by') == 'net_total')
                                                <i class="bi bi-sort-numeric-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($getRecord as $value)
                                    <tr>
                                        <!-- No Database IDs displayed, using sequential row number -->
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value->invoice_number }}</td>
                                        <td>
                                            <span class="fw-medium text-white">{{ $value->customer_name }}</span>
                                        </td>
                                        <td>{{ date('M d, Y', strtotime($value->invoice_date)) }}</td>
                                        <td class="fw-bold text-success">{{ number_format($value->net_total, 2) }}</td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                @if($value->is_deleted == 0)
                                                    <a href="{{ url('admin/invoices/show/'.$value->id) }}" class="btn btn-outline-info btn-sm rounded-3" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ url('admin/invoices/print/'.$value->id) }}" target="_blank" class="btn btn-outline-warning btn-sm rounded-3" title="Print Receipt">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                    <a href="{{ url('admin/invoices/edit/'.$value->id) }}" class="btn btn-outline-primary btn-sm rounded-3" title="Edit Invoice">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ url('admin/invoices/delete/'.$value->id) }}" class="btn btn-outline-danger btn-sm rounded-3" title="Delete"
                                                       onclick="return confirm('Are you sure you want to delete this invoice? This will soft delete the invoice and add the item quantities back into stock.');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ url('admin/invoices/restore/'.$value->id) }}" class="btn btn-outline-success btn-sm rounded-3" title="Restore"
                                                       onclick="return confirm('Are you sure you want to restore this soft-deleted invoice? This will re-deduct quantities from stock.');">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-danger py-5">
                                            <i class="bi bi-exclamation-circle fs-3 mb-2 d-block"></i>
                                            No Invoices Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-end mt-4">
                        {!! $getRecord->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
