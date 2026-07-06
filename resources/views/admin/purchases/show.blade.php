@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Purchase Voucher Details</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/purchases') }}" class="text-decoration-none">Purchases</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-9 mx-auto">
                    <div class="card shadow-sm border-0 overflow-hidden">
                        <!-- Invoice Header -->
                        <div class="p-4 border-bottom d-flex justify-content-between align-items-center" style="background: rgba(250, 202, 90, 0.03);">
                            <div>
                                <h3 class="fw-bold mb-1 text-white">Voucher: {{ $purchase->voucher_number }}</h3>
                                <span class="text-muted small">Supplier: <strong>{{ optional($purchase->getSupplierName)->name ?? 'N/A' }}</strong></span>
                            </div>
                            <div class="text-end">
                                <span class="text-muted small d-block">Voucher Date</span>
                                <strong class="text-white">{{ date('M d, Y', strtotime($purchase->purchase_date)) }}</strong>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Supplier details card -->
                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <h6 class="text-secondary text-uppercase small fw-bold">Supplier Information</h6>
                                    <div class="border rounded p-3" style="border-color: var(--bs-border-color) !important;">
                                        <h5 class="fw-bold text-white mb-1">{{ optional($purchase->getSupplierName)->name ?? 'N/A' }}</h5>
                                        <p class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i> {{ optional($purchase->getSupplierName)->phone ?? 'N/A' }}</p>
                                        <p class="text-muted small mb-1"><i class="bi bi-envelope me-1"></i> {{ optional($purchase->getSupplierName)->email ?? 'N/A' }}</p>
                                        <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i> {{ optional($purchase->getSupplierName)->address ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-secondary text-uppercase small fw-bold">Payment Summary</h6>
                                    <div class="border rounded p-3 h-100 d-flex flex-column justify-content-center" style="border-color: var(--bs-border-color) !important;">
                                        <div class="mb-2">
                                            <span class="text-muted small d-block">Payment Lifecycle Status</span>
                                            @if($purchase->payment_status == 1)
                                                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill mt-1">Pending</span>
                                            @elseif($purchase->payment_status == 2)
                                                <span class="badge bg-success px-3 py-1 rounded-pill mt-1">Accepted</span>
                                            @else
                                                <span class="badge bg-danger px-3 py-1 rounded-pill mt-1">Cancelled</span>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Voucher Created</span>
                                            <span class="text-white">{{ $purchase->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Medicines Table -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2">Purchase Line Items</h5>
                            <div class="table-responsive mb-4">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Medicine Name</th>
                                            <th>Generic Name</th>
                                            <th>Packaging</th>
                                            <th class="text-center">Qty Purchased</th>
                                            <th class="text-end">Purchase Rate ($)</th>
                                            <th class="text-end">Subtotal ($)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchaseItems as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="fw-bold text-white">{{ optional($item->getMedicine)->name ?? 'N/A' }}</td>
                                                <td class="text-secondary">{{ optional($item->getMedicine)->generic_name ?? 'N/A' }}</td>
                                                <td><span class="badge bg-secondary-subtle border px-2 py-0.5 rounded-pill">{{ optional($item->getMedicine)->packaging ?? 'N/A' }}</span></td>
                                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                                <td class="text-end">${{ number_format($item->purchase_rate, 2) }}</td>
                                                <td class="text-end fw-bold text-success">${{ number_format($item->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="text-end fw-bold text-white fs-5">Net Total Amount:</td>
                                            <td class="text-end fw-bold text-success fs-5">${{ number_format($purchase->net_total, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between mt-5 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                <a href="{{ url('admin/purchases') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to List
                                </a>
                                @if($purchase->is_deleted == 0)
                                    <a href="{{ url('admin/purchases/edit/'.$purchase->id) }}" class="btn btn-primary">
                                        <i class="bi bi-pencil-fill me-1"></i> Edit Voucher
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
