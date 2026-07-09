@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Purchases</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Purchases</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/purchases/add') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add New Purchase
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
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(250, 202, 90, 0.08);">
                        <div class="card-body py-2">
                            <i class="bi bi-cart-check-fill text-primary fs-3 mb-1 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Total Purchases</span>
                            <h4 class="mb-0 fw-bold mt-1 text-white">{{ $totalPurchases }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(34, 197, 94, 0.08);">
                        <div class="card-body py-2">
                            <i class="bi bi-calendar3 text-success fs-3 mb-1 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Today's Purchases</span>
                            <h4 class="mb-0 fw-bold mt-1 text-success">{{ $todayPurchases }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(239, 68, 68, 0.08);">
                        <div class="card-body py-2">
                            <i class="bi bi-hourglass-split text-danger fs-3 mb-1 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Pending Payments</span>
                            <h4 class="mb-0 fw-bold mt-1 text-danger">{{ $pendingPayments }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(59, 130, 246, 0.08);">
                        <div class="card-body py-2">
                            <i class="bi bi-cash-stack text-info fs-3 mb-1 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Stock Inventory Value</span>
                            <h4 class="mb-0 fw-bold mt-1 text-white">{{ number_format($currentStockValue, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <!-- Search Keyword -->
                        <div class="col-md-3">
                            <label for="search" class="form-label small fw-medium">Search Voucher / Supplier</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" id="search" class="form-control"
                                       value="{{ request('search') }}" placeholder="Voucher # or supplier name...">
                            </div>
                        </div>

                        <!-- Supplier -->
                        <div class="col-md-3">
                            <label for="supplier_id" class="form-label small fw-medium">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-select">
                                <option value="">All Suppliers</option>
                                @foreach($getSuppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Payment Status -->
                        <div class="col-md-2">
                            <label for="payment_status" class="form-label small fw-medium">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="1" {{ request('payment_status') == '1' ? 'selected' : '' }}>Pending</option>
                                <option value="2" {{ request('payment_status') == '2' ? 'selected' : '' }}>Accepted</option>
                                <option value="3" {{ request('payment_status') == '3' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <!-- Dates -->
                        <div class="col-md-2">
                            <label for="start_date" class="form-label small fw-medium">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="limit" class="form-label small fw-medium">Limit</label>
                            <select name="limit" id="limit" class="form-select">
                                <option value="10" {{ request('limit') == '10' ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('limit') == '20' ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('limit') == '50' ? 'selected' : '' }}>50</option>
                            </select>
                        </div>

                        <!-- sorting placeholders -->
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                        <!-- Form actions -->
                        <div class="col-12 text-end mt-3">
                            <a href="{{ url('admin/purchases') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-clockwise"></i> Clear
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Purchases Registry Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Purchase Invoice Catalog</h4>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $getPurchases->total() }} vouchers found</span>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'voucher', 'sort_order' => request('sort_by') == 'voucher' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Voucher Number
                                            @if(request('sort_by') == 'voucher')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'supplier', 'sort_order' => request('sort_by') == 'supplier' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Supplier
                                            @if(request('sort_by') == 'supplier')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'purchase_date', 'sort_order' => request('sort_by') == 'purchase_date' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Purchase Date
                                            @if(request('sort_by') == 'purchase_date')
                                                <i class="bi bi-sort-numeric-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'net_total', 'sort_order' => request('sort_by') == 'net_total' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Net Total
                                            @if(request('sort_by') == 'net_total')
                                                <i class="bi bi-sort-numeric-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'payment_status', 'sort_order' => request('sort_by') == 'payment_status' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Payment Status
                                            @if(request('sort_by') == 'payment_status')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($getPurchases as $purchase)
                                    <tr>
                                        <!-- No Database IDs displayed, using sequential row number -->
                                        <td>{{ ($getPurchases->currentPage() - 1) * $getPurchases->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $purchase->voucher_number }}</td>
                                        <td>{{ $purchase->supplier_name }}</td>
                                        <td>{{ date('M d, Y', strtotime($purchase->purchase_date)) }}</td>
                                        <td class="fw-bold text-success">{{ number_format($purchase->net_total, 2) }}</td>
                                        <td>
                                            @if($purchase->payment_status == 1)
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Pending</span>
                                            @elseif($purchase->payment_status == 2)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Accepted</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Cancelled</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                @if($purchase->is_deleted == 0)
                                                    <a href="{{ url('admin/purchases/show/'.$purchase->id) }}" class="btn btn-outline-info btn-sm rounded-3" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ url('admin/purchases/edit/'.$purchase->id) }}" class="btn btn-outline-primary btn-sm rounded-3" title="Edit Voucher">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ url('admin/purchases/delete/'.$purchase->id) }}" class="btn btn-outline-danger btn-sm rounded-3" title="Delete"
                                                       onclick="return confirm('Are you sure you want to delete this purchase voucher? This will soft delete the voucher and subtract its items from stock.');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ url('admin/purchases/restore/'.$purchase->id) }}" class="btn btn-outline-success btn-sm rounded-3" title="Restore"
                                                       onclick="return confirm('Are you sure you want to restore this soft-deleted purchase voucher? This will add the items back into inventory.');">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-danger py-5">
                                            <i class="bi bi-exclamation-circle fs-3 mb-2 d-block"></i>
                                            No Purchase Records Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-4">
                        {!! $getPurchases->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
