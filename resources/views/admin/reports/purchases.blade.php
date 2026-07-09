@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Purchase Statement Report</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/reports/dashboard') }}" class="text-decoration-none">BI Analytics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Purchases Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <!-- Summary stats row -->
            <div class="row mb-4 text-center">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm p-3 h-100" style="background: rgba(59, 130, 246, 0.08);">
                        <span class="text-secondary small fw-medium text-uppercase text-white">Total Purchases</span>
                        <h4 class="mb-0 fw-bold mt-1 text-white">${{ number_format($totalPurchaseVal, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm p-3 h-100" style="background: rgba(34, 197, 94, 0.08);">
                        <span class="text-secondary small fw-medium text-uppercase text-success">Completed Purchases</span>
                        <h4 class="mb-0 fw-bold mt-1 text-success">${{ number_format($completedPayments, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm p-3 h-100" style="background: rgba(239, 68, 68, 0.08);">
                        <span class="text-secondary small fw-medium text-uppercase text-danger">Pending Payments</span>
                        <h4 class="mb-0 fw-bold mt-1 text-danger">${{ number_format($pendingPayments, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm p-3 h-100" style="background: rgba(168, 85, 247, 0.08);">
                        <span class="text-secondary small fw-medium text-uppercase text-white">Vouchers Count</span>
                        <h4 class="mb-0 fw-bold mt-1 text-white">{{ $totalPurchasesCount }} vch</h4>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label small fw-medium">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label small fw-medium">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="supplier_id" class="form-label small fw-medium">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-select">
                                <option value="">All Suppliers</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="payment_status" class="form-label small fw-medium">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="1" {{ request('payment_status') == '1' ? 'selected' : '' }}>Pending</option>
                                <option value="2" {{ request('payment_status') == '2' ? 'selected' : '' }}>Accepted</option>
                                <option value="3" {{ request('payment_status') == '3' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-12 text-end mt-3">
                            <a href="{{ url('admin/reports/purchases') }}" class="btn btn-secondary me-2">Clear</a>
                            <button type="submit" class="btn btn-primary px-4">Filter Report</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Invoices Ledger -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Purchase Orders Ledger</h4>
                    <div class="d-flex gap-2 no-print">
                        <a href="{{ url('admin/reports/purchases/excel?' . http_build_query(request()->all())) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
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
                                    <th>Voucher Number</th>
                                    <th>Supplier Name</th>
                                    <th>Purchase Date</th>
                                    <th>Payment Status</th>
                                    <th>Net Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value->voucher_number }}</td>
                                        <td>{{ $value->supplier_name }}</td>
                                        <td>{{ date('M d, Y', strtotime($value->purchase_date)) }}</td>
                                        <td>
                                            @if($value->payment_status == 1)
                                                <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                                            @elseif($value->payment_status == 2)
                                                <span class="badge bg-success text-white rounded-pill">Accepted</span>
                                            @else
                                                <span class="badge bg-danger text-white rounded-pill">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-success">${{ number_format($value->net_total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-danger py-4">
                                            No purchase records found.
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
