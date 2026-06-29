@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Edit Medicine</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/medicines') }}" class="text-decoration-none">Medicines</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h4 class="card-title fw-bold mb-0">Medicine Information</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/medicines/edit/' . $getRecord->id) }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label fw-medium mb-2">Medicine Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $getRecord->name) }}" placeholder="e.g. Paracetamol" required>
                                        </div>
                                    </div>

                                    <!-- SKU / Code -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="sku" class="form-label fw-medium mb-2">SKU / Barcode / Drug Code</label>
                                            <input type="text" name="sku" class="form-control" id="sku" value="{{ old('sku', $getRecord->sku) }}" placeholder="e.g. SKU-100293">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Generic Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="generic_name" class="form-label fw-medium mb-2">Generic Name</label>
                                            <input type="text" name="generic_name" class="form-control" id="generic_name" value="{{ old('generic_name', $getRecord->generic_name) }}" placeholder="e.g. Acetaminophen">
                                        </div>
                                    </div>

                                    <!-- Strength -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="strength" class="form-label fw-medium mb-2">Strength / Dosage</label>
                                            <input type="text" name="strength" class="form-control" id="strength" value="{{ old('strength', $getRecord->strength) }}" placeholder="e.g. 500mg, 10ml, 5%">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Category -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="category" class="form-label fw-medium mb-2">Form / Category</label>
                                            <select name="category" id="category" class="form-select">
                                                <option value="">-- Select Category --</option>
                                                <option value="Tablet" {{ $getRecord->category == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                                <option value="Syrup" {{ $getRecord->category == 'Syrup' ? 'selected' : '' }}>Syrup</option>
                                                <option value="Capsule" {{ $getRecord->category == 'Capsule' ? 'selected' : '' }}>Capsule</option>
                                                <option value="Injection" {{ $getRecord->category == 'Injection' ? 'selected' : '' }}>Injection</option>
                                                <option value="Ointment" {{ $getRecord->category == 'Ointment' ? 'selected' : '' }}>Ointment / Cream</option>
                                                <option value="Drops" {{ $getRecord->category == 'Drops' ? 'selected' : '' }}>Drops</option>
                                                <option value="Inhaler" {{ $getRecord->category == 'Inhaler' ? 'selected' : '' }}>Inhaler</option>
                                                <option value="Other" {{ $getRecord->category == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Packaging -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="packaging" class="form-label fw-medium mb-2">Packaging Type</label>
                                            <input type="text" name="packaging" class="form-control" id="packaging" value="{{ old('packaging', $getRecord->packaging) }}" placeholder="e.g. Strip of 10, Box of 100">
                                        </div>
                                    </div>

                                    <!-- Supplier -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="supplier_id" class="form-label fw-medium mb-2">Supplier</label>
                                            <select name="supplier_id" id="supplier_id" class="form-select">
                                                <option value="">-- Select Supplier --</option>
                                                @foreach($getSuppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" {{ $getRecord->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Temperature Control -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="temperature_control" class="form-label fw-medium mb-2">Storage Temperature Requirement</label>
                                            <select name="temperature_control" id="temperature_control" class="form-select">
                                                <option value="Room Temp" {{ $getRecord->temperature_control == 'Room Temp' ? 'selected' : '' }}>Standard Room Temp (15-25°C)</option>
                                                <option value="Refrigerated" {{ $getRecord->temperature_control == 'Refrigerated' ? 'selected' : '' }}>Refrigerated (2-8°C)</option>
                                                <option value="Frozen" {{ $getRecord->temperature_control == 'Frozen' ? 'selected' : '' }}>Frozen (Below -15°C)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Prescription Required -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="prescription_required" class="form-label fw-medium mb-2">Prescription Required (Rx Only)</label>
                                            <select name="prescription_required" id="prescription_required" class="form-select">
                                                <option value="0" {{ $getRecord->prescription_required == 0 ? 'selected' : '' }}>No (Over-The-Counter / OTC)</option>
                                                <option value="1" {{ $getRecord->prescription_required == 1 ? 'selected' : '' }}>Yes (Requires Doctor Prescription)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <div class="form-group">
                                            <label for="profile_image" class="form-label fw-medium mb-2">Medicine Image / Label Photo</label>
                                            <input type="file" name="profile_image" class="form-control mb-2" id="profile_image" accept="image/*">
                                            @if($getRecord->profile_image)
                                                <div class="mt-2">
                                                    <img src="{{ url('uploads/medicines/' . $getRecord->profile_image) }}" alt="Preview" class="rounded border shadow-sm" style="max-height: 80px;">
                                                </div>
                                            @endif
                                            <div class="form-text text-muted text-xs mt-1">Provide a high-quality product photo or box labeling. Max size: 2MB.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <a href="{{ url('admin/medicines') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Medicine</button>
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