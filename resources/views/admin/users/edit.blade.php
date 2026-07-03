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
                    <!-- Validation error alerts -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4 d-flex justify-content-between align-items-center">
                            <h4 class="card-title fw-bold mb-0">User Account Information</h4>
                            <div>
                                <span class="badge bg-secondary-subtle text-white border px-3 py-2 rounded-pill">
                                    Last Updated: {{ $getRecord->updated_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/users/edit/' . $getRecord->id) }}" method="POST" enctype="multipart/form-data" id="userEditForm">
                                @csrf

                                <div class="row">
                                    <!-- First Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label fw-medium mb-2">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" value="{{ old('name', $getRecord->name) }}" placeholder="Enter first name" required>
                                        </div>
                                    </div>

                                    <!-- Last Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label fw-medium mb-2">Last Name</label>
                                            <input type="text" name="last_name" class="form-control" 
                                                   id="last_name" value="{{ old('last_name', $getRecord->last_name) }}" placeholder="Enter last name">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="email" class="form-label fw-medium mb-2">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" value="{{ old('email', $getRecord->email) }}" placeholder="Enter email address" required>
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="phone" class="form-label fw-medium mb-2">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" value="{{ old('phone', $getRecord->phone) }}" placeholder="Enter contact number" required>
                                            <div class="form-text text-muted text-xs">Must be numeric and between 7 to 15 digits.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Role -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="is_role" class="form-label fw-medium mb-2">System Role <span class="text-danger">*</span></label>
                                            <select name="is_role" id="is_role" class="form-select" required>
                                                <option value="2" {{ $getRecord->is_role == 2 ? 'selected' : '' }}>Pharmacy Staff</option>
                                                <option value="1" {{ $getRecord->is_role == 1 ? 'selected' : '' }}>Administrator</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="status" class="form-label fw-medium mb-2">Account Status <span class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-select" required>
                                                <option value="1" {{ $getRecord->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $getRecord->status == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="row mt-2">
                                    <div class="col-12 mb-4">
                                        <div class="form-group">
                                            <label for="profile_image" class="form-label fw-medium mb-2">Profile Picture</label>
                                            <input type="file" name="profile_image" class="form-control mb-3" id="profile_image" accept="image/*">
                                            
                                            <div class="row mt-2">
                                                @if(!empty($getRecord->getProfileImage()))
                                                    <div class="col-md-3 col-sm-6 mb-2">
                                                        <span class="d-block text-secondary small mb-2 fw-medium">Current Photo:</span>
                                                        <img src="{{ $getRecord->getProfileImage() }}" alt="Current Profile" 
                                                             class="rounded border shadow-sm" style="height: 100px; width: 100px; object-fit: cover;">
                                                    </div>
                                                @endif

                                                <!-- JS Image Preview -->
                                                <div class="col-md-3 col-sm-6 mb-2 d-none" id="previewContainer">
                                                    <span class="d-block text-secondary small mb-2 fw-medium">New Photo Preview:</span>
                                                    <img src="#" id="imagePreview" alt="Profile Preview" 
                                                         class="rounded border shadow-sm" style="height: 100px; width: 100px; object-fit: cover;">
                                                </div>
                                            </div>
                                            <div class="form-text text-muted text-xs mt-1">Accepts jpeg, png, jpg, gif. Max size: 2MB.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <div>
                                        <!-- Add link/button to trigger Password Reset action -->
                                        <button type="button" class="btn btn-outline-warning"
                                                onclick="if(confirm('Are you sure you want to reset this user\'s password? This will generate a new randomized password and send it.')) { document.getElementById('reset-password-form').submit(); }">
                                            <i class="bi bi-shield-lock-fill me-1"></i> Reset Password
                                        </button>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ url('admin/users') }}" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Update User</button>
                                    </div>
                                </div>
                            </form>

                            <!-- Hidden Password Reset Form -->
                            <form id="reset-password-form" action="{{ url('admin/users/reset-password/'.$getRecord->id) }}" method="POST" style="display: none;">
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileImageInput = document.getElementById('profile_image');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');

        profileImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.setAttribute('src', e.target.result);
                    previewContainer.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('d-none');
            }
        });
    });
</script>
@endsection