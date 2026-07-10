@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">System Administration Settings</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            @include('message')

            <div class="card shadow-sm border-0">
                <div class="card-header p-4">
                    <h4 class="card-title fw-bold mb-0 text-white">Medora Configuration Console</h4>
                </div>
                <div class="card-body p-4">
                    <form action="" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Sidebar/Navigation Tabs -->
                            <div class="col-md-3 border-end">
                                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active text-start py-3 fw-bold" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab" aria-controls="v-pills-general" aria-selected="true">
                                        <i class="bi bi-shop me-2 text-primary"></i> Pharmacy Info
                                    </button>
                                    <button class="nav-link text-start py-3 fw-bold" id="v-pills-business-tab" data-bs-toggle="pill" data-bs-target="#v-pills-business" type="button" role="tab" aria-controls="v-pills-business" aria-selected="false">
                                        <i class="bi bi-wallet2 me-2 text-success"></i> Business & Finance
                                    </button>
                                    <button class="nav-link text-start py-3 fw-bold" id="v-pills-inventory-tab" data-bs-toggle="pill" data-bs-target="#v-pills-inventory" type="button" role="tab" aria-controls="v-pills-inventory" aria-selected="false">
                                        <i class="bi bi-sliders me-2 text-warning"></i> Inventory Controls
                                    </button>
                                    <button class="nav-link text-start py-3 fw-bold" id="v-pills-invoices-tab" data-bs-toggle="pill" data-bs-target="#v-pills-invoices" type="button" role="tab" aria-controls="v-pills-invoices" aria-selected="false">
                                        <i class="bi bi-hash me-2 text-info"></i> Code Prefixes
                                    </button>
                                    <button class="nav-link text-start py-3 fw-bold" id="v-pills-theme-tab" data-bs-toggle="pill" data-bs-target="#v-pills-theme" type="button" role="tab" aria-controls="v-pills-theme" aria-selected="false">
                                        <i class="bi bi-palette me-2 text-purple" style="color: #a855f7;"></i> Theme & Look
                                    </button>
                                </div>
                            </div>

                            <!-- Tab Contents -->
                            <div class="col-md-9 p-4">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <!-- 1. Pharmacy Info Tab -->
                                    <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab" tabindex="0">
                                        <h5 class="fw-bold mb-4 text-white">Pharmacy Information</h5>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label for="pharmacy_name" class="form-label small fw-medium">Pharmacy / Company Name</label>
                                                <input type="text" name="pharmacy_name" id="pharmacy_name" class="form-control" value="{{ old('pharmacy_name', $pharmacy_name) }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="phone" class="form-label small fw-medium">Pharmacy Phone Number</label>
                                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $phone) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label small fw-medium">Pharmacy Email Address</label>
                                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $email) }}">
                                            </div>
                                            <div class="col-md-12">
                                                <label for="website" class="form-label small fw-medium">Pharmacy Website</label>
                                                <input type="text" name="website" id="website" class="form-control" value="{{ old('website', $website) }}">
                                            </div>
                                            <div class="col-md-12">
                                                <label for="address" class="form-label small fw-medium">Physical Address</label>
                                                <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $address) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 2. Business & Finance Tab -->
                                    <div class="tab-pane fade" id="v-pills-business" role="tabpanel" aria-labelledby="v-pills-business-tab" tabindex="0">
                                        <h5 class="fw-bold mb-4 text-white">Business Settings</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="currency" class="form-label small fw-medium">Currency Symbol</label>
                                                <input type="text" name="currency" id="currency" class="form-control" value="{{ old('currency', $currency) }}" placeholder="e.g. $, €, Tk" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="tax_percentage" class="form-label small fw-medium">Tax Percentage (%)</label>
                                                <input type="number" step="0.01" name="tax_percentage" id="tax_percentage" class="form-control" value="{{ old('tax_percentage', $tax_percentage) }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 3. Inventory Controls Tab -->
                                    <div class="tab-pane fade" id="v-pills-inventory" role="tabpanel" aria-labelledby="v-pills-inventory-tab" tabindex="0">
                                        <h5 class="fw-bold mb-4 text-white">Inventory Controls Thresholds</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="low_stock_threshold" class="form-label small fw-medium">Low Stock Warning Limit (units)</label>
                                                <input type="number" name="low_stock_threshold" id="low_stock_threshold" class="form-control" value="{{ old('low_stock_threshold', $low_stock_threshold) }}" required>
                                                <div class="form-text text-muted">Batches with quantities falling below this limit trigger alerts.</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="expiry_alert_days" class="form-label small fw-medium">Near-Expiry Warning Limit (days)</label>
                                                <input type="number" name="expiry_alert_days" id="expiry_alert_days" class="form-control" value="{{ old('expiry_alert_days', $expiry_alert_days) }}" required>
                                                <div class="form-text text-muted">Batches expiring within this number of days will alert.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 4. Code Prefixes Tab -->
                                    <div class="tab-pane fade" id="v-pills-invoices" role="tabpanel" aria-labelledby="v-pills-invoices-tab" tabindex="0">
                                        <h5 class="fw-bold mb-4 text-white">Transaction Prefix Codes</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="invoice_prefix" class="form-label small fw-medium">Sales Invoice Prefix</label>
                                                <input type="text" name="invoice_prefix" id="invoice_prefix" class="form-control" value="{{ old('invoice_prefix', $invoice_prefix) }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="purchase_prefix" class="form-label small fw-medium">Purchase Order Prefix</label>
                                                <input type="text" name="purchase_prefix" id="purchase_prefix" class="form-control" value="{{ old('purchase_prefix', $purchase_prefix) }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 5. Theme Settings Tab -->
                                    <div class="tab-pane fade" id="v-pills-theme" role="tabpanel" aria-labelledby="v-pills-theme-tab" tabindex="0">
                                        <h5 class="fw-bold mb-4 text-white">Theme & Display Settings</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="theme" class="form-label small fw-medium">Active Interface Theme</label>
                                                <select name="theme" id="theme" class="form-select">
                                                    <option value="dark" {{ $theme === 'dark' ? 'selected' : '' }}>Dark Mode</option>
                                                    <option value="light" {{ $theme === 'light' ? 'selected' : '' }}>Light Mode</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="text-end px-4 mt-3">
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Save System Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
