@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Medicine Performance Report</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/reports/dashboard') }}" class="text-decoration-none">BI Analytics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Medicines Performance</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <!-- Search & Filters Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <div class="col-md-8">
                            <label for="performance_category" class="form-label small fw-medium">Performance Category</label>
                            <select name="performance_category" id="performance_category" class="form-select">
                                <option value="best_selling" {{ request('performance_category') === 'best_selling' || !request('performance_category') ? 'selected' : '' }}>Best-Selling Medicines (ranked by quantity sold desc)</option>
                                <option value="least_selling" {{ request('performance_category') === 'least_selling' ? 'selected' : '' }}>Least-Selling Medicines (ranked by quantity sold asc)</option>
                                <option value="never_sold" {{ request('performance_category') === 'never_sold' ? 'selected' : '' }}>Never-Sold Medicines (0 sales records)</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-end align-self-end">
                            <a href="{{ url('admin/reports/medicines') }}" class="btn btn-secondary me-2">Clear</a>
                            <button type="submit" class="btn btn-primary px-4">Apply Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Medicines list card -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4">
                    <h4 class="card-title fw-bold mb-0 text-white">Medicine sales statistics</h4>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Medicine Name</th>
                                    <th>Generic Name</th>
                                    <th>Packaging</th>
                                    <th class="text-center">Total Quantity Sold</th>
                                    <th class="text-end">Sales Revenue ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value->name }}</td>
                                        <td class="text-secondary">{{ $value->generic_name }}</td>
                                        <td>{{ $value->packaging }}</td>
                                        <td class="text-center fw-bold text-white">{{ number_format($value->qty_sold) }} units</td>
                                        <td class="text-end fw-bold text-success">${{ number_format($value->revenue, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-danger py-4">
                                            No medicine records found.
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
