@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Add New Purchase</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/purchases') }}" class="text-decoration-none">Purchases</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-12">
                    <!-- Error list -->
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('admin/purchases/add') }}" method="POST" id="purchaseForm">
                        @csrf

                        <!-- Purchase Info Card -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header p-4">
                                <h4 class="card-title fw-bold mb-0">Purchase Information</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <!-- Supplier -->
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="supplier_id" class="form-label fw-medium mb-2">Supplier <span class="text-danger">*</span></label>
                                            <select name="supplier_id" id="supplier_id" class="form-select" required>
                                                <option value="">-- Select Supplier --</option>
                                                @foreach($getSuppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Voucher Number -->
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="voucher_number" class="form-label fw-medium mb-2">Voucher Number <span class="text-danger">*</span></label>
                                            <input type="text" name="voucher_number" id="voucher_number" class="form-control" 
                                                   value="{{ old('voucher_number', 'PUR-' . date('YmdHis')) }}" placeholder="Enter unique voucher number" required>
                                        </div>
                                    </div>

                                    <!-- Purchase Date -->
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="purchase_date" class="form-label fw-medium mb-2">Purchase Date <span class="text-danger">*</span></label>
                                            <input type="date" name="purchase_date" id="purchase_date" class="form-control" 
                                                   value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                                        </div>
                                    </div>

                                    <!-- Payment Status -->
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="payment_status" class="form-label fw-medium mb-2">Payment Status <span class="text-danger">*</span></label>
                                            <select name="payment_status" id="payment_status" class="form-select" required>
                                                <option value="1" {{ old('payment_status') == '1' ? 'selected' : '' }}>Pending</option>
                                                <option value="2" {{ old('payment_status') == '2' ? 'selected' : '' }}>Accepted</option>
                                                <option value="3" {{ old('payment_status') == '3' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Items Card -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header p-4 d-flex justify-content-between align-items-center">
                                <h4 class="card-title fw-bold mb-0">Medicines Included</h4>
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" id="addRowBtn">
                                    <i class="bi bi-plus-lg me-1"></i> Add Row
                                </button>
                            </div>

                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table align-middle" id="medicineTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%;">Medicine Name <span class="text-danger">*</span></th>
                                                <th style="width: 15%;">Batch ID <span class="text-danger">*</span></th>
                                                <th style="width: 15%;">Expiry Date <span class="text-danger">*</span></th>
                                                <th style="width: 10%;">Qty <span class="text-danger">*</span></th>
                                                <th style="width: 12%;">Purchase Rate ($) <span class="text-danger">*</span></th>
                                                <th style="width: 12%;">MRP ($) <span class="text-danger">*</span></th>
                                                <th style="width: 10%;">Subtotal</th>
                                                <th style="width: 5%;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Row Template will be appended here -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-end fw-bold text-white fs-5">Net Total Amount:</td>
                                                <td colspan="2" class="fw-bold text-success fs-5">
                                                    $<span id="netTotalLabel">0.00</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <button type="reset" class="btn btn-outline-secondary" id="resetBtn">Reset</button>
                                    <a href="{{ url('admin/purchases') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Purchase</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Hidden Select Dropdown for cloning -->
<div class="d-none">
    <select id="medicineSelectTemplate" class="form-select">
        <option value="">-- Choose --</option>
        @foreach($getMedicines as $med)
            <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->packaging }})</option>
        @endforeach
    </select>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#medicineTable tbody');
        const addRowBtn = document.getElementById('addRowBtn');
        const netTotalLabel = document.getElementById('netTotalLabel');
        const medicineSelectTemplate = document.getElementById('medicineSelectTemplate');
        let rowIndex = 0;

        // Function to create a new row
        function addRow() {
            const tr = document.createElement('tr');
            tr.dataset.index = rowIndex;

            // Medicine Column Select
            const tdMed = document.createElement('td');
            const selectCloned = medicineSelectTemplate.cloneNode(true);
            selectCloned.removeAttribute('id');
            selectCloned.setAttribute('name', `medicines[${rowIndex}][medicine_id]`);
            selectCloned.setAttribute('required', 'required');
            selectCloned.className = 'form-select';
            tdMed.appendChild(selectCloned);

            // Batch Column
            const tdBatch = document.createElement('td');
            tdBatch.innerHTML = `<input type="text" name="medicines[${rowIndex}][batch_id]" class="form-control" placeholder="e.g. B001" required>`;

            // Expiry Column
            const tdExpiry = document.createElement('td');
            tdExpiry.innerHTML = `<input type="date" name="medicines[${rowIndex}][expiry_date]" class="form-control" required>`;

            // Qty Column
            const tdQty = document.createElement('td');
            tdQty.innerHTML = `<input type="number" name="medicines[${rowIndex}][quantity]" class="form-control qty-input" min="1" placeholder="10" required>`;

            // Rate Column
            const tdRate = document.createElement('td');
            tdRate.innerHTML = `<input type="number" step="0.01" name="medicines[${rowIndex}][purchase_rate]" class="form-control rate-input" min="0.01" placeholder="8.00" required>`;

            // MRP Column
            const tdMRP = document.createElement('td');
            tdMRP.innerHTML = `<input type="number" step="0.01" name="medicines[${rowIndex}][mrp]" class="form-control mrp-input" min="0.01" placeholder="10.00" required>`;

            // Subtotal Column
            const tdSub = document.createElement('td');
            tdSub.className = 'subtotal-cell fw-medium text-white';
            tdSub.textContent = '$0.00';

            // Remove Button
            const tdRemove = document.createElement('td');
            tdRemove.innerHTML = `<button type="button" class="btn btn-outline-danger btn-sm remove-row-btn"><i class="bi bi-trash"></i></button>`;

            // Append all to tr
            tr.appendChild(tdMed);
            tr.appendChild(tdBatch);
            tr.appendChild(tdExpiry);
            tr.appendChild(tdQty);
            tr.appendChild(tdRate);
            tr.appendChild(tdMRP);
            tr.appendChild(tdSub);
            tr.appendChild(tdRemove);

            tableBody.appendChild(tr);

            // Bind calculations on input changes
            const qtyInput = tr.querySelector('.qty-input');
            const rateInput = tr.querySelector('.rate-input');
            
            function recalculateRow() {
                const q = parseFloat(qtyInput.value) || 0;
                const r = parseFloat(rateInput.value) || 0;
                const sub = q * r;
                tdSub.textContent = '$' + sub.toFixed(2);
                recalculateNetTotal();
            }

            qtyInput.addEventListener('input', recalculateRow);
            rateInput.addEventListener('input', recalculateRow);

            rowIndex++;
        }

        // Calculate Net Total
        function recalculateNetTotal() {
            let total = 0;
            const rows = tableBody.querySelectorAll('tr');
            rows.forEach(row => {
                const q = parseFloat(row.querySelector('.qty-input').value) || 0;
                const r = parseFloat(row.querySelector('.rate-input').value) || 0;
                total += q * r;
            });
            netTotalLabel.textContent = total.toFixed(2);
        }

        // Add first row automatically on load
        addRow();

        // Event listener for adding rows
        addRowBtn.addEventListener('click', addRow);

        // Event listener for removing rows
        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row-btn')) {
                const tr = e.target.closest('tr');
                tr.remove();
                recalculateNetTotal();
            }
        });

        // Reset clears all and appends one row
        document.getElementById('resetBtn').addEventListener('click', function() {
            tableBody.innerHTML = '';
            setTimeout(() => {
                addRow();
                recalculateNetTotal();
            }, 100);
        });
    });
</script>
@endsection
