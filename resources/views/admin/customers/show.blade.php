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
                                    <span class="text-muted small d-block">Phone Number</span>
                                    <strong class="text-white">{{ $customer->phone ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Email Address</span>
                                    <strong class="text-white text-secondary">{{ $customer->email ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted small d-block">Home Address</span>
                                    <strong class="text-white d-block">{{ $customer->address ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Registration Date</span>
                                    <span class="text-white">{{ $customer->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Last Updated</span>
                                    <span class="text-white">{{ $customer->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>

                            <!-- Doctor Details -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2 mt-5">Prescribing Medical Details</h5>
                            <div class="row g-4 mb-4">
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Doctor Name</span>
                                    <strong class="text-white text-warning">{{ $customer->doctor_name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Clinic/Hospital Address</span>
                                    <strong class="text-white">{{ $customer->doctor_address ?? 'N/A' }}</strong>
                                </div>
                            </div>

                            <!-- Integration Placeholders -->
                            <h5 class="fw-bold mb-3 text-white border-bottom pb-2 mt-5">Invoices & Financial Integration</h5>
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <div class="border rounded p-3 text-center" style="border-color: var(--bs-border-color) !important;">
                                        <h6 class="text-muted mb-1 small">Total Invoices</h6>
                                        <h4 class="mb-0 fw-bold text-white">0 <span class="text-xs text-muted fw-normal">(Placeholder)</span></h4>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="border rounded p-3 text-center" style="border-color: var(--bs-border-color) !important;">
                                        <h6 class="text-muted mb-1 small">Total Sales</h6>
                                        <h4 class="mb-0 fw-bold text-white">$0.00 <span class="text-xs text-muted fw-normal">(Placeholder)</span></h4>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="border rounded p-3 text-center text-danger" style="border-color: var(--bs-border-color) !important; background: rgba(239, 68, 68, 0.05);">
                                        <h6 class="text-danger mb-1 small">Outstanding Balance</h6>
                                        <h4 class="mb-0 fw-bold">$0.00 <span class="text-xs text-muted fw-normal">(Placeholder)</span></h4>
                                    </div>
                                </div>
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
