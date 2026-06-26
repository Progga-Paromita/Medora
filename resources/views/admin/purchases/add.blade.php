@extends('layouts.app')

@section('content')

<section class="content">
    <div class="container-fluid mt-3 mb-3">
        <div class="row">
            <div class="col-md-8">

                <div class="card card-primary">

                    <div class="card-header">
                        <h3 class="card-title">Add New Purchase</h3>
                    </div>

                    <form action="{{ url('admin/purchases/add') }}" method="POST">
                        @csrf

                        <div class="card-body">

                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Supplier -->
                            <div class="form-group">
                                <label for="supplier_id">Supplier</label>

                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="">-- Select Supplier --</option>

                                    @foreach($getSuppliers as $supplier)
                                        <option value="{{ $supplier->id }}">
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <!-- Invoice -->

                            <div class="form-group">
                                <label for="invoice_id">Invoice Number</label>

                                <select name="invoice_id" id="invoice_id" class="form-control" required>

                                    <option value="">-- Select Invoice --</option>

                                    @foreach($getInvoiceNo as $invoice)

                                        <option
                                            value="{{ $invoice->id }}"
                                            data-total="{{ $invoice->net_total }}">

                                            {{ $invoice->invoice_number ?? $invoice->id }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <!-- Total Amount -->

                            <div class="form-group">
                                <label for="net_total">Total Amount</label>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="net_total"
                                    id="net_total"
                                    class="form-control"
                                    value="{{ old('net_total') }}"
                                    placeholder="Enter total amount">
                            </div>

                            <!-- Voucher -->

                            <div class="form-group">
                                <label for="voucher_number">Voucher Number</label>

                                <input
                                    type="text"
                                    name="voucher_number"
                                    id="voucher_number"
                                    class="form-control"
                                    value="{{ old('voucher_number') }}"
                                    placeholder="Enter voucher number">
                            </div>

                            <!-- Purchase Date -->

                            <div class="form-group">
                                <label for="purchase_date">Purchase Date</label>

                                <input
                                    type="date"
                                    name="purchase_date"
                                    id="purchase_date"
                                    class="form-control"
                                    value="{{ old('purchase_date') }}">
                            </div>

                            <!-- Payment Status -->

                            <div class="form-group">
                                <label for="payment_status">Payment Status</label>

                                <select
                                    name="payment_status"
                                    id="payment_status"
                                    class="form-control"
                                    required>

                                    <option value="">-- Select Payment Status --</option>

                                    <option value="1">Pending</option>
                                    <option value="2">Accepted</option>
                                    <option value="3">Rejected</option>

                                </select>

                            </div>

                        </div>

                        <div class="card-footer">

                            <button type="submit" class="btn btn-primary">
                                <i class="nav-icon bi bi-save"></i>
                                Submit
                            </button>

                            <a href="{{ url('admin/purchases') }}" class="btn btn-secondary">
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@section('script')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const invoice = document.getElementById('invoice_id');
    const total = document.getElementById('net_total');

    function updateTotal() {

        const option = invoice.options[invoice.selectedIndex];

        if(option && option.dataset.total){
            total.value = option.dataset.total;
        }

    }

    invoice.addEventListener('change', updateTotal);

});

</script>

@endsection
