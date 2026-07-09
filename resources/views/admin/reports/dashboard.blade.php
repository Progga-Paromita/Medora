@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Business Intelligence Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">BI Analytics</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <!-- KPIs Card Grid -->
            <div class="row g-4 mb-4">
                <!-- Total Revenue -->
                <div class="col-lg-3 col-sm-6">
                    <div class="card border-0 shadow-sm p-4 h-100" style="background: rgba(34, 197, 94, 0.08);">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-secondary small fw-medium text-uppercase">Total Revenue</span>
                                <h3 class="mb-0 fw-bold text-success mt-1">${{ number_format($totalRevenue, 2) }}</h3>
                            </div>
                            <div class="rounded-circle p-3 bg-success bg-opacity-25">
                                <i class="bi bi-wallet2 text-success fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gross Profit -->
                <div class="col-lg-3 col-sm-6">
                    <div class="card border-0 shadow-sm p-4 h-100" style="background: rgba(59, 130, 246, 0.08);">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-secondary small fw-medium text-uppercase">Gross Profit</span>
                                <h3 class="mb-0 fw-bold text-primary mt-1" style="color: #3b82f6 !important;">${{ number_format($grossProfit, 2) }}</h3>
                            </div>
                            <div class="rounded-circle p-3 bg-primary bg-opacity-25">
                                <i class="bi bi-cash-coin text-primary fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Purchases -->
                <div class="col-lg-3 col-sm-6">
                    <div class="card border-0 shadow-sm p-4 h-100" style="background: rgba(239, 68, 68, 0.08);">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-secondary small fw-medium text-uppercase">Total Purchases</span>
                                <h3 class="mb-0 fw-bold text-danger mt-1">${{ number_format($totalPurchases, 2) }}</h3>
                            </div>
                            <div class="rounded-circle p-3 bg-danger bg-opacity-25">
                                <i class="bi bi-cart-dash text-danger fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Valuation -->
                <div class="col-lg-3 col-sm-6">
                    <div class="card border-0 shadow-sm p-4 h-100" style="background: rgba(234, 179, 8, 0.08);">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-secondary small fw-medium text-uppercase">Inventory Asset Value</span>
                                <h3 class="mb-0 fw-bold text-warning mt-1">${{ number_format($inventoryValuation, 2) }}</h3>
                            </div>
                            <div class="rounded-circle p-3 bg-warning bg-opacity-25">
                                <i class="bi bi-boxes text-warning fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- More Dashboard Info Counters -->
            <div class="row mb-5 text-center">
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="border rounded p-3 h-100" style="border-color: var(--bs-border-color) !important;">
                        <span class="text-muted small text-uppercase">Today's Sales</span>
                        <h4 class="mb-0 fw-bold text-white mt-1">${{ number_format($todaySales, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="border rounded p-3 h-100" style="border-color: var(--bs-border-color) !important;">
                        <span class="text-muted small text-uppercase">This Month</span>
                        <h4 class="mb-0 fw-bold text-white mt-1">${{ number_format($monthlySales, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="border rounded p-3 h-100" style="border-color: var(--bs-border-color) !important;">
                        <span class="text-muted small text-uppercase">Avg Daily Sales</span>
                        <h4 class="mb-0 fw-bold text-white mt-1">${{ number_format($avgDailySales, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="border rounded p-3 h-100" style="border-color: var(--bs-border-color) !important;">
                        <span class="text-muted small text-uppercase">Avg Invoice</span>
                        <h4 class="mb-0 fw-bold text-white mt-1">${{ number_format($avgInvoiceValue, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="border rounded p-3 h-100 bg-danger bg-opacity-10 border-danger" style="border-color: rgba(239,68,68,0.2) !important;">
                        <span class="text-danger small text-uppercase">Low Stock</span>
                        <h4 class="mb-0 fw-bold text-danger mt-1">{{ $lowStockCount }}</h4>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6 mb-3">
                    <div class="border rounded p-3 h-100 bg-danger bg-opacity-10 border-danger" style="border-color: rgba(239,68,68,0.2) !important;">
                        <span class="text-danger small text-uppercase">Expired Batches</span>
                        <h4 class="mb-0 fw-bold text-danger mt-1">{{ $expiredCount }}</h4>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row g-4 mb-4">
                <!-- Monthly Sales vs Purchases -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header p-4">
                            <h5 class="fw-bold mb-0 text-white">Monthly Business Cashflow ({{ date('Y') }})</h5>
                        </div>
                        <div class="card-body p-4">
                            <div style="height: 350px;">
                                <canvas id="cashflowChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Distribution -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header p-4">
                            <h5 class="fw-bold mb-0 text-white">Inventory Stock Units Distribution</h5>
                        </div>
                        <div class="card-body p-4 d-flex align-items-center justify-content-center">
                            <div style="height: 300px; width: 100%;">
                                <canvas id="distributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Top 5 Selling Medicines -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header p-4">
                            <h5 class="fw-bold mb-0 text-white">Top 5 Best-Selling Medicines</h5>
                        </div>
                        <div class="card-body p-4">
                            <div style="height: 300px;">
                                <canvas id="topMedicinesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick reports links -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header p-4">
                            <h5 class="fw-bold mb-0 text-white">Generate Operations Reports</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="list-group list-group-flush">
                                <a href="{{ url('admin/reports/sales') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 bg-transparent text-white">
                                    <span><i class="bi bi-file-earmark-bar-graph text-primary me-2"></i> Sales Performance Report</span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </a>
                                <a href="{{ url('admin/reports/purchases') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 bg-transparent text-white">
                                    <span><i class="bi bi-file-earmark-arrow-down text-success me-2"></i> Supplier Purchases Report</span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </a>
                                <a href="{{ url('admin/reports/inventory') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 bg-transparent text-white">
                                    <span><i class="bi bi-file-earmark-medical text-warning me-2"></i> Inventory Valuation Statement</span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </a>
                                <a href="{{ url('admin/reports/customers') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 bg-transparent text-white">
                                    <span><i class="bi bi-people text-info me-2"></i> Customer Purchase Ledger</span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </a>
                                <a href="{{ url('admin/reports/suppliers') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 bg-transparent text-white">
                                    <span><i class="bi bi-building text-warning me-2"></i> Supplier Performance Report</span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </a>
                                <a href="{{ url('admin/reports/medicines') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 bg-transparent text-white">
                                    <span><i class="bi bi-capsule-indicator text-purple me-2" style="color: #a855f7;"></i> Medicine Performance report</span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </a>
                                <a href="{{ url('admin/reports/profit') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 bg-transparent text-white">
                                    <span><i class="bi bi-currency-exchange text-success me-2"></i> Profit Analysis Report</span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </a>
                                <a href="{{ url('admin/reports/financial') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 bg-transparent text-white">
                                    <span><i class="bi bi-bank text-info me-2"></i> Overall Financial Statement</span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Colors & theme options
        const fontColor = '#94a3b8';
        const gridColor = 'rgba(148, 163, 184, 0.08)';

        // 1. Monthly Sales vs Purchases Chart
        const cashflowCtx = document.getElementById('cashflowChart').getContext('2d');
        new Chart(cashflowCtx, {
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
        const distCtx = document.getElementById('distributionChart').getContext('2d');
        new Chart(distCtx, {
            type: 'doughnut',
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
                        labels: { color: fontColor, boxWidth: 12 }
                    }
                }
            }
        });

        // 3. Top Selling Medicines Chart
        const topMedCtx = document.getElementById('topMedicinesChart').getContext('2d');
        new Chart(topMedCtx, {
            type: 'bar',
            data: {
                labels: {!! $topMedNames !!},
                datasets: [{
                    label: 'Units Sold',
                    data: {!! $topMedQty !!},
                    backgroundColor: 'rgba(168, 85, 247, 0.7)',
                    borderColor: '#a855f7',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: gridColor },
                        ticks: { color: fontColor }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: fontColor }
                    }
                }
            }
        });
    });
</script>
@endsection
