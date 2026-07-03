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
            <div class="row align-items-center">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/medicines/create') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add New Medicine
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            @include('message')

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(250, 202, 90, 0.1);">
                        <div class="card-body">
                            <i class="bi bi-capsule text-primary fs-2 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Total Medicines</span>
                            <h3 class="mb-0 fw-bold mt-1 text-white">{{ $totalMedicines }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(34, 197, 94, 0.1);">
                        <div class="card-body">
                            <i class="bi bi-plus-square text-success fs-2 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">New (This Month)</span>
                            <h3 class="mb-0 fw-bold mt-1 text-success">{{ $newMedicinesThisMonth }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <!-- Keyword Search -->
                        <div class="col-md-4">
                            <label for="search" class="form-label small fw-medium">Search Keyword</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" id="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Name, generic name, supplier...">
                            </div>
                        </div>

                        <!-- Medicine Type -->
                        <div class="col-md-3">
                            <label for="packaging" class="form-label small fw-medium">Medicine Type</label>
                            <select name="packaging" id="packaging" class="form-select">
                                <option value="">All Medicine Types</option>
                                <option value="Tablet" {{ request('packaging') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="Capsule" {{ request('packaging') == 'Capsule' ? 'selected' : '' }}>Capsule</option>
                                <option value="Syrup" {{ request('packaging') == 'Syrup' ? 'selected' : '' }}>Syrup</option>
                                <option value="Injection" {{ request('packaging') == 'Injection' ? 'selected' : '' }}>Injection</option>
                                <option value="Ointment" {{ request('packaging') == 'Ointment' ? 'selected' : '' }}>Ointment</option>
                                <option value="Cream" {{ request('packaging') == 'Cream' ? 'selected' : '' }}>Cream</option>
                                <option value="Drops" {{ request('packaging') == 'Drops' ? 'selected' : '' }}>Drops</option>
                                <option value="Suspension" {{ request('packaging') == 'Suspension' ? 'selected' : '' }}>Suspension</option>
                                <option value="Powder" {{ request('packaging') == 'Powder' ? 'selected' : '' }}>Powder</option>
                                <option value="Inhaler" {{ request('packaging') == 'Inhaler' ? 'selected' : '' }}>Inhaler</option>
                            </select>
                        </div>

                        <!-- Supplier Dropdown -->
                        <div class="col-md-3">
                            <label for="supplier_id" class="form-label small fw-medium">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-select">
                                <option value="">All Suppliers</option>
                                @foreach($getSuppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Page Limit -->
                        <div class="col-md-2">
                            <label for="limit" class="form-label small fw-medium">Show</label>
                            <select name="limit" id="limit" class="form-select">
                                <option value="10" {{ request('limit') == '10' ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('limit') == '20' ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('limit') == '50' ? 'selected' : '' }}>50</option>
                            </select>
                        </div>

                        <!-- preserved sorting states -->
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                        <!-- Buttons -->
                        <div class="col-12 text-end mt-3">
                            <a href="{{ url('admin/medicines') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-clockwise"></i> Clear
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Medicines Registry Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Medicine Catalog</h4>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $getRecord->total() }} records found</span>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Image</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Medicine Name
                                            @if(request('sort_by') == 'name')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th>Medicine Type</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <!-- No Database IDs displayed, using sequential row number -->
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $value->getProfileImage() }}" alt="label"
                                                 class="rounded border shadow-sm" style="width:45px; height:45px; object-fit: cover;">
                                        </td>
                                        <td class="fw-bold text-white">{{ $value->name }}</td>

                                        <td><span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">{{ $value->packaging ?? 'N/A' }}</span></td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                @if($value->is_deleted == 0)
                                                    <a href="{{ url('admin/medicines/show/'.$value->id) }}" class="btn btn-outline-info btn-sm rounded-3" title="View Catalog">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ url('admin/medicines/edit/'.$value->id) }}" class="btn btn-outline-primary btn-sm rounded-3" title="Edit Medicine">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ url('admin/medicines/delete/'.$value->id) }}" class="btn btn-outline-danger btn-sm rounded-3" title="Delete"
                                                       onclick="return confirm('Are you sure you want to delete this medicine? This will soft delete their record.');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ url('admin/medicines/restore/'.$value->id) }}" class="btn btn-outline-success btn-sm rounded-3" title="Restore"
                                                       onclick="return confirm('Are you sure you want to restore this soft-deleted medicine?');">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-danger py-5">
                                            <i class="bi bi-exclamation-circle fs-3 mb-2 d-block"></i>
                                            No Medicines Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-end mt-4">
                        {!! $getRecord->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
