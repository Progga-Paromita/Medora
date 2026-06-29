@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Staff Users</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Staff Users</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/users/create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Add New Staff User
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
                    <h4 class="card-title fw-bold mb-0 text-white">Staff Directory</h4>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $value->getProfileImage() }}" alt="profile"
                                                 class="rounded-circle border" style="width:45px; height:45px; object-fit: cover;">
                                        </td>
                                        <td class="fw-bold text-white">{{ $value->name }} {{ $value->last_name }}</td>
                                        <td>{{ $value->phone ?? 'N/A' }}</td>
                                        <td class="text-secondary">{{ $value->email }}</td>
                                        <td>
                                            @if($value->is_role == 1)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">Administrator</span>
                                            @else
                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill">Staff</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($value->status == 1)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-xs text-muted">{{ $value->created_at }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ url('admin/users/edit/'.$value->id) }}"
                                                   class="btn btn-outline-primary btn-sm rounded-3 me-1">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <a href="{{ url('admin/users/delete/'.$value->id) }}"
                                                   class="btn btn-outline-danger btn-sm rounded-3"
                                                   onclick="return confirm('Are you sure you want to delete this staff user?');">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-danger py-4">
                                            No Staff Users Found
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