@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Medicines</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Medicines</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/medicines/create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Add New Medicine
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            @include('message')

            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Medicine Directory</h4>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>SKU</th>
                                    <th>Medicine Name</th>
                                    <th>Generic Name</th>
                                    <th>Strength</th>
                                    <th>Category</th>
                                    <th>Packaging</th>
                                    <th>Supplier</th>
                                    <th>Rx Only</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $value->getProfileImage() }}" alt="label"
                                                 class="rounded-circle border" style="width:45px; height:45px; object-fit: cover;">
                                        </td>
                                        <td class="text-xs text-muted">{{ $value->sku ?? 'N/A' }}</td>
                                        <td class="fw-bold text-white">{{ $value->name }}</td>
                                        <td class="text-secondary">{{ $value->generic_name ?? 'N/A' }}</td>
                                        <td><span class="badge bg-secondary-subtle text-white border px-2 py-1">{{ $value->strength ?? 'N/A' }}</span></td>
                                        <td><span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">{{ $value->category ?? 'N/A' }}</span></td>
                                        <td>{{ $value->packaging ?? 'N/A' }}</td>
                                        <td>{{ optional($value->getSupplierName)->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($value->prescription_required)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Rx Only</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">OTC</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ url('admin/medicines/edit/'.$value->id) }}"
                                                   class="btn btn-outline-primary btn-sm rounded-3 me-1">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <a href="{{ url('admin/medicines/delete/'.$value->id) }}"
                                                   class="btn btn-outline-danger btn-sm rounded-3"
                                                   onclick="return confirm('Are you sure you want to delete this medicine?');">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-danger py-4">
                                            No Medicines Found
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