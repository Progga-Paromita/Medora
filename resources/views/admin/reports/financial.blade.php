@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Overall Financial Statement</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/reports/dashboard') }}" class="text-decoration-none">BI Analytics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Financial Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h4 class="card-title fw-bold mb-0 text-white">Trial balance & Asset Valuation</h4>
                        </div>
                        <div class="card-body p-4">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>Account Category</th>
                                        <th class="text-end">Value ($)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="color: var(--bs-body-color);">Sales Revenue (Invoiced Total)</td>
                                        <td class="text-end fw-bold text-success">${{ number_format($totalRevenue, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold" style="color: var(--bs-body-color);">Expenses (Supplier Purchases Total)</td>
                                        <td class="text-end fw-bold text-danger">-${{ number_format($totalPurchases, 2) }}</td>
                                    </tr>
                                    <tr style="background: var(--bs-secondary-bg);">
                                        <td class="fw-bold" style="color: var(--bs-body-color);">Calculated Gross Profit</td>
                                        <td class="text-end fw-bold text-success">${{ number_format($grossProfit, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold" style="color: var(--bs-body-color);">Inventory Asset Value (at cost)</td>
                                        <td class="text-end fw-bold text-warning">${{ number_format($inventoryValuation, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="text-end mt-4 no-print">
                                <button onclick="window.print()" class="btn btn-primary px-4"><i class="bi bi-printer me-1"></i> Print Statement</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
