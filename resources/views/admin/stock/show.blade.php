@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Stock Batch Details</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/stocks') }}" class="text-decoration-none">Stocks</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Batch Details</li>
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
                        <!-- Header with Medicine Logo & Status -->
                        <div class="p-4 border-bottom" style="background: rgba(250, 202, 90, 0.03);">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3">
                                        <i class="bi bi-boxes fs-2"></i>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold mb-1 text-white">{{ $getRecord->medicine_name }}</h3>
                                        <span class="text-muted small">{{ $getRecord->generic_name }}</span>
                                    </div>
                                </div>
                                <div>
                                    @if($getRecord->expiry_date < date('Y-m-d'))
                                        <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold">EXPIRED</span>
                                    @elseif($getRecord->quantity == 0)
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill fw-bold">OUT OF STOCK</span>
                                    @elseif($getRecord->quantity < 10)
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">LOW STOCK</span>
                                    @else
                                        <span class="badge bg-success px-3 py-2 rounded-pill fw-bold">ACTIVE STOCK</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Batch & Quantity Info -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2">Batch & Inventory Status</h5>
                            <div class="row g-4 mb-4">
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Batch Number</strong>
                                    <span class="text-warning fs-5 fw-bold">{{ $getRecord->batch_id }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Available Quantity</strong>
                                    <span class="text-secondary fs-5">{{ $getRecord->quantity }} units</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Packaging</strong>
                                    <span class="text-secondary">{{ $getRecord->packaging ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Expiry Date</strong>
                                    @if($getRecord->expiry_date < date('Y-m-d'))
                                        <span class="text-danger fw-bold"><i class="bi bi-calendar-x-fill me-1"></i>{{ date('M d, Y', strtotime($getRecord->expiry_date)) }} (Expired)</span>
                                    @elseif($getRecord->expiry_date <= date('Y-m-d', strtotime('+90 days')))
                                        <span class="text-warning fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ date('M d, Y', strtotime($getRecord->expiry_date)) }} (Near Expiry)</span>
                                    @else
                                        <span class="text-success fw-bold"><i class="bi bi-calendar-check-fill me-1"></i>{{ date('M d, Y', strtotime($getRecord->expiry_date)) }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Financial details -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2 mt-5">Pricing & Valuation</h5>
                            <div class="row g-4 mb-4">
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Purchase Rate (Cost Price)</strong>
                                    <span class="text-secondary fs-5">{{ number_format($getRecord->rate, 2) }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Selling MRP (Retail Price)</strong>
                                    <span class="text-success fs-5 fw-bold">{{ number_format($getRecord->mrp, 2) }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Estimated Batch Cost Value</strong>
                                    <span class="text-secondary">{{ number_format($getRecord->rate * $getRecord->quantity, 2) }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Estimated Batch Retail Value</strong>
                                    <span class="text-success fw-bold">{{ number_format($getRecord->mrp * $getRecord->quantity, 2) }}</span>
                                </div>
                            </div>

                            <!-- Meta details -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2 mt-5">System Tracking</h5>
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Created On</strong>
                                    <span class="text-secondary">{{ $getRecord->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong class="text-white small d-block">Last Updated</strong>
                                    <span class="text-secondary">{{ $getRecord->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between mt-5 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                <a href="{{ url('admin/stocks') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to List
                                </a>
                                <a href="{{ url('admin/stocks/edit/'.$getRecord->id) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil-fill me-1"></i> Edit Batch
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
