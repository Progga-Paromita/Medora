@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Medicine Details</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/medicines') }}" class="text-decoration-none">Medicines</a></li>
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
                        <div class="p-4 text-center border-bottom" style="background: rgba(250, 202, 90, 0.03);">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $medicine->getProfileImage() }}" alt="Medicine Photo" 
                                     class="rounded border border-3 border-primary shadow" 
                                     style="width: 130px; height: 130px; object-fit: cover;">
                            </div>
                            <h3 class="fw-bold mt-3 mb-1 text-white">{{ $medicine->name }}</h3>
                            <p class="text-muted mb-0 small">
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill">{{ $medicine->packaging }}</span>
                            </p>
                        </div>

                        <div class="card-body p-4">
                            <!-- Basic details -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2">Product Specifications</h5>
                            <div class="row g-4 mb-4">
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Generic Name</span>
                                    <strong class="text-white">{{ $medicine->generic_name }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Strength / Dosage</span>
                                    <strong class="text-white">{{ $medicine->strength ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">SKU / Drug Code</span>
                                    <strong class="text-white">{{ $medicine->sku ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Storage Temperature</span>
                                    <strong class="text-white">{{ $medicine->temperature_control ?? 'Room Temp' }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Rx/OTC status</span>
                                    @if($medicine->prescription_required)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 rounded-pill">Rx Only</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded-pill">OTC Product</span>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Linked Supplier</span>
                                    <strong class="text-white text-secondary">{{ optional($medicine->getSupplierName)->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Added On</span>
                                    <span class="text-white">{{ $medicine->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Last Updated</span>
                                    <span class="text-white">{{ $medicine->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>

                            <!-- Placeholders for stock, expiry, and history -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2 mt-5">Inventory & Stock Tracking</h5>
                            <div class="row g-3">
                                <div class="col-sm-3 col-6">
                                    <div class="border rounded p-3 text-center" style="border-color: var(--bs-border-color) !important;">
                                        <h6 class="text-muted mb-1 small">Current Stock</h6>
                                        <h4 class="mb-0 fw-bold text-white">0 <span class="text-xs text-muted fw-normal">(Placeholder)</span></h4>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <div class="border rounded p-3 text-center" style="border-color: var(--bs-border-color) !important;">
                                        <h6 class="text-muted mb-1 small">Purchased</h6>
                                        <h4 class="mb-0 fw-bold text-white">0 <span class="text-xs text-muted fw-normal">(Placeholder)</span></h4>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <div class="border rounded p-3 text-center" style="border-color: var(--bs-border-color) !important;">
                                        <h6 class="text-muted mb-1 small">Sold</h6>
                                        <h4 class="mb-0 fw-bold text-white">0 <span class="text-xs text-muted fw-normal">(Placeholder)</span></h4>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <div class="border rounded p-3 text-center text-warning" style="border-color: var(--bs-border-color) !important;">
                                        <h6 class="text-warning mb-1 small">Expiry status</h6>
                                        <h4 class="mb-0 fw-bold">OK <span class="text-xs text-muted fw-normal">(Placeholder)</span></h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between mt-5 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                <a href="{{ url('admin/medicines') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to List
                                </a>
                                @if($medicine->is_deleted == 0)
                                    <a href="{{ url('admin/medicines/edit/'.$medicine->id) }}" class="btn btn-primary">
                                        <i class="bi bi-pencil-fill me-1"></i> Edit Product
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
