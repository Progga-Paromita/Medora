@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Edit Purchase</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/purchases') }}" class="text-decoration-none">Purchases</a></li>
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
                            <h4 class="card-title fw-bold mb-0">Purchase Details</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/purchases/edit/' . $getRecord->id) }}" method="POST">
                                @csrf

                                @if($errors->any())
                                    <div class="alert alert-danger mb-4">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Supplier -->
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="supplier_id" class="form-label fw-medium mb-2">Supplier</label>
                                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                                            <option value="">-- Select Supplier --</option>
                                            @foreach($getSuppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ $getRecord->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Invoice -->
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="invoice_id" class="form-label fw-medium mb-2">Invoice Number</label>
                                        <select name="invoice_id" id="invoice_id" class="form-select" required>
                                            <option value="">-- Select Invoice --</option>
                                            @foreach($getInvoiceNo as $invoice)
                                                <option value="{{ $invoice->id }}" data-total="{{ $invoice->net_total }}" {{ $getRecord->invoice_id == $invoice->id ? 'selected' : '' }}>
                                                    {{ $invoice->invoice_number ?? $invoice->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Total Amount -->
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="net_total" class="form-label fw-medium mb-2">Total Amount</label>
                                        <input type="number" step="0.01" min="0" name="net_total" id="net_total" class="form-control" value="{{ old('net_total', $getRecord->net_total) }}" placeholder="Enter total amount" required>
                                    </div>
                                </div>

                                <!-- Voucher -->
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="voucher_number" class="form-label fw-medium mb-2">Voucher Number</label>
                                        <input type="text" name="voucher_number" id="voucher_number" class="form-control" value="{{ old('voucher_number', $getRecord->voucher_number) }}" placeholder="Enter voucher number">
                                    </div>
                                </div>

                                <!-- Purchase Date -->
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="purchase_date" class="form-label fw-medium mb-2">Purchase Date</label>
                                        <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ old('purchase_date', $getRecord->purchase_date) }}" required>
                                    </div>
                                </div>

                                <!-- Payment Status -->
                                <div class="mb-4">
                                    <div class="form-group">
                                        <label for="payment_status" class="form-label fw-medium mb-2">Payment Status</label>
                                        <select name="payment_status" id="payment_status" class="form-select" required>
                                            <option value="">-- Select Payment Status --</option>
                                            <option value="1" {{ $getRecord->payment_status == 1 ? 'selected' : '' }}>Pending</option>
                                            <option value="2" {{ $getRecord->payment_status == 2 ? 'selected' : '' }}>Accepted</option>
                                            <option value="3" {{ $getRecord->payment_status == 3 ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <a href="{{ url('admin/purchases') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Purchase</button>
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

@section('scripts')
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

    invoice.addEventListener('change', updateTotal);
});
</script>
@endsection
