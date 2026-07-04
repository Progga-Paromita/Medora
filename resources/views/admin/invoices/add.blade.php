@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Generate Invoice</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/invoices') }}" class="text-decoration-none">Invoices</a></li>
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

                    @if(session('error'))
                        <div class="alert alert-danger mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ url('admin/invoices/create') }}" method="POST" id="invoiceForm">
                        @csrf

                        <!-- Header Info Card -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header p-4">
                                <h4 class="card-title fw-bold mb-0 text-white">Invoice Sale Details</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <!-- Customer / Patient -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="customer_id" class="form-label fw-medium mb-2">Customer / Patient <span class="text-danger">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-select" required>
                                                <option value="">-- Select Customer --</option>
                                                @foreach($getCustomer as $customer)
                                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }} ({{ $customer->phone }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Invoice Date -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="invoice_date" class="form-label fw-medium mb-2">Invoice Date <span class="text-danger">*</span></label>
                                            <input type="date" name="invoice_date" class="form-control" id="invoice_date" 
                                                   value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Autocomplete Medicine Search Widget -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-4">
                                <label for="medicineSearchInput" class="form-label fw-semibold text-white">Search Medicine to Add <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" id="medicineSearchInput" class="form-control form-control-lg" 
                                               placeholder="Type medicine name or generic name to search..." autocomplete="off">
                                    </div>
                                    
                                    <!-- Results Dropdown container -->
                                    <ul class="dropdown-menu w-100 shadow-lg border-0 mt-1 d-none" id="searchResults" style="max-height: 250px; overflow-y: auto;">
                                        <!-- Dynamic search list -->
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Items Table -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header p-4">
                                <h4 class="card-title fw-bold mb-0 text-white">Bill Items</h4>
                            </div>

                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table align-middle" id="itemsTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 30%;">Medicine Name</th>
                                                <th style="width: 15%;">Generic Name</th>
                                                <th style="width: 15%;">Stock Level</th>
                                                <th style="width: 15%;">Quantity <span class="text-danger">*</span></th>
                                                <th style="width: 10%;">Price (MRP)</th>
                                                <th style="width: 10%;">Subtotal</th>
                                                <th style="width: 5%;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic billing lines -->
                                            <tr id="emptyRowPlaceholder">
                                                <td colspan="7" class="text-center py-4 text-muted">
                                                    No medicines added yet. Type in the search box above to add items.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Running totals calculations -->
                                <div class="row justify-content-end mt-4">
                                    <div class="col-md-5">
                                        <div class="border rounded p-4" style="border-color: var(--bs-border-color) !important;">
                                            <div class="d-flex justify-content-between mb-3 text-white">
                                                <span>Gross Subtotal:</span>
                                                <span class="fw-semibold" id="grossSubtotalLabel">$0.00</span>
                                            </div>

                                            <div class="row g-2 align-items-center mb-3">
                                                <div class="col-sm-5 text-white">Discount:</div>
                                                <div class="col-sm-4">
                                                    <select name="discount_type" id="discount_type" class="form-select form-select-sm">
                                                        <option value="percentage">% Percent</option>
                                                        <option value="fixed">Fixed</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="number" step="0.01" name="discount_value" id="discount_value" 
                                                           class="form-control form-control-sm text-end" value="0.00" min="0">
                                                </div>
                                            </div>

                                            <div class="row g-2 align-items-center mb-3">
                                                <div class="col-sm-8 text-white">VAT / Tax (%):</div>
                                                <div class="col-sm-4">
                                                    <input type="number" step="0.1" name="tax" id="tax" 
                                                           class="form-control form-control-sm text-end" value="0.00" min="0" max="100">
                                                </div>
                                            </div>

                                            <hr style="border-color: var(--bs-border-color) !important;">

                                            <div class="d-flex justify-content-between text-success fw-bold fs-5">
                                                <span>Net Grand Total:</span>
                                                <span id="netGrandTotalLabel">$0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <a href="{{ url('admin/invoices') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="saveInvoiceBtn" disabled>
                                        <i class="bi bi-save me-1"></i> Save Invoice
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('medicineSearchInput');
        const searchResults = document.getElementById('searchResults');
        const itemsTableBody = document.querySelector('#itemsTable tbody');
        const emptyRowPlaceholder = document.getElementById('emptyRowPlaceholder');
        const grossSubtotalLabel = document.getElementById('grossSubtotalLabel');
        const discountType = document.getElementById('discount_type');
        const discountValue = document.getElementById('discount_value');
        const taxInput = document.getElementById('tax');
        const netGrandTotalLabel = document.getElementById('netGrandTotalLabel');
        const saveInvoiceBtn = document.getElementById('saveInvoiceBtn');
        let rowIndex = 0;

        // Perform AJAX search on typing
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length < 2) {
                searchResults.classList.add('d-none');
                return;
            }

            fetch("{{ url('admin/invoices/search-medicine') }}?q=" + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    if (data.length === 0) {
                        const li = document.createElement('li');
                        li.className = 'dropdown-item text-danger small';
                        li.textContent = 'No medicines found or out of stock';
                        searchResults.appendChild(li);
                    } else {
                        data.forEach(item => {
                            const li = document.createElement('li');
                            li.className = 'dropdown-item d-flex justify-content-between align-items-center cursor-pointer py-2';
                            li.innerHTML = `
                                <div>
                                    <strong class="text-white">${item.name}</strong> (${item.packaging})
                                    <small class="d-block text-secondary">${item.generic_name}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge ${item.available_qty > 0 ? 'bg-success' : 'bg-danger'} rounded-pill me-2">Qty: ${item.available_qty}</span>
                                    <span class="text-success fw-bold">${item.mrp.toFixed(2)}</span>
                                </div>
                            `;
                            li.addEventListener('click', () => addMedicineRow(item));
                            searchResults.appendChild(li);
                        });
                    }
                    searchResults.classList.remove('d-none');
                })
                .catch(err => console.error(err));
        });

        // Hide dropdown search when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('d-none');
            }
        });

        // Add medicine row to table
        function addMedicineRow(item) {
            searchResults.classList.add('d-none');
            searchInput.value = '';

            // Check if medicine already exists in table
            const existingRow = itemsTableBody.querySelector(`tr[data-medicine-id="${item.id}"]`);
            if (existingRow) {
                const qtyInput = existingRow.querySelector('.qty-input');
                qtyInput.value = parseInt(qtyInput.value) + 1;
                qtyInput.dispatchEvent(new Event('input'));
                return;
            }

            // Remove placeholder if present
            if (emptyRowPlaceholder) {
                emptyRowPlaceholder.remove();
            }

            const tr = document.createElement('tr');
            tr.dataset.index = rowIndex;
            tr.dataset.medicineId = item.id;

            tr.innerHTML = `
                <td class="fw-bold text-white">
                    ${item.name} (${item.packaging})
                    <input type="hidden" name="medicines[${rowIndex}][medicine_id]" value="${item.id}">
                </td>
                <td class="text-secondary">${item.generic_name}</td>
                <td>
                    <span class="badge bg-secondary px-2.5 py-1.5 rounded-pill stock-label" data-stock="${item.available_qty}">
                        Avail Qty: ${item.available_qty}
                    </span>
                </td>
                <td>
                    <input type="number" name="medicines[${rowIndex}][quantity]" 
                           class="form-control form-control-sm qty-input" 
                           min="1" value="1" required>
                    <span class="text-danger text-xs d-none validation-error">Quantity exceeds stock!</span>
                </td>
                <td class="fw-medium text-white">${item.mrp.toFixed(2)}</td>
                <td class="subtotal-cell fw-bold text-success">${item.mrp.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-item-btn"><i class="bi bi-trash"></i></button>
                </td>
            `;

            itemsTableBody.appendChild(tr);

            const qtyInput = tr.querySelector('.qty-input');
            const errorLabel = tr.querySelector('.validation-error');
            const subtotalCell = tr.querySelector('.subtotal-cell');

            qtyInput.addEventListener('input', function() {
                const q = parseInt(this.value) || 0;
                if (q > item.available_qty) {
                    errorLabel.classList.remove('d-none');
                    tr.classList.add('table-danger');
                } else {
                    errorLabel.classList.add('d-none');
                    tr.classList.remove('table-danger');
                }

                const sub = q * item.mrp;
                subtotalCell.textContent =  sub.toFixed(2);
                recalculateTotal();
            });

            tr.querySelector('.remove-item-btn').addEventListener('click', function() {
                tr.remove();
                if (itemsTableBody.querySelectorAll('tr').length === 0) {
                    itemsTableBody.appendChild(emptyRowPlaceholder);
                }
                recalculateTotal();
            });

            rowIndex++;
            recalculateTotal();
        }

        // Recalculate Invoiced Running Totals
        function recalculateTotal() {
            let grossSubtotal = 0.00;
            let containsError = false;
            let rowsCount = 0;

            const rows = itemsTableBody.querySelectorAll('tr');
            rows.forEach(row => {
                if (row.id === 'emptyRowPlaceholder') return;
                rowsCount++;

                const qtyInput = row.querySelector('.qty-input');
                const qtyVal = parseInt(qtyInput.value) || 0;
                const stockVal = parseInt(row.querySelector('.stock-label').dataset.stock) || 0;
                const mrpVal = parseFloat(row.querySelector('td:nth-child(5)').textContent.replace('$', '')) || 0;

                grossSubtotal += qtyVal * mrpVal;

                if (qtyVal > stockVal || qtyVal < 1) {
                    containsError = true;
                }
            });

            grossSubtotalLabel.textContent =  grossSubtotal.toFixed(2);

            const discType = discountType.value;
            const discVal = parseFloat(discountValue.value) || 0;
            const taxVal = parseFloat(taxInput.value) || 0;

            let discountAmt = 0.00;
            if (discType === 'percentage') {
                discountAmt = (discVal / 100) * grossSubtotal;
            } else {
                discountAmt = Math.min(discVal, grossSubtotal);
            }

            const afterDiscount = grossSubtotal - discountAmt;
            const taxAmt = (taxVal / 100) * afterDiscount;
            const netGrandTotal = afterDiscount + taxAmt;

            netGrandTotalLabel.textContent =  netGrandTotal.toFixed(2);

            // Enable submit button only if invoice contains items and has no validation stock warnings
            if (rowsCount > 0 && !containsError) {
                saveInvoiceBtn.removeAttribute('disabled');
            } else {
                saveInvoiceBtn.setAttribute('disabled', 'disabled');
            }
        }

        discountType.addEventListener('change', recalculateTotal);
        discountValue.addEventListener('input', recalculateTotal);
        taxInput.addEventListener('input', recalculateTotal);
    });
</script>
@endsection
