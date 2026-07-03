@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">User Details</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/users') }}" class="text-decoration-none">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            @include('message')
            
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card shadow-sm border-0 overflow-hidden">
                        <!-- Premium profile header section -->
                        <div class="p-4 text-center border-bottom" style="background: rgba(250, 202, 90, 0.03);">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $user->getProfileImage() }}" alt="User Photo" 
                                     class="rounded-circle border border-3 border-primary shadow" 
                                     style="width: 130px; height: 130px; object-fit: cover;">
                            </div>
                            <h3 class="fw-bold mt-3 mb-1 text-white">{{ $user->name }} {{ $user->last_name }}</h3>
                            <p class="text-muted mb-0 small">
                                @if($user->is_role == 1)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill">Administrator</span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 rounded-pill">Pharmacy Staff</span>
                                @endif
                            </p>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Contact Info Section -->
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary-subtle p-3 rounded-3 me-3 text-primary">
                                            <i class="bi bi-envelope fs-4"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Email Address</span>
                                            <strong class="text-white">{{ $user->email }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary-subtle p-3 rounded-3 me-3 text-primary">
                                            <i class="bi bi-telephone fs-4"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Phone Number</span>
                                            <strong class="text-white">{{ $user->phone ?? 'N/A' }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Info Section -->
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary-subtle p-3 rounded-3 me-3 text-primary">
                                            <i class="bi bi-shield-check fs-4"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Account Status</span>
                                            @if($user->is_deleted == 1)
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-0.5 rounded-pill">Deleted (Soft)</span>
                                            @elseif($user->status == 1)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded-pill">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 rounded-pill">Inactive</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary-subtle p-3 rounded-3 me-3 text-primary">
                                            <i class="bi bi-calendar-event fs-4"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Registered Date</span>
                                            <strong class="text-white">{{ $user->created_at->format('M d, Y h:i A') }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary-subtle p-3 rounded-3 me-3 text-primary">
                                            <i class="bi bi-clock-history fs-4"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Last Profile Update</span>
                                            <strong class="text-white">{{ $user->updated_at->format('M d, Y h:i A') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons Section -->
                            <div class="d-flex justify-content-between mt-5 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                <a href="{{ url('admin/users') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to List
                                </a>
                                @if($user->is_deleted == 0)
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-warning"
                                                onclick="if(confirm('Are you sure you want to reset this user\'s password?')) { document.getElementById('reset-password-form-show').submit(); }">
                                            <i class="bi bi-shield-lock-fill me-1"></i> Reset Password
                                        </button>
                                        <a href="{{ url('admin/users/edit/'.$user->id) }}" class="btn btn-primary">
                                            <i class="bi bi-pencil-fill me-1"></i> Edit Account
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Hidden Password Reset Form -->
                            <form id="reset-password-form-show" action="{{ url('admin/users/reset-password/'.$user->id) }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
