@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Edit Customer</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/customers') }}" class="text-decoration-none">Customers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <!-- Error list -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h4 class="card-title fw-bold mb-0">Customer & Patient Registry</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/customers/edit/' . $customer->id) }}" method="POST">
                                @csrf

                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label fw-medium mb-2">Customer Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" value="{{ old('name', $customer->name) }}" placeholder="Enter full name" required>
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="phone" class="form-label fw-medium mb-2">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" value="{{ old('phone', $customer->phone) }}" placeholder="Enter phone number" required>
                                            <div class="form-text text-muted text-xs">Must be numeric and unique.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="email" class="form-label fw-medium mb-2">Email Address</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" value="{{ old('email', $customer->email) }}" placeholder="Enter email address">
                                            <div class="form-text text-muted text-xs">Optional, but must be valid if entered.</div>
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="address" class="form-label fw-medium mb-2">Home Address <span class="text-danger">*</span></label>
                                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                                   id="address" value="{{ old('address', $customer->address) }}" placeholder="Enter home address" required>
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
                                            <input type="text" name="doctor_name" class="form-control @error('doctor_name') is-invalid @enderror" 
                                                   id="doctor_name" value="{{ old('doctor_name', $customer->doctor_name) }}" placeholder="e.g. Dr. John Doe">
                                        </div>
                                    </div>

                                    <!-- Doctor Address -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="doctor_address" class="form-label fw-medium mb-2">Clinic/Hospital Address</label>
                                            <input type="text" name="doctor_address" class="form-control @error('doctor_address') is-invalid @enderror" 
                                                   id="doctor_address" value="{{ old('doctor_address', $customer->doctor_address) }}" placeholder="Enter clinic address">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                    <a href="{{ url('admin/customers') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Customer</button>
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