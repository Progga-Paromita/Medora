@extends('layouts.app')

@section('content')

<section class="content">
    <div class="container-fluid mt-3 mb-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add New Invoice</h3>
                    </div>

                    <form action="{{ url('admin/invoices/create') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
    <label for="invoice_number">Invoice Number</label>
    <input type="text" name="invoice_number" class="form-control" id="invoice_number" value="{{ old('invoice_number', $getRecord->invoice_number ?? '') }}" placeholder="Enter invoice number" required>
</div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="customer_id">Customer Name</label>
                                <select name="customer_id" id="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                    @foreach($getCustomer as $value)
                                        <option value="{{ $value->id }}" {{ old('customer_id') == $value->id ? 'selected' : '' }}>
                                            {{ $value->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="invoice_date">Invoice Date</label>
                                <input type="date" name="invoice_date" class="form-control" id="invoice_date" value="{{ old('invoice_date') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="total_amount">Total Amount</label>
                                <input type="number" name="total_amount" step="0.01" class="form-control" id="total_amount" value="{{ old('total_amount') }}" placeholder="0.00" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="total_discount">Total Discount</label>
                                <input type="number" name="total_discount" step="0.01" class="form-control" id="total_discount" value="{{ old('total_discount', '0.00') }}" placeholder="0.00">
                            </div>

                            <div class="form-group mb-3">
                                <label for="tax">Tax (%)</label>
                                <input type="number" name="tax" step="0.01" class="form-control" id="tax" value="{{ old('tax', '0.00') }}" placeholder="0.00">
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ url('admin/invoices') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Submit
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
