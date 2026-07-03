@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Low Stock Alert</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/inventory/dashboard') }}" class="text-decoration-none">Inventory Control</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Low Stock</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 bg-warning bg-opacity-10 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-warning"><i class="bi bi-exclamation-triangle me-2"></i> Low Stock Batches (Qty < 20)</h4>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">{{ $getRecord->total() }} alerts</span>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Medicine Name</th>
                                    <th>Generic Name</th>
                                    <th>Packaging</th>
                                    <th>Batch Number</th>
                                    <th>Quantity Available</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value->medicine_name }}</td>
                                        <td class="text-secondary">{{ $value->generic_name }}</td>
                                        <td>{{ $value->packaging }}</td>
                                        <td><span class="badge bg-secondary">{{ $value->batch_id }}</span></td>
                                        <td class="fw-bold text-warning">{{ $value->quantity }} units</td>
                                        <td>{{ date('M d, Y', strtotime($value->expiry_date)) }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-warning text-dark px-3 py-1.5 fw-semibold" style="background-color: #eab308 !important; color: #000 !important;">
                                                Low Stock
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-success py-4">
                                            <i class="bi bi-check-circle fs-3 d-block mb-2"></i>
                                            All medicine stock levels are healthy!
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
