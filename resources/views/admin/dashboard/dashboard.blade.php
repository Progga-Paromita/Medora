@extends('layouts.app')

@section('content')
<main class="app-main py-4">
    <!-- Header Block -->
    <div class="app-content-header mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h2 class="mb-0 fw-bold text-white tracking-tight" style="font-size: 28px;">
                        Medora <span class="fw-normal" style="color: #FACA5A;">Console</span>
                    </h2>
                    <p class="text-muted text-sm mb-0">Central dashboard for monitoring pharmacy analytics and operational systems.</p>
                </div>
                <div class="col-sm-6 text-sm-end d-flex justify-content-end align-items-center gap-3">
                    <!-- Digital Clock Widget -->
                    <div class="border rounded px-3 py-1 text-start" style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08) !important;">
                        <small class="text-muted d-block text-xs uppercase" style="font-size: 9px; letter-spacing: 1px;">Live Server Clock</small>
                        <span id="digitalClock" class="fw-bold text-white" style="font-family: monospace; font-size: 14px;">--:--:-- --</span>
                    </div>
                    <a href="{{ url('admin/invoices/create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus me-1"></i> New Invoice</a>
                </div>
            </div>
        </div>
    </div>

    <!-- App Content Content -->
    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <!-- KPI Cards Grid (12 Glassmorphic Cards) -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
                <!-- 1. Total Medicines -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm p-4 card-glass" style="background: rgba(59, 130, 246, 0.06);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small fw-medium text-uppercase text-white">Total Medicines</span>
                            <div class="rounded-circle p-2 bg-primary bg-opacity-25 text-primary">
                                <i class="bi bi-capsule fs-4"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-white mb-0">{{ number_format($totalMedicines) }}</h2>
                        <span class="text-muted small mt-2 d-block">Registered catalog items</span>
                    </div>
                </div>

                <!-- 2. Total Customers -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm p-4 card-glass" style="background: rgba(34, 197, 94, 0.06);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small fw-medium text-uppercase text-white">Total Customers</span>
                            <div class="rounded-circle p-2 bg-success bg-opacity-25 text-success">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-white mb-0">{{ number_format($totalCustomers) }}</h2>
                        <span class="text-muted small mt-2 d-block">Unique accounts</span>
                    </div>
                </div>

                <!-- 3. Total Suppliers -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm p-4 card-glass" style="background: rgba(234, 179, 8, 0.06);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small fw-medium text-uppercase text-white">Total Suppliers</span>
                            <div class="rounded-circle p-2 bg-warning bg-opacity-25 text-warning">
                                <i class="bi bi-truck fs-4"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-white mb-0">{{ number_format($totalSuppliers) }}</h2>
                        <span class="text-muted small mt-2 d-block">Procurement partners</span>
                    </div>
                </div>

                <!-- 4. System Users -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm p-4 card-glass" style="background: rgba(168, 85, 247, 0.06);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small fw-medium text-uppercase text-white">System Users</span>
                            <div class="rounded-circle p-2 bg-purple bg-opacity-25 text-purple" style="color: #a855f7;">
                                <i class="bi bi-person-badge fs-4"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-white mb-0">{{ number_format($totalUsers) }}</h2>
                        <span class="text-muted small mt-2 d-block">Authorized staff members</span>
                    </div>
                </div>

                <!-- 5. Total Purchases -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm p-4 card-glass" style="background: rgba(239, 68, 68, 0.06);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small fw-medium text-uppercase text-white">Purchases</span>
                            <div class="rounded-circle p-2 bg-danger bg-opacity-25 text-danger">
                                <i class="bi bi-cart-plus fs-4"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-white mb-0">{{ number_format($totalPurchases) }}</h2>
                        <span class="text-muted small mt-2 d-block">Procurement vouchers</span>
                    </div>
                </div>

                <!-- 6. Total Sales -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm p-4 card-glass" style="background: rgba(59, 130, 246, 0.06);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small fw-medium text-uppercase text-white">Sales Invoices</span>
                            <div class="rounded-circle p-2 bg-primary bg-opacity-25 text-primary">
                                <i class="bi bi-receipt fs-4"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-white mb-0">{{ number_format($totalSales) }}</h2>
                        <span class="text-muted small mt-2 d-block">Completed invoices</span>
                    </div>
                </div>

                <!-- 7. Revenue -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm p-4 card-glass" style="background: rgba(34, 197, 94, 0.06);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small fw-medium text-uppercase text-white">Net Revenue</span>
                            <div class="rounded-circle p-2 bg-success bg-opacity-25 text-success">
                                <i class="bi bi-wallet2 fs-4"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-success mb-0">${{ number_format($totalRevenue, 2) }}</h2>
                        <span class="text-muted small mt-2 d-block">Sales turnover total</span>
                    </div>
                </div>

                <!-- 8. Calculated Profit -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm p-4 card-glass" style="background: rgba(59, 130, 246, 0.06);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small fw-medium text-uppercase text-white">Calculated Profit</span>
                            <div class="rounded-circle p-2 bg-primary bg-opacity-25 text-primary">
                                <i class="bi bi-cash-coin fs-4"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-primary mb-0" style="color: #3b82f6 !important;">${{ number_format($totalProfit, 2) }}</h2>
                        <span class="text-muted small mt-2 d-block">Invoice gross margins</span>
                    </div>
                </div>

                <!-- 9. Inventory Value -->
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm p-4 card-glass" style="background: rgba(234, 179, 8, 0.06);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-secondary small fw-medium text-uppercase text-white">Asset Value</span>
                            <div class="rounded-circle p-2 bg-warning bg-opacity-25 text-warning">
                                <i class="bi bi-boxes fs-4"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold text-warning mb-0">${{ number_format($totalValuation, 2) }}</h2>
                        <span class="text-muted small mt-2 d-block">Stock valuation at cost</span>
                    </div>
                </div>

                <!-- 10. Low Stock Alerts -->
                <div class="col">
                    <a href="{{ url('admin/inventory/low-stock') }}" class="text-decoration-none h-100 d-block">
                        <div class="card h-100 border-0 shadow-sm p-4 card-glass bg-warning bg-opacity-10 border-warning" style="border: 1px solid rgba(234,179,8,0.2) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-warning small fw-medium text-uppercase">Low Stock</span>
                                <div class="rounded-circle p-2 bg-warning bg-opacity-25 text-warning">
                                    <i class="bi bi-exclamation-triangle fs-4"></i>
                                </div>
                            </div>
                            <h2 class="fw-bold text-warning mb-0">{{ $lowStock }}</h2>
                            <span class="text-muted small mt-2 d-block text-white-50">Batches needing restock</span>
                        </div>
                    </a>
                </div>

                <!-- 11. Expired Medicines -->
                <div class="col">
                    <a href="{{ url('admin/inventory/expired') }}" class="text-decoration-none h-100 d-block">
                        <div class="card h-100 border-0 shadow-sm p-4 card-glass bg-danger bg-opacity-10 border-danger" style="border: 1px solid rgba(239,68,68,0.2) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-danger small fw-medium text-uppercase">Expired</span>
                                <div class="rounded-circle p-2 bg-danger bg-opacity-25 text-danger">
                                    <i class="bi bi-calendar-x fs-4"></i>
                                </div>
                            </div>
                            <h2 class="fw-bold text-danger mb-0">{{ $expiredMedicines }}</h2>
                            <span class="text-muted small mt-2 d-block text-white-50">Batches needing removal</span>
                        </div>
                    </a>
                </div>

                <!-- 12. Near Expiry -->
                <div class="col">
                    <a href="{{ url('admin/inventory/near-expiry') }}" class="text-decoration-none h-100 d-block">
                        <div class="card h-100 border-0 shadow-sm p-4 card-glass bg-warning bg-opacity-10 border-warning" style="border: 1px solid rgba(234,179,8,0.2) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-warning small fw-medium text-uppercase">Near Expiry</span>
                                <div class="rounded-circle p-2 bg-warning bg-opacity-25 text-warning">
                                    <i class="bi bi-clock fs-4"></i>
                                </div>
                            </div>
                            <h2 class="fw-bold text-warning mb-0">{{ $nearExpiry }}</h2>
                            <span class="text-muted small mt-2 d-block text-white-50">Expiring in next 30 days</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Quick Action Shortcut Buttons -->
            <div class="card shadow-sm border-0 mb-5">
                <div class="card-header p-4">
                    <h5 class="fw-bold mb-0 text-white">System Actions Shortcuts</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-6 g-3">
                        <div class="col">
                            <a href="{{ url('admin/medicines/create') }}" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-2">
                                <i class="bi bi-capsule fs-3"></i> Add Medicine
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ url('admin/purchases/add') }}" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center gap-2">
                                <i class="bi bi-cart-plus fs-3"></i> Log Purchase
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ url('admin/invoices/create') }}" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-2">
                                <i class="bi bi-receipt fs-3"></i> Sell Invoice
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ url('admin/customers/create') }}" class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center gap-2">
                                <i class="bi bi-people fs-3"></i> Add Customer
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ url('admin/suppliers/create') }}" class="btn btn-outline-warning w-100 py-3 d-flex flex-column align-items-center gap-2">
                                <i class="bi bi-truck fs-3"></i> Add Supplier
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ url('admin/reports/dashboard') }}" class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center gap-2 text-white">
                                <i class="bi bi-file-earmark-bar-graph fs-3"></i> View Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-5">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header p-4">
                            <h5 class="fw-bold mb-0 text-white">Monthly Cashflow (Sales vs Purchases)</h5>
                        </div>
                        <div class="card-body p-4">
                            <div style="height: 350px;">
                                <canvas id="dashboardCashflowChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header p-4">
                            <h5 class="fw-bold mb-0 text-white">Inventory distribution</h5>
                        </div>
                        <div class="card-body p-4 d-flex align-items-center justify-content-center">
                            <div style="height: 300px; width: 100%;">
                                <canvas id="dashboardDistChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit Trail Timeline and Real-time Alerts Drawer -->
            <div class="row g-4">
                <!-- Activity Logs Timeline -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header p-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-white">Recent System Activities Feed</h5>
                            @if(Auth::user()->is_role == 1)
                                <a href="{{ url('admin/activity-logs') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">View All Logs</a>
                            @endif
                        </div>
                        <div class="card-body p-4">
                            <div class="timeline">
                                @forelse($recentActivities as $act)
                                    <div class="d-flex align-items-start mb-4">
                                        <div class="p-1.5 rounded-circle bg-primary me-3 mt-1" style="width: 10px; height: 10px; box-shadow: 0 0 6px #3b82f6;"></div>
                                        <div>
                                            <p class="mb-0 text-sm text-white-50">
                                                <strong>{{ $act->user_name ?? 'System' }}</strong>: {{ $act->action }}
                                            </p>
                                            <span class="text-xs text-muted">{{ date('M d, Y h:i A', strtotime($act->created_at)) }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted text-center py-4">No system activities logged yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Alerts drawer -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header p-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-white">Active System Notifications</h5>
                            <a href="{{ url('admin/inventory/notifications') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Alert Center</a>
                        </div>
                        <div class="card-body p-4">
                            @forelse($alerts as $alert)
                                <div class="d-flex align-items-center p-3 mb-3 border rounded" style="border-color: var(--bs-border-color) !important;">
                                    <i class="bi bi-bell-fill me-3 fs-5 {{ $alert['class'] }}"></i>
                                    <div>
                                        <span class="fw-bold d-block text-xs uppercase text-white">{{ $alert['type'] }}</span>
                                        <span class="text-muted small">{!! $alert['message'] !!}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-shield-check text-success fs-1"></i>
                                    <h6 class="fw-bold text-white mt-3">All Systems Nominal</h6>
                                    <p class="text-xs mb-0">No active stock or payment alerts flagged.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Server Digital Clock
        function updateClock() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            let seconds = now.getSeconds();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            const strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
            document.getElementById('digitalClock').textContent = strTime;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Colors & theme options
        const fontColor = '#94a3b8';
        const gridColor = 'rgba(148, 163, 184, 0.08)';

        // 1. Cashflow Chart
        const cashCtx = document.getElementById('dashboardCashflowChart').getContext('2d');
        new Chart(cashCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Sales Revenue ($)',
                        data: {!! $monthlySalesJSON !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderColor: '#22c55e',
                        borderWidth: 1
                    },
                    {
                        label: 'Purchases Cost ($)',
                        data: {!! $monthlyPurchasesJSON !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: '#ef4444',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: fontColor } }
                },
                scales: {
                    x: {
                        grid: { color: gridColor },
                        ticks: { color: fontColor }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: { color: fontColor }
                    }
                }
            }
        });

        // 2. Inventory Distribution Chart
        const distCtx = document.getElementById('dashboardDistChart').getContext('2d');
        new Chart(distCtx, {
            type: 'pie',
            data: {
                labels: {!! $distMedNames !!},
                datasets: [{
                    data: {!! $distMedQty !!},
                    backgroundColor: [
                        '#3b82f6', '#22c55e', '#f59e0b', '#ef4444', 
                        '#a855f7', '#06b6d4', '#ec4899', '#6366f1'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: fontColor, boxWidth: 10 }
                    }
                }
            }
        });
    });
</script>
@endsection
