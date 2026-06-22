@extends('layouts.app')

@section('content')
<section class="content">
    <div class="container-fluid mt-3 mb-3">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add New Invoice</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/purchases/add') }}" method="post">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="supplier_id">Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="">-- Select Supplier --</option>
                                    @foreach($getSuppliers as $value1)
                                        <option value="{{ $value1->id }}">{{ $value1->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="invoice_id">Invoice Number</label>
                                <select name="invoice_id" id="invoice_id" class="form-control" required>
                                    <option value="">-- Select Invoice --</option>
                                    @foreach($getInvoiceNo as $value2)
                                        <option value="{{ $value2->id }}" data-total="{{ $value2->net_total }}">
                                            {{ $value2->invoice_number ?? $value2->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="net_total">Total Amount</label>
                                <input type="number" name="net_total" class="form-control" id="net_total" value="{{ old('net_total') }}" placeholder="Enter total amount" readonly>
                            </div>

                            <div class="form-group">
                                <label for="voucher_number">Voucher Number</label>
                                <input type="text" name="voucher_number" class="form-control" id="voucher_number" value="{{ old('voucher_number') }}" placeholder="Enter voucher number">
                            </div>

                            <div class="form-group">
                                <label for="purchase_date">Purchase Date</label>
                                <input type="date" name="purchase_date" class="form-control" id="purchase_date" value="{{ old('purchase_date') }}" placeholder="Enter purchase date">
                            </div>

                            <div class="form-group">
                                <label for="payment_status">Payment Status</label>
                                <select name="payment_status" id="payment_status" class="form-control" required>
                                    <option value="" disabled selected hidden>-- Select Payment Status --</option>
                                    <option value="1">Pending</option>
                                    <option value="2">Accepted</option>
                                    <option value="3">Rejected</option>
                                </select>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="nav-icon bi bi-save"></i> Submit
                                </button>
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
document.getElementById('invoice_id').addEventListener('change', function() {
    // Get the selected option element
    var selectedOption = this.options[this.selectedIndex];

    // Extract the data-total attribute value
    var netTotal = selectedOption.getAttribute('data-total');

    // Set the value into the net_total input field
    if (netTotal) {
        document.getElementById('net_total').value = netTotal;
    } else {
        document.getElementById('net_total').value = '';
    }
});
</script>
@endsection
