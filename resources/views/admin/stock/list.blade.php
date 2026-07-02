@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Inventory Stock</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Stock</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/stocks/create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Add New Stock
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            @include('message')

            <div class="card shadow-sm border-0">
                <div class="card-header p-4">
                    <h4 class="card-title fw-bold mb-0 text-white">Stock Directory</h4>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>

                                    <th>Medicine Name</th>
                                    <th>Batch ID</th>
                                    <th>Expiry Date</th>
                                    <th>Available Qty</th>
                                    <th>Selling MRP ($)</th>
                                    <th>Purchase Rate ($)</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($getRecord as $value)
                                    <tr>

                                        <td class="fw-bold text-white">
                                            {{ optional($value->getMedicine)->name ?? 'N/A' }}
                                            <small class="d-block text-muted">{{ optional($value->getMedicine)->generic_name }}</small>
                                        </td>
                                        <td>{{ $value->batch_id }}</td>
                                        <td>
                                            @if($value->expiry_date < date('Y-m-d'))
                                                <span class="text-danger fw-semibold"><i class="bi bi-calendar-x-fill me-1"></i>{{ $value->expiry_date }} (Expired)</span>
                                            @else
                                                <span class="text-success"><i class="bi bi-calendar-check me-1"></i>{{ $value->expiry_date }}</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold">{{ $value->quantity }}</td>
                                        <td>${{ number_format($value->mrp, 2) }}</td>
                                        <td>${{ number_format($value->rate, 2) }}</td>
                                        <td>
                                            @if($value->expiry_date < date('Y-m-d'))
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Expired</span>
                                            @elseif($value->quantity < 10)
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Low Stock</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">In Stock</span>
                                            @endif
                                        </td>
                                        <td class="text-xs text-muted">{{ $value->created_at }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ url('admin/stocks/edit/'.$value->id) }}"
                                                   class="btn btn-outline-primary btn-sm rounded-3 me-1">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <a href="{{ url('admin/stocks/delete/'.$value->id) }}"
                                                   class="btn btn-outline-danger btn-sm rounded-3"
                                                   onclick="return confirm('Are you sure you want to delete this stock entry?');">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-danger py-4">
                                            No Stock Entries Found
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
