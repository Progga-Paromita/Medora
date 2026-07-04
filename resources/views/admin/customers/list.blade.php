@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Customers</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customers</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/customers/create') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add New Customer
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
                            <i class="bi bi-people-fill text-primary fs-2 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Total Customers</span>
                            <h3 class="mb-0 fw-bold mt-1 text-white">{{ $totalCustomers }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100" style="background: rgba(34, 197, 94, 0.1);">
                        <div class="card-body">
                            <i class="bi bi-calendar-check text-success fs-2 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">New Customers (This Month)</span>
                            <h3 class="mb-0 fw-bold mt-1 text-success">{{ $newCustomersThisMonth }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <!-- Search Keyword -->
                        <div class="col-md-8">
                            <label for="search" class="form-label small fw-medium">Search Keyword</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" id="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Name, Email, Phone...">
                            </div>
                        </div>

                        <!-- Limit -->
                        <div class="col-md-4">
                            <label for="limit" class="form-label small fw-medium">Show</label>
                            <select name="limit" id="limit" class="form-select">
                                <option value="10" {{ request('limit') == '10' ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('limit') == '20' ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('limit') == '50' ? 'selected' : '' }}>50</option>
                            </select>
                        </div>

                        <!-- Sorting states -->
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                        <!-- Actions -->
                        <div class="col-12 text-end mt-3">
                            <a href="{{ url('admin/customers') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-clockwise"></i> Clear
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customers Table Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Customer Registry</h4>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $customers->total() }} customers</span>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Customer Name
                                            @if(request('sort_by') == 'name')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Phone Number</th>
                                    <th>Email Address</th>
                                    <th>Home Address</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($customers as $value)
                                    <tr>
                                        <!-- No Database IDs displayed, using sequential row number -->
                                        <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value->name }}</td>
                                        <td>{{ $value->phone ?? 'N/A' }}</td>
                                        <td class="text-secondary">{{ $value->email ?? 'N/A' }}</td>
                                        <td>{{ $value->address ?? 'N/A' }}</td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                @if($value->is_deleted == 0)
                                                    <a href="{{ url('admin/customers/show/'.$value->id) }}" class="btn btn-outline-info btn-sm rounded-3" title="View Profile">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ url('admin/customers/edit/'.$value->id) }}" class="btn btn-outline-primary btn-sm rounded-3" title="Edit Customer">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ url('admin/customers/delete/'.$value->id) }}" class="btn btn-outline-danger btn-sm rounded-3" title="Delete"
                                                       onclick="return confirm('Are you sure you want to delete this customer? This will soft delete their record.');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ url('admin/customers/restore/'.$value->id) }}" class="btn btn-outline-success btn-sm rounded-3" title="Restore"
                                                       onclick="return confirm('Are you sure you want to restore this soft-deleted customer?');">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-danger py-5">
                                            <i class="bi bi-exclamation-circle fs-3 mb-2 d-block"></i>
                                            No Customers Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-end mt-4">
                        {!! $customers->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
