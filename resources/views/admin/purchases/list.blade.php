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
            <div class="row align-items-center mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/purchases/add') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Add New Purchase
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            @include('message')

            <div class="card shadow-sm border-0">
                <div class="card-header p-4">
                    <h4 class="card-title fw-bold mb-0 text-white">Purchase Records</h4>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>

                                    <th>Supplier</th>
                                    <th>Invoice Number</th>
                                    <th>Voucher Number</th>
                                    <th>Purchase Date</th>
                                    <th>Total Amount</th>
                                    <th>Payment Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($getPurchases as $purchase)
                                    <tr>

                                        <td class="fw-bold text-white">{{ optional($purchase->getSupplierName)->name ?? 'N/A' }}</td>
                                        <td>{{ optional($purchase->getInvoiceNo)->invoice_number ?? 'N/A' }}</td>
                                        <td>{{ $purchase->voucher_number ?? 'N/A' }}</td>
                                        <td>{{ date('Y-m-d', strtotime($purchase->purchase_date)) }}</td>
                                        <td class="fw-bold">${{ number_format($purchase->net_total, 2) }}</td>
                                        <td>
                                            @if($purchase->payment_status == 1)
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Pending</span>
                                            @elseif($purchase->payment_status == 2)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Accepted</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="text-xs text-muted">{{ date('Y-m-d H:i:s', strtotime($purchase->created_at)) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ url('admin/purchases/edit/'.$purchase->id) }}"
                                                   class="btn btn-outline-primary btn-sm rounded-3 me-1">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <a href="{{ url('admin/purchases/delete/'.$purchase->id) }}"
                                                   class="btn btn-outline-danger btn-sm rounded-3"
                                                   onclick="return confirm('Are you sure you want to delete this purchase?');">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-danger py-4">
                                            No Purchase Records Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
