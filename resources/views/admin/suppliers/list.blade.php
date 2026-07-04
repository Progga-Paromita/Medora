@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Suppliers</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/suppliers/create') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add New Supplier
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
                            <i class="bi bi-truck text-primary fs-2 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Total Suppliers</span>
                            <h3 class="mb-0 fw-bold mt-1 text-white">{{ $totalSuppliers }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(34, 197, 94, 0.1);">
                        <div class="card-body">
                            <i class="bi bi-calendar-check text-success fs-2 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">New Suppliers (This Month)</span>
                            <h3 class="mb-0 fw-bold mt-1 text-success">{{ $newSuppliersThisMonth }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <!-- Search Keyword -->
                        <div class="col-md-4">
                            <label for="search" class="form-label small fw-medium">Search Keyword</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" id="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Name, Email, Phone...">
                            </div>
                        </div>

                        <!-- Status Filter (Active / Deleted) -->
                        <div class="col-md-2">
                            <label for="status" class="form-label small fw-medium">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Active</option>
                                <option value="deleted" {{ request('status') === 'deleted' ? 'selected' : '' }}>Deleted (Soft Deleted)</option>
                            </select>
                        </div>

                        <!-- Date range -->
                        <div class="col-md-2">
                            <label for="start_date" class="form-label small fw-medium">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="end_date" class="form-label small fw-medium">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        <!-- Limit -->
                        <div class="col-md-2">
                            <label for="limit" class="form-label small fw-medium">Show</label>
                            <select name="limit" id="limit" class="form-select">
                                <option value="10" {{ request('limit') == '10' ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('limit') == '20' ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('limit') == '50' ? 'selected' : '' }}>50</option>
                            </select>
                        </div>

                        <!-- Preserving sort parameters -->
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                        <!-- Actions -->
                        <div class="col-12 text-end mt-3">
                            <a href="{{ url('admin/suppliers') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-clockwise"></i> Clear
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Suppliers Directory Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Supplier Directory</h4>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $getRecord->total() }} suppliers</span>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Supplier Name
                                            @if(request('sort_by') == 'name')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Phone Number</th>
                                    <th>Email Address</th>
                                    <th>Supplier Address</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Created At
                                            @if(request('sort_by', 'created_at') == 'created_at')
                                                <i class="bi bi-sort-numeric-{{ request('sort_order', 'desc') == 'desc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <!-- No Database IDs displayed, using sequential row number -->
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value->name }}</td>
                                        <td>{{ $value->phone ?? 'N/A' }}</td>
                                        <td class="text-secondary">{{ $value->email ?? 'N/A' }}</td>
                                        <td>{{ $value->address ?? 'N/A' }}</td>
                                        <td class="text-xs text-muted">{{ $value->created_at->format('M d, Y h:i A') }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1 dropdown-toggle" 
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Manage
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3">
                                                    @if($value->is_deleted == 0)
                                                        <li>
                                                            <a href="{{ url('admin/suppliers/show/'.$value->id) }}" class="dropdown-item py-2">
                                                                <i class="bi bi-eye text-info me-2"></i> View Profile
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('admin/suppliers/edit/'.$value->id) }}" class="dropdown-item py-2">
                                                                <i class="bi bi-pencil text-primary me-2"></i> Edit Profile
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a href="{{ url('admin/suppliers/delete/'.$value->id) }}" class="dropdown-item py-2 text-danger"
                                                               onclick="return confirm('Are you sure you want to delete this supplier? This will soft delete their record.');">
                                                                <i class="bi bi-trash me-2"></i> Soft Delete
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{ url('admin/suppliers/restore/'.$value->id) }}" class="dropdown-item py-2 text-success"
                                                               onclick="return confirm('Are you sure you want to restore this soft-deleted supplier?');">
                                                                <i class="bi bi-arrow-counterclockwise me-2"></i> Restore Supplier
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-danger py-5">
                                            <i class="bi bi-exclamation-circle fs-3 mb-2 d-block"></i>
                                            No Suppliers Found
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
