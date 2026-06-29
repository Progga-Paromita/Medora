@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Edit Invoice</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/invoices') }}" class="text-decoration-none">Invoices</a></li>
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
                            <h4 class="card-title fw-bold mb-0">Invoice Sale Details</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/invoices/edit/' . $getRecord->id) }}" method="post">
                                @csrf

                                <div class="row">
                                    <!-- Invoice Number -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="invoice_number" class="form-label fw-medium mb-2">Invoice Number <span class="text-danger">*</span></label>
                                            <input type="text" name="invoice_number" class="form-control" id="invoice_number" value="{{ old('invoice_number', $getRecord->invoice_number) }}" required>
                                        </div>
                                    </div>

                                    <!-- Invoice Date -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="invoice_date" class="form-label fw-medium mb-2">Invoice Date <span class="text-danger">*</span></label>
                                            <input type="date" name="invoice_date" class="form-control" id="invoice_date" value="{{ old('invoice_date', $getRecord->invoice_date) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer -->
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="customer_id" class="form-label fw-medium mb-2">Customer / Patient <span class="text-danger">*</span></label>
                                        <select name="customer_id" id="customer_id" class="form-select" required>
                                            <option value="">-- Select Customer --</option>
                                            @foreach($getCustomer as $customer)
                                                <option value="{{ $customer->id }}" {{ $getRecord->customer_id == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }} ({{ $customer->phone }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Total Amount -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="total_amount" class="form-label fw-medium mb-2">Gross Subtotal ($) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" min="0" name="total_amount" id="total_amount" class="form-control" value="{{ old('total_amount', $getRecord->total_amount) }}" placeholder="0.00" required>
                                        </div>
                                    </div>

                                    <!-- Total Discount -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="total_discount" class="form-label fw-medium mb-2">Discount ($)</label>
                                            <input type="number" step="0.01" min="0" name="total_discount" id="total_discount" class="form-control" value="{{ old('total_discount', $getRecord->total_discount) }}">
                                        </div>
                                    </div>

                                    <!-- Tax -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="tax" class="form-label fw-medium mb-2">VAT / Tax (%)</label>
                                            <input type="number" step="0.1" min="0" name="tax" id="tax" class="form-control" value="{{ old('tax', $getRecord->tax) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <a href="{{ url('admin/invoices') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Invoice</button>
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
