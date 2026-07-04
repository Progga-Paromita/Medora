@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Invoice Details</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/invoices') }}" class="text-decoration-none">Invoices</a></li>
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
                                <h3 class="fw-bold mb-1 text-white">Invoice No: {{ $invoice->invoice_number }}</h3>
                                <span class="text-muted small">Customer: <strong>{{ optional($invoice->getCustomerName)->name ?? 'Walk-in' }}</strong></span>
                            </div>
                            <div class="text-end">
                                <strong class="text-white small d-block">Sale Date</strong>
                                <span class="text-secondary">{{ date('M d, Y', strtotime($invoice->invoice_date)) }}</span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Customer Details -->
                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <h6 class="text-secondary text-uppercase small fw-bold">Customer Details</h6>
                                    <div class="border rounded p-3" style="border-color: var(--bs-border-color) !important;">
                                        <h5 class="fw-bold text-white mb-1">{{ optional($invoice->getCustomerName)->name ?? 'Walk-in' }}</h5>
                                        <p class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i> {{ optional($invoice->getCustomerName)->phone ?? 'N/A' }}</p>
                                        <p class="text-muted small mb-1"><i class="bi bi-envelope me-1"></i> {{ optional($invoice->getCustomerName)->email ?? 'N/A' }}</p>
                                        <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i> {{ optional($invoice->getCustomerName)->address ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-secondary text-uppercase small fw-bold">Invoice Summary</h6>
                                    <div class="border rounded p-3 h-100 d-flex flex-column justify-content-center" style="border-color: var(--bs-border-color) !important;">
                                        <div class="d-flex justify-content-between mb-2">
                                            <strong class="text-white small">Gross Subtotal:</strong>
                                            <span class="text-secondary">{{ number_format($invoice->total_amount, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <strong class="text-white small">Discount Deducted:</strong>
                                            <span class="text-danger">-{{ number_format($invoice->total_discount, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <strong class="text-white small">VAT / Tax rate:</strong>
                                            <span class="text-secondary">{{ number_format($invoice->tax, 1) }}%</span>
                                        </div>
                                        <hr class="my-2" style="border-color: var(--bs-border-color) !important;">
                                        <div class="d-flex justify-content-between text-success fw-bold">
                                            <strong>Net Payable Total:</strong>
                                            <span>{{ number_format($invoice->net_total, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Medicines Sold Table -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2">Line Items Sold</h5>
                            <div class="table-responsive mb-4">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Medicine Name</th>
                                            <th>Generic Name</th>
                                            <th>Packaging</th>
                                            <th class="text-center">Qty Sold</th>
                                            <th class="text-end">Selling Price</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoiceItems as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="fw-bold text-white">{{ optional($item->getMedicine)->name ?? 'N/A' }}</td>
                                                <td class="text-secondary">{{ optional($item->getMedicine)->generic_name ?? 'N/A' }}</td>
                                                <td><span class="badge bg-secondary-subtle border px-2 py-0.5 rounded-pill">{{ optional($item->getMedicine)->packaging ?? 'N/A' }}</span></td>
                                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                                <td class="text-end">{{ number_format($item->selling_price, 2) }}</td>
                                                <td class="text-end fw-bold text-success">{{ number_format($item->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="text-end fw-bold text-white fs-5">Gross Bill Total:</td>
                                            <td class="text-end fw-bold text-white fs-5">{{ number_format($invoice->total_amount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between mt-5 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                <a href="{{ url('admin/invoices') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to List
                                </a>
                                <div class="d-flex gap-2">
                                    <a href="{{ url('admin/invoices/print/'.$invoice->id) }}" target="_blank" class="btn btn-outline-warning">
                                        <i class="bi bi-printer me-1"></i> Print Invoice
                                    </a>
                                    @if($invoice->is_deleted == 0)
                                        <a href="{{ url('admin/invoices/edit/'.$invoice->id) }}" class="btn btn-primary">
                                            <i class="bi bi-pencil-fill me-1"></i> Edit Invoice
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
