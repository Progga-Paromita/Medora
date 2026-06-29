@extends('layouts.app')

@section('content')
<main class="app-main py-4">
    <!--begin::App Content Header-->
    <div class="app-content-header mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h2 class="mb-0 fw-bold text-white tracking-tight" style="font-size: 28px;">
                        Medora <span class="fw-normal" style="color: #FACA5A;">Analytics</span>
                    </h2>
                    <p class="text-muted text-sm mb-0">Futuristic pharmacy inventory monitoring and performance intelligence.</p>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <div class="btn-group shadow-sm">
                        <button class="btn btn-secondary btn-sm"><i class="bi bi-download me-1"></i> Export Report</button>
                        <a href="{{ url('admin/invoices/create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus me-1"></i> New Invoice</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <!-- Statistics Grid (9 Cards) -->
            <div class="row mb-4">
                <!-- 1. Total Medicines -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-secondary fw-semibold text-sm">Total Medicines</span>
                                <div class="icon-shape p-2 rounded-3" style="background: rgba(250, 202, 90, 0.12); color: #FACA5A; box-shadow: 0 0 10px rgba(250, 202, 90, 0.2);">
                                    <i class="bi bi-capsule fs-4"></i>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h2 class="fw-bold mb-0 text-white">{{ $totalMedicines }}</h2>
                                <span class="text-success text-sm fw-medium"><i class="bi bi-arrow-up-right me-1"></i>+4.2% active</span>
                            </div>
                            <div class="mt-3">
                                <svg class="w-100" height="35" viewBox="0 0 100 30" preserveAspectRatio="none">
                                    <path d="M0,25 Q15,5 30,20 T60,10 T90,22 T100,5" fill="none" stroke="#FACA5A" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Total Stock -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-secondary fw-semibold text-sm">Total Stock</span>
                                <div class="icon-shape p-2 rounded-3" style="background: rgba(250, 196, 70, 0.12); color: #FAC446; box-shadow: 0 0 10px rgba(250, 196, 70, 0.2);">
                                    <i class="bi bi-boxes fs-4"></i>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h2 class="fw-bold mb-0 text-white">{{ number_format($totalStock) }}</h2>
                                <span class="text-success text-sm fw-medium"><i class="bi bi-arrow-up-right me-1"></i>+1.8% inventory</span>
                            </div>
                            <div class="mt-3">
                                <svg class="w-100" height="35" viewBox="0 0 100 30" preserveAspectRatio="none">
                                    <path d="M0,15 Q20,25 40,5 T70,22 T100,10" fill="none" stroke="#FAC446" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Total Suppliers -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-secondary fw-semibold text-sm">Total Suppliers</span>
                                <div class="icon-shape p-2 rounded-3" style="background: rgba(242, 181, 39, 0.12); color: #F2B527; box-shadow: 0 0 10px rgba(242, 181, 39, 0.2);">
                                    <i class="bi bi-truck fs-4"></i>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h2 class="fw-bold mb-0 text-white">{{ $totalSuppliers }}</h2>
                                <span class="text-secondary text-sm fw-medium"><i class="bi bi-dash me-1"></i>Stable partners</span>
                            </div>
                            <div class="mt-3">
                                <svg class="w-100" height="35" viewBox="0 0 100 30" preserveAspectRatio="none">
                                    <path d="M0,20 L20,20 L40,10 L60,25 L80,15 L100,15" fill="none" stroke="#F2B527" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Total Customers -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-secondary fw-semibold text-sm">Total Customers</span>
                                <div class="icon-shape p-2 rounded-3" style="background: rgba(250, 202, 90, 0.12); color: #FACA5A; box-shadow: 0 0 10px rgba(250, 202, 90, 0.2);">
                                    <i class="bi bi-people-fill fs-4"></i>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h2 class="fw-bold mb-0 text-white">{{ $totalCustomers }}</h2>
                                <span class="text-success text-sm fw-medium"><i class="bi bi-arrow-up-right me-1"></i>+8.4% growth</span>
                            </div>
                            <div class="mt-3">
                                <svg class="w-100" height="35" viewBox="0 0 100 30" preserveAspectRatio="none">
                                    <path d="M0,22 Q15,5 40,25 T80,10 T100,5" fill="none" stroke="#FACA5A" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Total Sales -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-secondary fw-semibold text-sm">Total Sales</span>
                                <div class="icon-shape p-2 rounded-3" style="background: rgba(250, 196, 70, 0.12); color: #FAC446; box-shadow: 0 0 10px rgba(250, 196, 70, 0.2);">
                                    <i class="bi bi-receipt fs-4"></i>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h2 class="fw-bold mb-0 text-white">{{ $totalSales }}</h2>
                                <span class="text-success text-sm fw-medium"><i class="bi bi-arrow-up-right me-1"></i>+12.1% orders</span>
                            </div>
                            <div class="mt-3">
                                <svg class="w-100" height="35" viewBox="0 0 100 30" preserveAspectRatio="none">
                                    <path d="M0,25 L15,15 L35,28 L60,10 L85,22 L100,5" fill="none" stroke="#FAC446" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. Revenue -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-secondary fw-semibold text-sm">Revenue</span>
                                <div class="icon-shape p-2 rounded-3" style="background: rgba(34, 197, 94, 0.12); color: #22C55E; box-shadow: 0 0 10px rgba(34, 197, 94, 0.2);">
                                    <i class="bi bi-cash-coin fs-4"></i>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h2 class="fw-bold mb-0 text-white">${{ number_format($totalRevenue, 2) }}</h2>
                                <span class="text-success text-sm fw-medium"><i class="bi bi-arrow-up-right me-1"></i>+15.3% revenue</span>
                            </div>
                            <div class="mt-3">
                                <svg class="w-100" height="35" viewBox="0 0 100 30" preserveAspectRatio="none">
                                    <path d="M0,25 Q25,-5 50,15 T100,5" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 7. Purchases -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-secondary fw-semibold text-sm">Purchases</span>
                                <div class="icon-shape p-2 rounded-3" style="background: rgba(242, 181, 39, 0.12); color: #F2B527; box-shadow: 0 0 10px rgba(242, 181, 39, 0.2);">
                                    <i class="bi bi-cart-check-fill fs-4"></i>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h2 class="fw-bold mb-0 text-white">{{ $totalPurchases }}</h2>
                                <span class="text-success text-sm fw-medium"><i class="bi bi-arrow-up-right me-1"></i>+5% supply incoming</span>
                            </div>
                            <div class="mt-3">
                                <svg class="w-100" height="35" viewBox="0 0 100 30" preserveAspectRatio="none">
                                    <path d="M0,20 Q30,10 50,25 T100,5" fill="none" stroke="#F2B527" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 8. Low Stock -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-secondary fw-semibold text-sm">Low Stock Alert</span>
                                <div class="icon-shape p-2 rounded-3" style="background: rgba(239, 68, 68, 0.12); color: #EF4444; box-shadow: 0 0 10px rgba(239, 68, 68, 0.2);">
                                    <i class="bi bi-exclamation-triangle fs-4"></i>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h2 class="fw-bold mb-0 text-danger">{{ $lowStock }}</h2>
                                <span class="text-danger text-sm fw-medium"><i class="bi bi-arrow-down me-1"></i>Needs restock</span>
                            </div>
                            <div class="mt-3">
                                <svg class="w-100" height="35" viewBox="0 0 100 30" preserveAspectRatio="none">
                                    <path d="M0,10 L30,22 L60,8 L100,28" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 9. Expired Medicines -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-secondary fw-semibold text-sm">Expired Medicines</span>
                                <div class="icon-shape p-2 rounded-3" style="background: rgba(239, 68, 68, 0.12); color: #EF4444; box-shadow: 0 0 10px rgba(239, 68, 68, 0.2);">
                                    <i class="bi bi-calendar-x fs-4"></i>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h2 class="fw-bold mb-0 text-danger">{{ $expiredMedicines }}</h2>
                                <span class="text-danger text-sm fw-medium"><i class="bi bi-trash me-1"></i>Awaiting removal</span>
                            </div>
                            <div class="mt-3">
                                <svg class="w-100" height="35" viewBox="0 0 100 30" preserveAspectRatio="none">
                                    <path d="M0,5 Q20,25 50,15 T100,25" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts & Quick Actions -->
            <div class="row mb-4">
                <div class="col-lg-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header border-0 d-flex justify-content-between align-items-center p-4">
                            <h5 class="mb-0 fw-bold text-white">Sales & Purchases Analytics</h5>
                            <span class="text-muted text-sm">Real-time performance</span>
                        </div>
                        <div class="card-body p-4">
                            <div id="analytics-chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <!-- Quick Actions -->
                    <div class="card h-100">
                        <div class="card-header border-0 p-4">
                            <h5 class="mb-0 fw-bold text-white">Quick Actions</h5>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="d-grid gap-3">
                                <a href="{{ url('admin/medicines/create') }}" class="btn btn-primary text-start d-flex justify-content-between align-items-center py-3">
                                    <span><i class="bi bi-plus-circle-fill me-2"></i> Add New Medicine</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                                <a href="{{ url('admin/invoices/create') }}" class="btn btn-secondary text-start d-flex justify-content-between align-items-center py-3 text-white">
                                    <span><i class="bi bi-receipt me-2"></i> Generate Invoice (Sale)</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                                <a href="{{ url('admin/stocks/create') }}" class="btn btn-secondary text-start d-flex justify-content-between align-items-center py-3 text-white">
                                    <span><i class="bi bi-box-seam me-2"></i> Record Stock Entry</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.08);">
                            
                            <h6 class="fw-bold text-white mb-3">System Activity Logs</h6>
                            <div class="d-flex align-items-start mb-3">
                                <div class="p-1 rounded-circle bg-warning me-2 mt-1" style="width: 8px; height: 8px; box-shadow: 0 0 6px #FACA5A;"></div>
                                <div>
                                    <p class="mb-0 text-sm text-white-50">Database seed successful.</p>
                                    <span class="text-xs text-muted">Just now</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <div class="p-1 rounded-circle bg-success me-2 mt-1" style="width: 8px; height: 8px; box-shadow: 0 0 6px #22C55E;"></div>
                                <div>
                                    <p class="mb-0 text-sm text-white-50">Secure session established.</p>
                                    <span class="text-xs text-muted">5 mins ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-0 p-4 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-white">Recent Inventory Stock Entries</h5>
                            <a href="{{ url('admin/stocks') }}" class="btn btn-secondary btn-sm">View All Stock</a>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Batch ID</th>
                                            <th>Expiry Date</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>MRP</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>B-2051</td>
                                            <td>2028-12-15</td>
                                            <td class="fw-bold text-white">150</td>
                                            <td>$12.50</td>
                                            <td>$18.00</td>
                                            <td><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>B-8094</td>
                                            <td>2027-08-20</td>
                                            <td class="fw-bold text-danger">5 <small class="fw-normal">(Low)</small></td>
                                            <td>$8.00</td>
                                            <td>$12.00</td>
                                            <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Restock Alert</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>B-0042</td>
                                            <td class="text-danger fw-semibold">2026-05-10 <small class="fw-normal">(Expired)</small></td>
                                            <td class="fw-bold text-white">80</td>
                                            <td>$15.00</td>
                                            <td>$22.00</td>
                                            <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Expired</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content-->
</main>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        var options = {
            series: [{
                name: 'Sales ($)',
                data: [31, 40, 28, 51, 42, 109, 100]
            }, {
                name: 'Purchases ($)',
                data: [11, 32, 45, 32, 34, 52, 41]
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                },
                fontFamily: 'Poppins, sans-serif'
            },
            colors: ['#FACA5A', '#FAC446'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                labels: {
                    style: {
                        colors: '#94A3B8'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#94A3B8'
                    }
                }
            },
            grid: {
                borderColor: 'rgba(255, 255, 255, 0.08)',
                strokeDashArray: 4
            },
            tooltip: {
                theme: 'dark',
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.02,
                    stops: [0, 90, 100]
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#analytics-chart"), options);
        chart.render();
    });
</script>
@endsection
