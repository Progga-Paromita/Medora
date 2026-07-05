@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Add New Medicine</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/medicines') }}" class="text-decoration-none">Medicines</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <!-- Error list -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h4 class="card-title fw-bold mb-0">Medicine Information</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/medicines/create') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label fw-medium mb-2">Medicine Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" value="{{ old('name') }}" placeholder="e.g. Paracetamol" required>
                                        </div>
                                    </div>

                                    <!-- Generic Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="generic_name" class="form-label fw-medium mb-2">Generic Name <span class="text-danger">*</span></label>
                                            <input type="text" name="generic_name" class="form-control @error('generic_name') is-invalid @enderror" 
                                                   id="generic_name" value="{{ old('generic_name') }}" placeholder="e.g. Acetaminophen" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- SKU -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="sku" class="form-label fw-medium mb-2">SKU / Drug Code</label>
                                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                                                   id="sku" value="{{ old('sku') }}" placeholder="e.g. SKU-100293">
                                        </div>
                                    </div>

                                    <!-- Strength -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="strength" class="form-label fw-medium mb-2">Strength / Dosage</label>
                                            <input type="text" name="strength" class="form-control @error('strength') is-invalid @enderror" 
                                                   id="strength" value="{{ old('strength') }}" placeholder="e.g. 500mg, 10ml, 5%">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Packaging (Predefined selection) -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="packaging" class="form-label fw-medium mb-2">Packaging Type <span class="text-danger">*</span></label>
                                            <select name="packaging" id="packaging" class="form-select @error('packaging') is-invalid @enderror" required>
                                                <option value="">-- Select Packaging Type --</option>
                                                <option value="Tablet" {{ old('packaging') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                                <option value="Capsule" {{ old('packaging') == 'Capsule' ? 'selected' : '' }}>Capsule</option>
                                                <option value="Syrup" {{ old('packaging') == 'Syrup' ? 'selected' : '' }}>Syrup</option>
                                                <option value="Injection" {{ old('packaging') == 'Injection' ? 'selected' : '' }}>Injection</option>
                                                <option value="Ointment" {{ old('packaging') == 'Ointment' ? 'selected' : '' }}>Ointment</option>
                                                <option value="Cream" {{ old('packaging') == 'Cream' ? 'selected' : '' }}>Cream</option>
                                                <option value="Drops" {{ old('packaging') == 'Drops' ? 'selected' : '' }}>Drops</option>
                                                <option value="Suspension" {{ old('packaging') == 'Suspension' ? 'selected' : '' }}>Suspension</option>
                                                <option value="Powder" {{ old('packaging') == 'Powder' ? 'selected' : '' }}>Powder</option>
                                                <option value="Inhaler" {{ old('packaging') == 'Inhaler' ? 'selected' : '' }}>Inhaler</option>
                                                <option value="Other" {{ old('packaging') == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Supplier -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="supplier_id" class="form-label fw-medium mb-2">Supplier <span class="text-danger">*</span></label>
                                            <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                                <option value="">-- Select Supplier --</option>
                                                @foreach($getSuppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Prescription Required -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="prescription_required" class="form-label fw-medium mb-2">Prescription Required (Rx Only) <span class="text-danger">*</span></label>
                                            <select name="prescription_required" id="prescription_required" class="form-select" required>
                                                <option value="0" {{ old('prescription_required') == '0' ? 'selected' : '' }}>No (OTC Product)</option>
                                                <option value="1" {{ old('prescription_required') == '1' ? 'selected' : '' }}>Yes (Rx Only)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Storage Requirement -->
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label for="temperature_control" class="form-label fw-medium mb-2">Storage Temperature Requirement</label>
                                            <select name="temperature_control" id="temperature_control" class="form-select">
                                                <option value="Room Temp" {{ old('temperature_control') == 'Room Temp' ? 'selected' : '' }}>Standard Room Temp (15-25°C)</option>
                                                <option value="Refrigerated" {{ old('temperature_control') == 'Refrigerated' ? 'selected' : '' }}>Refrigerated (2-8°C)</option>
                                                <option value="Frozen" {{ old('temperature_control') == 'Frozen' ? 'selected' : '' }}>Frozen (Below -15°C)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <div class="form-group">
                                            <label for="profile_image" class="form-label fw-medium mb-2">Medicine Image / Label Photo</label>
                                            <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" 
                                                   id="profile_image" accept="image/*">
                                            <div class="form-text text-muted text-xs mt-1">Accepts jpeg, png, jpg, gif. Max size: 2MB.</div>
                                            
                                            <!-- JS Image Preview -->
                                            <div class="mt-3 d-none" id="previewContainer">
                                                <img src="#" id="imagePreview" alt="Medicine Label Preview" 
                                                     class="rounded border shadow-sm" style="height: 120px; width: 120px; object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <button type="reset" class="btn btn-outline-secondary" id="resetBtn">Reset</button>
                                    <a href="{{ url('admin/medicines') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Medicine</button>
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
        const imageInput = document.getElementById('profile_image');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const resetBtn = document.getElementById('resetBtn');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.setAttribute('src', e.target.result);
                    previewContainer.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('d-none');
            }
        });

        resetBtn.addEventListener('click', function() {
            previewContainer.classList.add('d-none');
        });
    });
</script>
@endsection