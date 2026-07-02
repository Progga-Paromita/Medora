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
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 text-center" style="background: rgba(250, 202, 90, 0.1);">
                        <div class="card-body p-3">
                            <i class="bi bi-people-fill text-primary fs-3 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Total Users</span>
                            <h3 class="mb-0 fw-bold mt-1 text-white">{{ $totalUsers }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100 text-center" style="background: rgba(13, 110, 253, 0.1);">
                        <div class="card-body p-3">
                            <i class="bi bi-shield-lock-fill text-info fs-3 mb-2 d-block"></i>
                            <span class="text-secondary small fw-medium text-uppercase">Administrators</span>
                            <h3 class="mb-0 fw-bold mt-1 text-white">{{ $adminCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
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
                        <div class="col-md-6">
                            <label for="search" class="form-label small fw-medium">Search Keyword</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" id="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Name, Email, or Phone...">
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="col-md-3">
                            <label for="role" class="form-label small fw-medium">Role</label>
                            <select name="role" id="role" class="form-select">
                                <option value="">All Roles</option>
                                <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Admin</option>
                                <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Staff</option>
                            </select>
                        </div>

                        <!-- Page limit -->
                        <div class="col-md-3">
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
                                    <th>Serial</th>
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
                                            <div class="fw-bold text-white">
                                                {{ $value->name }} {{ $value->last_name }}
                                                @if($value->is_deleted == 1)
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-1 px-1.5 py-0.5 rounded-pill small" style="font-size: 0.75rem;">Deleted</span>
                                                @endif
                                            </div>
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
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                @if($value->is_deleted == 0)
                                                    <a href="{{ url('admin/users/show/'.$value->id) }}" class="btn btn-outline-info btn-sm rounded-3" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ url('admin/users/edit/'.$value->id) }}" class="btn btn-outline-primary btn-sm rounded-3" title="Edit Account">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ url('admin/users/delete/'.$value->id) }}" class="btn btn-outline-danger btn-sm rounded-3" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this user? This will soft delete their record.');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ url('admin/users/restore/'.$value->id) }}" class="btn btn-outline-success btn-sm rounded-3" title="Restore"
                                                       onclick="return confirm('Are you sure you want to restore this soft-deleted user account?');">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-danger py-5">
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
