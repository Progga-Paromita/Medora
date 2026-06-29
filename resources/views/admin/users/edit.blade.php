@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Edit User</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/users') }}" class="text-decoration-none">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h4 class="card-title fw-bold mb-0">User Account Information</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/users/edit/' . $getRecord->id) }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <!-- First Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label fw-medium mb-2">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $getRecord->name) }}" placeholder="Enter first name" required>
                                        </div>
                                    </div>

                                    <!-- Last Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label fw-medium mb-2">Last Name</label>
                                            <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name', $getRecord->last_name) }}" placeholder="Enter last name">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="email" class="form-label fw-medium mb-2">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $getRecord->email) }}" placeholder="Enter email address" required>
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="phone" class="form-label fw-medium mb-2">Phone Number</label>
                                            <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', $getRecord->phone) }}" placeholder="Enter phone number">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Password -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="password" class="form-label fw-medium mb-2">Password</label>
                                            <input type="password" name="password" class="form-control" id="password" placeholder="Leave blank to keep current password">
                                        </div>
                                    </div>

                                    <!-- Role -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="is_role" class="form-label fw-medium mb-2">System Role</label>
                                            <select name="is_role" id="is_role" class="form-select" required>
                                                <option value="2" {{ $getRecord->is_role == 2 ? 'selected' : '' }}>Pharmacy Staff</option>
                                                <option value="1" {{ $getRecord->is_role == 1 ? 'selected' : '' }}>Administrator</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="status" class="form-label fw-medium mb-2">Account Status</label>
                                            <select name="status" id="status" class="form-select" required>
                                                <option value="1" {{ $getRecord->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $getRecord->status == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <div class="form-group">
                                            <label for="profile_image" class="form-label fw-medium mb-2">Profile Picture</label>
                                            <input type="file" name="profile_image" class="form-control mb-2" id="profile_image" accept="image/*">
                                            @if(!empty($getRecord->getProfileImage()))
                                                <div class="mt-2">
                                                    <img src="{{ $getRecord->getProfileImage() }}" alt="Profile preview" class="rounded border shadow-sm" style="max-height: 80px; width: 80px; object-fit: cover;">
                                                </div>
                                            @endif
                                            <div class="form-text text-muted text-xs mt-1">Accepts jpeg, png, jpg, gif. Max size: 2MB.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <a href="{{ url('admin/users') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection