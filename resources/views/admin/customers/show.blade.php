@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Customer Details</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/customers') }}" class="text-decoration-none">Customers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card shadow-sm border-0 overflow-hidden">
                        <!-- Profile Header -->
                        <div class="p-4 border-bottom" style="background: rgba(250, 202, 90, 0.03);">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3">
                                    <i class="bi bi-person-bounding-box fs-2"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold mb-1 text-white">{{ $customer->name }}</h3>
                                    <span class="text-muted small">Registered Customer / Patient</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Contact Details -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2">Contact Details</h5>
                            <div class="row g-4 mb-4">
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Phone Number</strong>
                                    <span class="text-secondary">{{ $customer->phone ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Email Address</strong>
                                    <span class="text-secondary">{{ $customer->email ?? 'N/A' }}</span>
                                </div>
                                <div class="col-12">
                                    <strong class="text-white small d-block">Home Address</strong>
                                    <span class="text-secondary d-block">{{ $customer->address ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <!-- Doctor Details -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2 mt-5">Prescribing Medical Details</h5>
                            <div class="row g-4 mb-4">
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Doctor Name</strong>
                                    <span class="text-secondary">{{ $customer->doctor_name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Clinic/Hospital Address</strong>
                                    <span class="text-secondary d-block">{{ $customer->doctor_address ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <!-- Integration Statistics -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2 mt-5">Purchase Performance Metrics</h5>
                            <div class="row g-3">
                                <div class="col-md-3 col-sm-6">
                                    <div class="border rounded p-3 text-center h-100" style="border-color: var(--bs-border-color) !important; background: rgba(250, 202, 90, 0.02);">
                                        <h6 class="text-muted mb-1 small">Total Invoices</h6>
                                        <h4 class="mb-0 fw-bold text-white">{{ $totalInvoices }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="border rounded p-3 text-center h-100" style="border-color: var(--bs-border-color) !important; background: rgba(34, 197, 94, 0.02);">
                                        <h6 class="text-muted mb-1 small">Unique Medicines</h6>
                                        <h4 class="mb-0 fw-bold text-white">{{ $totalMedicinesPurchased }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="border rounded p-3 text-center h-100" style="border-color: var(--bs-border-color) !important; background: rgba(59, 130, 246, 0.02);">
                                        <h6 class="text-muted mb-1 small">Total Qty Purchased</h6>
                                        <h4 class="mb-0 fw-bold text-white">{{ number_format($totalQuantityPurchased) }} <span class="text-xs text-muted fw-normal">units</span></h4>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="border rounded p-3 text-center h-100" style="border-color: var(--bs-border-color) !important; background: rgba(239, 68, 68, 0.02);">
                                        <h6 class="text-muted mb-1 small">Total Purchase Value</h6>
                                        <h4 class="mb-0 fw-bold text-white">{{ number_format($totalSalesValue, 2) }}</h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Purchase History -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2 mt-5">Purchase History</h5>
                            <div class="table-responsive">
                                <table class="table align-middle table-hover">
                                    <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Invoice Number</th>
                                            <th>Invoice Date</th>
                                            <th>Medicines Purchased</th>
                                            <th>Grand Total</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($invoices as $invoice)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="fw-bold text-white">{{ $invoice->invoice_number }}</td>
                                                <td>{{ date('M d, Y', strtotime($invoice->invoice_date)) }}</td>
                                                <td>
                                                    <ul class="list-unstyled mb-0 small">
                                                        @foreach($invoice->getInvoiceItems as $item)
                                                            <li>
                                                                <span class="text-white">{{ $item->getMedicine->name ?? 'N/A' }}</span>
                                                                <span class="text-muted">({{ $item->quantity }} units)</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td class="text-white">{{ number_format($invoice->net_total, 2) }}</td>
                                                <td class="text-end">
                                                    <a href="{{ url('admin/invoices/show/'.$invoice->id) }}" class="btn btn-outline-info btn-sm rounded-3" title="View Invoice Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-danger py-4">
                                                    <i class="bi bi-exclamation-circle fs-4 mb-2 d-block"></i>
                                                    No purchase transactions found for this customer.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between mt-5 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                <a href="{{ url('admin/customers') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to List
                                </a>
                                @if($customer->is_deleted == 0)
                                    <a href="{{ url('admin/customers/edit/'.$customer->id) }}" class="btn btn-primary">
                                        <i class="bi bi-pencil-fill me-1"></i> Edit Customer
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
