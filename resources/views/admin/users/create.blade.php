@extends('layouts.app')

@section('content')

<section class="content">
<div class="container-fluid mt-3 mb-3">
<div class="row">
<div class="col-md-8">
<div class="card card-primary">
<div class="card-header">
<h3 class="card-title">Add New User</h3>
</div>
<div class="card-body">

<form action="{{ url('admin/users/create') }}" method="post" enctype="multipart/form-data">
@csrf

<div class="form-group">
<label for="name">Name</label>
<input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="Enter name" required>
</div>

<div class="form-group">
<label for="last_name">Last Name</label>
<input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name') }}" placeholder="Enter last name">
</div>

<div class="form-group">
<label for="email">Email</label>
<input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="Enter email">
</div>

<div class="form-group">
<label for="password">Password</label>
<input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
</div>

<div class="form-group">
<label for="phone">Phone</label>
<input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone') }}" placeholder="Enter phone">
</div>

<div class="form-group">
<label for="profile_image">Profile Picture</label>
<input type="file" name="profile_image" class="form-control" id="profile_image">
</div>

<div class="form-group">
<label for="is_role">Role</label>
<select name="is_role" id="is_role" value="{{ old('is_role') }}" class="form-control">
<option value="1">Admin</option>
<option value="2">User</option>
</select>
</div>

<div class="form-group">
<label for="status">Status</label>
<select name="status" id="status" value="{{ old('status') }}" class="form-control">
<option value="1">Active</option>
<option value="0">Inactive</option>
</select>
</div>

</div>

<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="nav-icon bi bi-save"></i> Submit</button>
</div>

</form>

</div>
</div>

</div>
</div>
</div>
</section>

@endsection