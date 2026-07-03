@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">User Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none text-primary">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/users/create') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-person-plus-fill me-1"></i> Add New User
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
                <div class="col-xl-2 col-md-4 col-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 text-center" style="background: rgba(250, 202, 90, 0.1);">
                        <div class="card-body p-3">
                            <i class="bi bi-people-fill text-primary fs-3 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Total Users</span>
                            <h3 class="mb-0 fw-bold mt-1 text-white">{{ $totalUsers }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 text-center" style="background: rgba(34, 197, 94, 0.1);">
                        <div class="card-body p-3">
                            <i class="bi bi-patch-check-fill text-success fs-3 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Active</span>
                            <h3 class="mb-0 fw-bold mt-1 text-success">{{ $activeUsers }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 text-center" style="background: rgba(239, 68, 68, 0.1);">
                        <div class="card-body p-3">
                            <i class="bi bi-slash-circle text-danger fs-3 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Inactive</span>
                            <h3 class="mb-0 fw-bold mt-1 text-danger">{{ $inactiveUsers }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 text-center" style="background: rgba(13, 110, 253, 0.1);">
                        <div class="card-body p-3">
                            <i class="bi bi-shield-lock-fill text-info fs-3 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Administrators</span>
                            <h3 class="mb-0 fw-bold mt-1 text-white">{{ $adminCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12 mb-3">
                    <div class="card border-0 shadow-sm h-100 text-center" style="background: rgba(108, 117, 125, 0.1);">
                        <div class="card-body p-3">
                            <i class="bi bi-person-badge text-secondary fs-3 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Pharmacy Staff</span>
                            <h3 class="mb-0 fw-bold mt-1 text-white">{{ $staffCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <!-- Search text -->
                        <div class="col-lg-3 col-md-6">
                            <label for="search" class="form-label small fw-medium">Search Keyword</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" id="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Name, Email, or Phone...">
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="col-lg-2 col-md-3">
                            <label for="role" class="form-label small fw-medium">Role</label>
                            <select name="role" id="role" class="form-select">
                                <option value="">All Roles</option>
                                <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Admin</option>
                                <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Staff</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-lg-2 col-md-3">
                            <label for="status" class="form-label small fw-medium">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                <option value="deleted" {{ request('status') === 'deleted' ? 'selected' : '' }}>Deleted (Soft Deleted)</option>
                            </select>
                        </div>

                        <!-- Date range -->
                        <div class="col-lg-2 col-md-4">
                            <label for="start_date" class="form-label small fw-medium">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label for="end_date" class="form-label small fw-medium">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        <!-- Page limit -->
                        <div class="col-lg-1 col-md-4">
                            <label for="limit" class="form-label small fw-medium">Show</label>
                            <select name="limit" id="limit" class="form-select">
                                <option value="10" {{ request('limit') == '10' ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('limit') == '20' ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('limit') == '50' ? 'selected' : '' }}>50</option>
                            </select>
                        </div>

                        <!-- Hidden fields for sorting, preserving state -->
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                        <!-- Action Buttons -->
                        <div class="col-12 text-end mt-3">
                            <a href="{{ url('admin/users') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-clockwise me-1"></i> Clear Filters
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Users Directory</h4>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $getRecord->total() }} records found</span>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Name
                                            @if(request('sort_by') == 'name')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Phone</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'sort_order' => request('sort_by') == 'email' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Email
                                            @if(request('sort_by') == 'email')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'role', 'sort_order' => request('sort_by') == 'role' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Role
                                            @if(request('sort_by') == 'role')
                                                <i class="bi bi-sort-numeric-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Status</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Created Date
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
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $value->getProfileImage() }}" alt="profile"
                                                 class="rounded-circle border border-2 shadow-sm" style="width:45px; height:45px; object-fit: cover;">
                                        </td>
                                        <td>
                                            <div class="fw-bold text-white">{{ $value->name }} {{ $value->last_name }}</div>
                                        </td>
                                        <td>{{ $value->phone ?? 'N/A' }}</td>
                                        <td class="text-secondary">{{ $value->email }}</td>
                                        <td>
                                            @if($value->is_role == 1)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill small">Administrator</span>
                                            @else
                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1 rounded-pill small">Staff</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($value->is_deleted == 1)
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-pill small">Deleted</span>
                                            @elseif($value->status == 1)
                                                <a href="{{ url('admin/users/status/'.$value->id) }}" class="text-decoration-none">
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small hover-badge">Active</span>
                                                </a>
                                            @else
                                                <a href="{{ url('admin/users/status/'.$value->id) }}" class="text-decoration-none">
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill small hover-badge">Inactive</span>
                                                </a>
                                            @endif
                                        </td>
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
                                                            <a href="{{ url('admin/users/show/'.$value->id) }}" class="dropdown-item py-2">
                                                                <i class="bi bi-eye me-2 text-info"></i> View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('admin/users/edit/'.$value->id) }}" class="dropdown-item py-2">
                                                                <i class="bi bi-pencil me-2 text-primary"></i> Edit Account
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <!-- Reset Password Form Hook -->
                                                            <form id="reset-password-form-{{ $value->id }}" action="{{ url('admin/users/reset-password/'.$value->id) }}" method="POST" style="display: none;">
                                                                @csrf
                                                            </form>
                                                            <a href="#" class="dropdown-item py-2" 
                                                               onclick="if(confirm('Are you sure you want to reset the password for this user? This will generate a new random password and send it to them.')) { document.getElementById('reset-password-form-{{ $value->id }}').submit(); } event.preventDefault();">
                                                                <i class="bi bi-shield-lock me-2 text-warning"></i> Reset Password
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a href="{{ url('admin/users/delete/'.$value->id) }}" class="dropdown-item py-2 text-danger"
                                                               onclick="return confirm('Are you sure you want to delete this user? This will soft delete their record.');">
                                                                <i class="bi bi-trash me-2"></i> Soft Delete
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{ url('admin/users/restore/'.$value->id) }}" class="dropdown-item py-2 text-success"
                                                               onclick="return confirm('Are you sure you want to restore this soft-deleted user account?');">
                                                                <i class="bi bi-arrow-counterclockwise me-2"></i> Restore User
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-danger py-5">
                                            <i class="bi bi-exclamation-circle fs-3 mb-2 d-block"></i>
                                            No Users Found
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

@section('scripts')
<style>
    .hover-badge {
        transition: all 0.2s ease-in-out;
    }
    .hover-badge:hover {
        opacity: 0.8;
        transform: scale(1.05);
    }
</style>
@endsection
