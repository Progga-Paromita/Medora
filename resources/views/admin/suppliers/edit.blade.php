@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Edit Supplier</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/suppliers') }}" class="text-decoration-none">Suppliers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h4 class="card-title fw-bold mb-0">Supplier Details</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/suppliers/edit/' . $getRecord->id) }}" method="post">
                                @csrf

                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="name" class="form-label fw-medium mb-2">Supplier Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $getRecord->name) }}" placeholder="Enter supplier name" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="phone" class="form-label fw-medium mb-2">Phone Number</label>
                                            <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', $getRecord->phone) }}" placeholder="Enter phone number">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="email" class="form-label fw-medium mb-2">Email Address</label>
                                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $getRecord->email) }}" placeholder="Enter email address">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-group">
                                        <label for="address" class="form-label fw-medium mb-2">Supplier Address</label>
                                        <textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter full address">{{ old('address', $getRecord->address) }}</textarea>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <a href="{{ url('admin/suppliers') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Supplier</button>
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