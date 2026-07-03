@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Inventory Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inventory Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            @include('message')

            <!-- Top statistics KPI row -->
            <div class="row mb-4">
                <!-- Total unique medicines -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="background: rgba(59, 130, 246, 0.08);">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-secondary small fw-medium text-uppercase">Total Medicines</span>
                                <h3 class="mb-0 fw-bold text-white mt-1">{{ $totalMedicines }}</h3>
                            </div>
                            <div class="rounded-circle p-3 bg-primary bg-opacity-25">
                                <i class="bi bi-capsule text-primary fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total stock units -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="background: rgba(14, 165, 233, 0.08);">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-secondary small fw-medium text-uppercase">Total Stock Units</span>
                                <h3 class="mb-0 fw-bold text-white mt-1">{{ number_format($totalStockUnits) }}</h3>
                            </div>
                            <div class="rounded-circle p-3 bg-info bg-opacity-25">
                                <i class="bi bi-boxes text-info fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Valuation -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="background: rgba(34, 197, 94, 0.08);">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-secondary small fw-medium text-uppercase">Inventory Valuation</span>
                                <h3 class="mb-0 fw-bold text-success mt-1">{{ number_format($inventoryValuation, 2) }}</h3>
                            </div>
                            <div class="rounded-circle p-3 bg-success bg-opacity-25">
                                <i class="bi bi-currency-dollar text-success fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent manual adjustments count -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="background: rgba(168, 85, 247, 0.08);">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-secondary small fw-medium text-uppercase">Adjustment logs</span>
                                <h3 class="mb-0 fw-bold text-white mt-1">{{ $recentAdjustments->count() }}</h3>
                            </div>
                            <div class="rounded-circle p-3 bg-purple bg-opacity-25" style="background-color: rgba(168, 85, 247, 0.25) !important;">
                                <i class="bi bi-sliders text-purple fs-3" style="color: #a855f7 !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Health Status / Alert row -->
            <div class="row mb-4">
                <!-- Low Stock Alert -->
                <div class="col-md-3 col-sm-6 mb-3">
                    <a href="{{ url('admin/inventory/low-stock') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm p-4 text-center h-100 transition-hover" style="background: rgba(234, 179, 8, 0.08);">
                            <i class="bi bi-exclamation-triangle text-warning fs-1 mb-2"></i>
                            <h4 class="fw-bold mb-1 text-white">{{ $lowStockCount }}</h4>
                            <span class="text-secondary small fw-medium text-uppercase">Low Stock Batches</span>
                        </div>
                    </a>
                </div>

                <!-- Out of Stock Alert -->
                <div class="col-md-3 col-sm-6 mb-3">
                    <a href="{{ url('admin/inventory/stock?stock_status=out_of_stock') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm p-4 text-center h-100 transition-hover" style="background: rgba(239, 68, 68, 0.08);">
                            <i class="bi bi-slash-circle text-danger fs-1 mb-2"></i>
                            <h4 class="fw-bold mb-1 text-white">{{ $outOfStockCount }}</h4>
                            <span class="text-secondary small fw-medium text-uppercase">Out of Stock Medicines</span>
                        </div>
                    </a>
                </div>

                <!-- Expired Medicines Alert -->
                <div class="col-md-3 col-sm-6 mb-3">
                    <a href="{{ url('admin/inventory/expired') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm p-4 text-center h-100 transition-hover" style="background: rgba(127, 29, 29, 0.15);">
                            <i class="bi bi-x-octagon text-danger fs-1 mb-2" style="color: #ef4444 !important;"></i>
                            <h4 class="fw-bold mb-1 text-white">{{ $expiredCount }}</h4>
                            <span class="text-secondary small fw-medium text-uppercase" style="color: #f87171 !important;">Expired Batches</span>
                        </div>
                    </a>
                </div>

                <!-- Near Expiry Alert -->
                <div class="col-md-3 col-sm-6 mb-3">
                    <a href="{{ url('admin/inventory/near-expiry') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm p-4 text-center h-100 transition-hover" style="background: rgba(249, 115, 22, 0.08);">
                            <i class="bi bi-clock text-warning fs-1 mb-2" style="color: #f97316 !important;"></i>
                            <h4 class="fw-bold mb-1 text-white">{{ $nearExpiryCount }}</h4>
                            <span class="text-secondary small fw-medium text-uppercase" style="color: #fdba74 !important;">Near Expiry Batches</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent manual adjustments audit log -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Recent Manual Adjustments Audit</h4>
                    @if(Auth::user()->is_role == 1)
                        <a href="{{ url('admin/inventory/adjust') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-sliders me-1"></i> Perform Adjustment
                        </a>
                    @endif
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Medicine</th>
                                    <th>Batch Number</th>
                                    <th>Type</th>
                                    <th>Quantity Adjusted</th>
                                    <th>Responsible User</th>
                                    <th>Reason / Justification</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAdjustments as $adj)
                                    <tr>
                                        <td>{{ $adj->created_at->format('M d, Y h:i A') }}</td>
                                        <td class="fw-bold text-white">{{ optional($adj->getMedicine)->name }}</td>
                                        <td><span class="badge bg-secondary">{{ optional($adj->getStock)->batch_id }}</span></td>
                                        <td>
                                            @if($adj->adjustment_type === 'increase')
                                                <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill">Increase</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill">Decrease</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold">{{ $adj->quantity }} units</td>
                                        <td>{{ optional($adj->getUser)->name }}</td>
                                        <td class="text-muted text-xs">{{ $adj->reason }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No manual stock adjustments recorded.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
