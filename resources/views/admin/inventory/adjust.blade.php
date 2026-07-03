@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Manual Stock Adjustment</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/inventory/dashboard') }}" class="text-decoration-none">Inventory Control</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manual Adjust</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-7 mx-auto">
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

                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h4 class="card-title fw-bold mb-0">Modify Inventory Levels Manually</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/inventory/adjust') }}" method="POST" id="adjustmentForm">
                                @csrf

                                <!-- Medicine -->
                                <div class="mb-3">
                                    <label for="medicine_select" class="form-label fw-medium mb-2">Select Medicine Product <span class="text-danger">*</span></label>
                                    <select name="medicine_id" id="medicine_select" class="form-select" required>
                                        <option value="">-- Choose Medicine --</option>
                                        @foreach($medicines as $med)
                                            <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->generic_name }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Stock Batch (loaded dynamically via JS) -->
                                <div class="mb-3">
                                    <label for="stock_select" class="form-label fw-medium mb-2">Select Stock Batch <span class="text-danger">*</span></label>
                                    <select name="stock_id" id="stock_select" class="form-select" required disabled>
                                        <option value="">-- Select batch --</option>
                                    </select>
                                    <div class="form-text text-muted text-xs mt-1" id="current_qty_info">
                                        Choose a medicine first to view available batches.
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Adjustment Type -->
                                    <div class="col-md-6 mb-3">
                                        <label for="adjustment_type" class="form-label fw-medium mb-2">Adjustment Action <span class="text-danger">*</span></label>
                                        <select name="adjustment_type" id="adjustment_type" class="form-select" required>
                                            <option value="increase">Increase Stock (+)</option>
                                            <option value="decrease">Decrease Stock (-)</option>
                                        </select>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-md-6 mb-3">
                                        <label for="quantity" class="form-label fw-medium mb-2">Adjust Quantity <span class="text-danger">*</span></label>
                                        <input type="number" min="1" name="quantity" id="quantity" class="form-control" placeholder="e.g. 50" required>
                                    </div>
                                </div>

                                <!-- Reason -->
                                <div class="mb-4">
                                    <label for="reason" class="form-label fw-medium mb-2">Reason / Justification <span class="text-danger">*</span></label>
                                    <textarea name="reason" id="reason" rows="4" class="form-control" 
                                              placeholder="Provide detail (e.g. Damaged medicines, Lost medicines, Returned medicines, Physical inventory mismatch)" required></textarea>
                                </div>

                                <div class="d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <a href="{{ url('admin/inventory/dashboard') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Apply Adjustment</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        const medSelect = document.getElementById('medicine_select');
        const stockSelect = document.getElementById('stock_select');
        const qtyInfo = document.getElementById('current_qty_info');

        medSelect.addEventListener('change', function() {
            const medId = this.value;
            stockSelect.innerHTML = '<option value="">-- Loading batches... --</option>';
            stockSelect.setAttribute('disabled', 'disabled');
            qtyInfo.textContent = 'Choose a medicine first to view available batches.';

            if (!medId) {
                stockSelect.innerHTML = '<option value="">-- Select batch --</option>';
                return;
            }

            fetch("{{ url('admin/inventory/batches') }}?medicine_id=" + medId)
                .then(res => res.json())
                .then(data => {
                    stockSelect.innerHTML = '<option value="">-- Choose Batch --</option>';
                    if (data.length === 0) {
                        qtyInfo.innerHTML = '<span class="text-danger">No batches found in stock for this medicine. You must purchase or add stock first.</span>';
                        return;
                    }

                    data.forEach(batch => {
                        const opt = document.createElement('option');
                        opt.value = batch.id;
                        opt.textContent = `Batch: ${batch.batch_id} (Avail Qty: ${batch.quantity}) | Expiry: ${batch.expiry_date} | MRP: ${batch.mrp.toFixed(2)}`;
                        opt.dataset.qty = batch.quantity;
                        stockSelect.appendChild(opt);
                    });

                    stockSelect.removeAttribute('disabled');
                    qtyInfo.textContent = 'Select a batch from the list above to view details.';
                })
                .catch(err => {
                    console.error(err);
                    stockSelect.innerHTML = '<option value="">-- Error loading batches --</option>';
                });
        });

        stockSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                qtyInfo.innerHTML = `Selected Batch Quantity: <strong class="text-white">${opt.dataset.qty}</strong> units.`;
            } else {
                qtyInfo.textContent = 'Select a batch from the list above to view details.';
            }
        });
    });
</script>
@endsection
