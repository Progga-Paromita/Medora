@extends('layouts.app')

@section('content')

<section class="content">
    <div class="container-fluid mt-3 mb-3">
        <div class="row">
            <div class="col-md-8">

                <div class="card card-primary">

                    <div class="card-header">
                        <h3 class="card-title">Edit Purchase</h3>
                    </div>

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

                        <form action="{{ url('admin/purchases/edit/'.$getRecord->id) }}" method="POST">

                            @csrf

                            <!-- Supplier -->
                            <div class="form-group mb-3">
                                <label>Supplier</label>

                                <select name="supplier_id" class="form-control" required>
                                    <option value="">-- Select Supplier --</option>

                                    @foreach($getSuppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ $getRecord->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <!-- Invoice -->
                            <div class="form-group mb-3">
                                <label>Invoice Number</label>

                                <select name="invoice_id" id="invoice_id" class="form-control" required>

                                    <option value="">-- Select Invoice --</option>

                                    @foreach($getInvoiceNo as $invoice)

                                        <option
                                            value="{{ $invoice->id }}"
                                            data-total="{{ $invoice->net_total }}"
                                            {{ $getRecord->invoice_id == $invoice->id ? 'selected' : '' }}>

                                            {{ $invoice->invoice_number }}

                                        </option>

                                    @endforeach

                                </select>
                            </div>

                            <!-- Total Amount -->
                            <div class="form-group mb-3">
                                <label>Total Amount</label>

                                <input
                                    type="number"
                                    step="0.01"
                                    name="net_total"
                                    id="net_total"
                                    class="form-control"
                                    value="{{ old('net_total', $getRecord->net_total) }}"
                                    required>
                            </div>

                            <!-- Voucher -->
                            <div class="form-group mb-3">
                                <label>Voucher Number</label>

                                <input
                                    type="text"
                                    name="voucher_number"
                                    class="form-control"
                                    value="{{ old('voucher_number', $getRecord->voucher_number) }}">
                            </div>

                            <!-- Purchase Date -->
                            <div class="form-group mb-3">
                                <label>Purchase Date</label>

                                <input
                                    type="date"
                                    name="purchase_date"
                                    class="form-control"
                                    value="{{ old('purchase_date', !empty($getRecord->purchase_date) ? date('Y-m-d', strtotime($getRecord->purchase_date)) : '') }}"
                                    required>
                            </div>

                            <!-- Payment Status -->
                            <div class="form-group mb-3">
                                <label>Payment Status</label>

                                <select name="payment_status" class="form-control" required>

                                    <option value="1" {{ $getRecord->payment_status == 1 ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="2" {{ $getRecord->payment_status == 2 ? 'selected' : '' }}>
                                        Accepted
                                    </option>

                                    <option value="3" {{ $getRecord->payment_status == 3 ? 'selected' : '' }}>
                                        Rejected
                                    </option>

                                </select>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    Update Purchase
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

        if (option && option.dataset.total) {
            total.value = option.dataset.total;
        }
    }

    // Initialize total amount when page loads
    updateTotal();

    // Update total when invoice changes
    invoice.addEventListener('change', updateTotal);

});
</script>

@endsection
