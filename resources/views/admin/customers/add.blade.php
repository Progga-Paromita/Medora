@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Add New Customer</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/customers') }}" class="text-decoration-none">Customers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h4 class="card-title fw-bold mb-0">Customer & Patient Registry</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/customers/create') }}" method="post">
                                @csrf

                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label fw-medium mb-2">Customer Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="Enter full name" required>
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="phone" class="form-label fw-medium mb-2">Phone Number</label>
                                            <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone') }}" placeholder="Enter phone number">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="email" class="form-label fw-medium mb-2">Email Address</label>
                                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="Enter email address">
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="address" class="form-label fw-medium mb-2">Home Address</label>
                                            <input type="text" name="address" class="form-control" id="address" value="{{ old('address') }}" placeholder="Enter home address">
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4" style="border-color: var(--bs-border-color) !important;">
                                <h5 class="fw-bold mb-3 text-white">Prescribing Medical Officer (Optional)</h5>

                                <div class="row">
                                    <!-- Doctor Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="doctor_name" class="form-label fw-medium mb-2">Prescribing Doctor</label>
                                            <input type="text" name="doctor_name" class="form-control" id="doctor_name" value="{{ old('doctor_name') }}" placeholder="e.g. Dr. John Doe">
                                        </div>
                                    </div>

                                    <!-- Doctor Address -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="doctor_address" class="form-label fw-medium mb-2">Clinic/Hospital Address</label>
                                            <input type="text" name="doctor_address" class="form-control" id="doctor_address" value="{{ old('doctor_address') }}" placeholder="Enter clinic address">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <a href="{{ url('admin/customers') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Customer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection