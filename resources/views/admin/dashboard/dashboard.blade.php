@extends('layouts.app')

@section('content')
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <!-- Statistics Grid (8 Cards) -->
            <div class="row">
                <!-- 1. Total Medicines -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-secondary fw-medium mb-1">Total Medicines</h6>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalMedicines }}</h3>
                                <span class="text-success text-xs fw-semibold mt-2 d-inline-block">
                                    <i class="bi bi-arrow-up-right me-1"></i>+4% active
                                </span>
                            </div>
                            <div class="icon-shape p-3 rounded-4 fs-3" style="background-color: rgba(242, 181, 39, 0.1); color: #F2B527;">
                                <i class="bi bi-capsule"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Total Suppliers -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-secondary fw-medium mb-1">Total Suppliers</h6>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalSuppliers }}</h3>
                                <span class="text-success text-xs fw-semibold mt-2 d-inline-block">
                                    <i class="bi bi-arrow-up-right me-1"></i>+2% active
                                </span>
                            </div>
                            <div class="icon-shape p-3 rounded-4 fs-3" style="background-color: rgba(250, 196, 70, 0.15); color: #FAC446;">
                                <i class="bi bi-truck"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Total Customers -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-secondary fw-medium mb-1">Total Customers</h6>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalCustomers }}</h3>
                                <span class="text-success text-xs fw-semibold mt-2 d-inline-block">
                                    <i class="bi bi-arrow-up-right me-1"></i>+12% new
                                </span>
                            </div>
                            <div class="icon-shape p-3 rounded-4 fs-3" style="background-color: rgba(250, 202, 90, 0.2); color: #FACA5A;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Total Purchases -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-secondary fw-medium mb-1">Total Purchases</h6>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalPurchases }}</h3>
                                <span class="text-secondary text-xs fw-semibold mt-2 d-inline-block">
                                    <i class="bi bi-cart-check me-1"></i>Order history
                                </span>
                            </div>
                            <div class="icon-shape p-3 rounded-4 fs-3" style="background-color: rgba(242, 181, 39, 0.1); color: #F2B527;">
                                <i class="bi bi-cart-check-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Total Sales -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-secondary fw-medium mb-1">Total Sales</h6>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalSales }}</h3>
                                <span class="text-success text-xs fw-semibold mt-2 d-inline-block">
                                    <i class="bi bi-arrow-up-right me-1"></i>+8% sales
                                </span>
                            </div>
                            <div class="icon-shape p-3 rounded-4 fs-3" style="background-color: rgba(250, 196, 70, 0.15); color: #FAC446;">
                                <i class="bi bi-receipt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. Total Revenue -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-secondary fw-medium mb-1">Total Revenue</h6>
                                <h3 class="fw-bold mb-0 text-dark">${{ number_format($totalRevenue, 2) }}</h3>
                                <span class="text-success text-xs fw-semibold mt-2 d-inline-block">
                                    <i class="bi bi-graph-up me-1"></i>+15% growth
                                </span>
                            </div>
                            <div class="icon-shape p-3 rounded-4 fs-3" style="background-color: rgba(34, 197, 94, 0.1); color: #22C55E;">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 7. Low Stock -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-secondary fw-medium mb-1">Low Stock</h6>
                                <h3 class="fw-bold mb-0 text-danger">{{ $lowStock }}</h3>
                                <span class="text-danger text-xs fw-semibold mt-2 d-inline-block">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Reorder needed
                                </span>
                            </div>
                            <div class="icon-shape p-3 rounded-4 fs-3" style="background-color: rgba(239, 68, 68, 0.1); color: #EF4444;">
                                <i class="bi bi-boxes"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 8. Expired Medicines -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-secondary fw-medium mb-1">Expired Medicines</h6>
                                <h3 class="fw-bold mb-0 text-danger">{{ $expiredMedicines }}</h3>
                                <span class="text-danger text-xs fw-semibold mt-2 d-inline-block">
                                    <i class="bi bi-calendar-x-fill me-1"></i>Requires disposal
                                </span>
                            </div>
                            <div class="icon-shape p-3 rounded-4 fs-3" style="background-color: rgba(239, 68, 68, 0.1); color: #EF4444;">
                                <i class="bi bi-calendar-x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Chart Section -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header border-0 d-flex justify-content-between align-items-center p-4">
                            <h5 class="mb-0 fw-bold text-dark">Sales & Purchases Analytics</h5>
                            <span class="text-muted text-sm">Monthly Performance</span>
                        </div>
                        <div class="card-body p-4">
                            <div id="analytics-chart"></div>
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
                name: 'Sales',
                data: [31, 40, 28, 51, 42, 109, 100]
            }, {
                name: 'Purchases',
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
            colors: ['#F2B527', '#FAC446'],
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
                        colors: '#9aa0ac'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#9aa0ac'
                    }
                }
            },
            grid: {
                borderColor: '#e5e7eb',
                strokeDashArray: 4
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#analytics-chart"), options);
        chart.render();
    });
</script>
@endsection
