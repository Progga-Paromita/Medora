@extends('layouts.app')

@section('content')

<section class="content">
    <div class="container-fluid mt-3 mb-3">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Invoice</h3>
                    </div>

                    <form action="{{ url('admin/invoices/edit/'.$getRecord->id) }}" method="POST">
                        @csrf

                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="invoice_number">Invoice Number</label>
                                <input type="text" name="invoice_number" class="form-control" id="invoice_number" value="{{ old('invoice_number', $getRecord->invoice_number ?? '') }}" placeholder="Enter Invoice Number" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="customer_id">Customer Name</label>
                                <select name="customer_id" id="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                    @foreach($getCustomer as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id', $getRecord->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="invoice_date">Invoice Date</label>
                                <input type="date"
                                       name="invoice_date"
                                       class="form-control"
                                       id="invoice_date"
                                       value="{{ old('invoice_date', $getRecord->invoice_date ? date('Y-m-d', strtotime($getRecord->invoice_date)) : '') }}"
                                       required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="total_amount">Total Amount</label>
                                <input type="number" name="total_amount" step="0.01" class="form-control" id="total_amount" value="{{ old('total_amount', $getRecord->total_amount) }}" placeholder="0.00" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="total_discount">Total Discount</label>
                                <input type="number" name="total_discount" step="0.01" class="form-control" id="total_discount" value="{{ old('total_discount', $getRecord->total_discount) }}" placeholder="0.00">
                            </div>

                            <div class="form-group mb-3">
                                <label for="tax">Tax (%)</label>
                                <input type="number" name="tax" step="0.01" class="form-control" id="tax" value="{{ old('tax', $getRecord->tax) }}" placeholder="0.00">
                            </div>

                            <div class="form-group mb-3">
                                <label for="net_total">Grand Total</label>
                                <input type="number" name="net_total" step="0.01" class="form-control" id="net_total" value="{{ old('net_total', $getRecord->net_total) }}" readonly>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ url('admin/invoices') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Invoice
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
</section>

@endsection
