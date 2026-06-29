@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Add New Stock</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/stocks') }}" class="text-decoration-none">Stocks</a></li>
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
                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h4 class="card-title fw-bold mb-0">Inventory Stock Details</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/stocks/create') }}" method="post">
                                @csrf

                                <!-- Medicine -->
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="medicine_id" class="form-label fw-medium mb-2">Medicine Product <span class="text-danger">*</span></label>
                                        <select name="medicine_id" id="medicine_id" class="form-select" required>
                                            <option value="">-- Select Medicine --</option>
                                            @foreach($medicines as $medicine)
                                                <option value="{{ $medicine->id }}">{{ $medicine->name }} ({{ $medicine->generic_name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Batch ID -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="batch_id" class="form-label fw-medium mb-2">Batch Number <span class="text-danger">*</span></label>
                                            <input type="text" name="batch_id" class="form-control" id="batch_id" value="{{ old('batch_id') }}" placeholder="e.g. B-99382" required>
                                        </div>
                                    </div>

                                    <!-- Expiry Date -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="expiry_date" class="form-label fw-medium mb-2">Expiry Date <span class="text-danger">*</span></label>
                                            <input type="date" name="expiry_date" class="form-control" id="expiry_date" value="{{ old('expiry_date') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Quantity -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="quantity" class="form-label fw-medium mb-2">Quantity <span class="text-danger">*</span></label>
                                            <input type="number" min="1" name="quantity" class="form-control" id="quantity" value="{{ old('quantity') }}" placeholder="e.g. 100" required>
                                        </div>
                                    </div>

                                    <!-- Rate -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="rate" class="form-label fw-medium mb-2">Purchase Rate ($) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" min="0" name="rate" class="form-control" id="rate" value="{{ old('rate') }}" placeholder="e.g. 4.50" required>
                                        </div>
                                    </div>

                                    <!-- MRP -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="mrp" class="form-label fw-medium mb-2">Selling MRP ($) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" min="0" name="mrp" class="form-control" id="mrp" value="{{ old('mrp') }}" placeholder="e.g. 7.99" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <a href="{{ url('admin/stocks') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Stock</button>
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