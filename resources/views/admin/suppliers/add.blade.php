@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Add New Supplier</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/suppliers') }}" class="text-decoration-none">Suppliers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-8 mx-auto">
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
                            <h4 class="card-title fw-bold mb-0">Supplier Details</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/suppliers/create') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="name" class="form-label fw-medium mb-2">Supplier Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" value="{{ old('name') }}" placeholder="Enter supplier name" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="phone" class="form-label fw-medium mb-2">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" value="{{ old('phone') }}" placeholder="Enter phone number" required>
                                            <div class="form-text text-muted text-xs">Must be numeric and unique.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="email" class="form-label fw-medium mb-2">Email Address</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" value="{{ old('email') }}" placeholder="Enter email address">
                                            <div class="form-text text-muted text-xs">Optional, but must be valid if entered.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-group">
                                        <label for="address" class="form-label fw-medium mb-2">Supplier Address <span class="text-danger">*</span></label>
                                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                                                  rows="3" placeholder="Enter full address" required>{{ old('address') }}</textarea>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                    <a href="{{ url('admin/suppliers') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Supplier</button>
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