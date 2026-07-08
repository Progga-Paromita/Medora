@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Near Expiry Batches</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/inventory/dashboard') }}" class="text-decoration-none">Inventory Control</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Near Expiry</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 bg-orange bg-opacity-10 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0" style="color: #f97316;"><i class="bi bi-clock-history me-2"></i> Near Expiry Stock Batches (Expiring <= 30 Days)</h4>
                    <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: #f97316; color: #fff;">{{ $getRecord->total() }} batches</span>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Medicine Name</th>
                                    <th>Generic Name</th>
                                    <th>Packaging</th>
                                    <th>Batch Number</th>
                                    <th>Quantity Available</th>
                                    <th>Expiry Date</th>
                                    <th>Remaining Days</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    @php
                                        $today = date('Y-m-d');
                                        $daysLeft = (strtotime($value->expiry_date) - strtotime($today)) / 86400;
                                    @endphp
                                    <tr style="background: rgba(249, 115, 2橙, 0.02) !important;">
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value->medicine_name }}</td>
                                        <td class="text-secondary">{{ $value->generic_name }}</td>
                                        <td>{{ $value->packaging }}</td>
                                        <td><span class="badge bg-secondary">{{ $value->batch_id }}</span></td>
                                        <td class="fw-bold text-white">{{ $value->quantity }} units</td>
                                        <td>{{ date('M d, Y', strtotime($value->expiry_date)) }}</td>
                                        <td class="fw-bold text-warning">{{ (int)$daysLeft }} days</td>
                                        <td>
                                            <span class="badge rounded-pill px-3 py-1.5 fw-semibold" style="background-color: #f97316 !important; color: #fff !important;">
                                                Near Expiry
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-success py-4">
                                            <i class="bi bi-shield-check fs-3 d-block mb-2 text-success"></i>
                                            Awesome! No near-expiry stock batches found.
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
