@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Supplier Details</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/suppliers') }}" class="text-decoration-none">Suppliers</a></li>
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
                                    <i class="bi bi-truck fs-2"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold mb-1 text-white">{{ $supplier->name }}</h3>
                                    <span class="text-muted small">Registered Supplier</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Contact Details -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2">Contact & Company Info</h5>
                            <div class="row g-4 mb-4">
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Phone Number</strong>
                                    <span class="text-secondary">{{ $supplier->phone ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Email Address</strong>
                                    <span class="text-secondary">{{ $supplier->email ?? 'N/A' }}</span>
                                </div>
                                <div class="col-12">
                                    <strong class="text-white small d-block">Company Address</strong>
                                    <span class="text-secondary d-block">{{ $supplier->address ?? 'N/A' }}</span>
                                </div>

                            </div>

                            <!-- Account Summary -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2 mt-5">Account Summary</h5>
                            <div class="row g-3">
                                <div class="col-md-4 col-sm-6">
                                    <div class="border rounded p-3 text-center h-100" style="border-color: var(--bs-border-color) !important; background: rgba(59, 130, 246, 0.02);">
                                        <strong class="text-white small d-block mb-1">Total Amount</strong>
                                        <h4 class="mb-0 fw-bold text-white">{{ number_format($totalBillAmount, 2) }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="border rounded p-3 text-center h-100" style="border-color: var(--bs-border-color) !important; background: rgba(34, 197, 94, 0.02);">
                                        <strong class="text-white small d-block mb-1">Given Amount</strong>
                                        <h4 class="mb-0 fw-bold text-success">{{ number_format($paidAmount, 2) }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="border rounded p-3 text-center h-100" style="border-color: var(--bs-border-color) !important; background: rgba(239, 68, 68, 0.02);">
                                        <strong class="text-white small d-block mb-1">Pending Amount</strong>
                                        <h4 class="mb-0 fw-bold text-danger">{{ number_format($pendingAmount, 2) }}</h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Supply History -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2 mt-5">Supply History</h5>
                            <div class="table-responsive">
                                <table class="table align-middle table-hover">
                                    <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Voucher Number</th>
                                            <th>Purchase Date</th>
                                            <th>Medicines Supplied</th>
                                            <th>Net Total</th>
                                            <th>Payment Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($purchases as $purchase)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="fw-bold text-white">{{ $purchase->voucher_number }}</td>
                                                <td>{{ date('M d, Y', strtotime($purchase->purchase_date)) }}</td>
                                                <td>
                                                    <ul class="list-unstyled mb-0 small">
                                                        @foreach($purchase->getPurchaseItems as $item)
                                                            <li>
                                                                <span class="text-white">{{ $item->getMedicine->name ?? 'N/A' }}</span>
                                                                <span class="text-muted">({{ $item->quantity }} units)</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td class="text-white">{{ number_format($purchase->net_total, 2) }}</td>
                                                <td>
                                                    @if($purchase->payment_status === 'Paid')
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Paid</span>
                                                    @elseif($purchase->payment_status === 'Pending')
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Pending</span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-white border px-2 py-1 rounded-pill">{{ $purchase->payment_status }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ url('admin/purchases/show/'.$purchase->id) }}" class="btn btn-outline-info btn-sm rounded-3" title="View Purchase Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-danger py-4">
                                                    <i class="bi bi-exclamation-circle fs-4 mb-2 d-block"></i>
                                                    No supply transactions found for this supplier.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between mt-5 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                <a href="{{ url('admin/suppliers') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to List
                                </a>
                                @if($supplier->is_deleted == 0)
                                    <a href="{{ url('admin/suppliers/edit/'.$supplier->id) }}" class="btn btn-primary">
                                        <i class="bi bi-pencil-fill me-1"></i> Edit Supplier
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
