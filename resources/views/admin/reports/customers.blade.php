@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Customer Purchase Ledger</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/reports/dashboard') }}" class="text-decoration-none">BI Analytics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <!-- Customers metrics list card -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Customer Purchasing Statistics</h4>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill fw-bold">Ranked by Total Spend</span>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Customer Name</th>
                                    <th>Contact Details</th>
                                    <th class="text-center">Total Invoices</th>
                                    <th class="text-end">Total Spending ($)</th>
                                    <th>Last Purchase Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value->name }}</td>
                                        <td>
                                            <span>{{ $value->phone }}</span>
                                            <small class="d-block text-muted">{{ $value->email }}</small>
                                        </td>
                                        <td class="text-center fw-semibold text-white">{{ $value->total_purchases }} invoices</td>
                                        <td class="text-end fw-bold text-success">${{ number_format($value->total_spending, 2) }}</td>
                                        <td>
                                            @if($value->last_purchase)
                                                {{ date('M d, Y', strtotime($value->last_purchase)) }}
                                            @else
                                                <span class="text-muted">No transactions</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-danger py-4">
                                            No customer records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        {!! $getRecord->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
