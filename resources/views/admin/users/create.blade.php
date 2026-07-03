@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Add New User</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/users') }}" class="text-decoration-none">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <!-- Error messages from validation -->
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
                        <div class="card-header p-4">
                            <h4 class="card-title fw-bold mb-0">User Account Information</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ url('admin/users/create') }}" method="POST" enctype="multipart/form-data" id="userForm">
                                @csrf

                                <div class="row">
                                    <!-- First Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label fw-medium mb-2">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" value="{{ old('name') }}" placeholder="Enter first name" required>
                                        </div>
                                    </div>

                                    <!-- Last Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label fw-medium mb-2">Last Name</label>
                                            <input type="text" name="last_name" class="form-control" 
                                                   id="last_name" value="{{ old('last_name') }}" placeholder="Enter last name">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="email" class="form-label fw-medium mb-2">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" value="{{ old('email') }}" placeholder="Enter email address" required>
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="phone" class="form-label fw-medium mb-2">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" value="{{ old('phone') }}" placeholder="Enter contact number" required>
                                            <div class="form-text text-muted text-xs">Must be numeric and between 7 to 15 digits.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Password -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="password" class="form-label fw-medium mb-2">Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" placeholder="Enter password" minlength="6" required>
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="form-text text-muted text-xs mt-1">Minimum 6 characters.</div>
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="form-label fw-medium mb-2">Confirm Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" name="password_confirmation" class="form-control" 
                                                       id="password_confirmation" placeholder="Confirm password" minlength="6" required>
                                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback d-none" id="passwordMatchError">Passwords do not match.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Role -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="is_role" class="form-label fw-medium mb-2">System Role <span class="text-danger">*</span></label>
                                            <select name="is_role" id="is_role" class="form-select @error('is_role') is-invalid @enderror" required>
                                                <option value="2" {{ old('is_role') == 2 ? 'selected' : '' }}>Pharmacy Staff</option>
                                                <option value="1" {{ old('is_role') == 1 ? 'selected' : '' }}>Administrator</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="status" class="form-label fw-medium mb-2">Account Status <span class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                                <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="row mt-2">
                                    <div class="col-12 mb-4">
                                        <div class="form-group">
                                            <label for="profile_image" class="form-label fw-medium mb-2">Profile Picture</label>
                                            <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" 
                                                   id="profile_image" accept="image/*">
                                            <div class="form-text text-muted text-xs mt-1">Accepts jpeg, png, jpg, gif. Max size: 2MB.</div>
                                            
                                            <!-- Dynamic image preview container -->
                                            <div class="mt-3 d-none" id="previewContainer">
                                                <img src="#" id="imagePreview" alt="Profile Preview" 
                                                     class="rounded border shadow-sm" style="height: 100px; width: 100px; object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--bs-border-color) !important;">
                                    <a href="{{ url('admin/users') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save User</button>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const profileImageInput = document.getElementById('profile_image');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const form = document.getElementById('userForm');
        const passwordMatchError = document.getElementById('passwordMatchError');

        // Toggle Password Visibility
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });

        // Image Preview handler
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

        // Password matching validation on form submit
        form.addEventListener('submit', function(e) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                e.preventDefault();
                confirmPasswordInput.classList.add('is-invalid');
                passwordMatchError.classList.remove('d-none');
            } else {
                confirmPasswordInput.classList.remove('is-invalid');
                passwordMatchError.classList.add('d-none');
            }
        });

        // Match validation on input keyup
        confirmPasswordInput.addEventListener('keyup', function() {
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.classList.add('is-invalid');
                passwordMatchError.classList.remove('d-none');
            } else {
                confirmPasswordInput.classList.remove('is-invalid');
                passwordMatchError.classList.add('d-none');
            }
        });
    });
</script>
@endsection