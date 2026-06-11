@extends('layouts.app')

@section('content')

<section class="content">
<div class="container-fluid mt-3 mb-3">
<div class="row">
<div class="col-md-8">
<div class="card card-primary">
<div class="card-header">
<h3 class="card-title">Add New Customers</h3>
</div>
<div class="card-body">

<form action="{{ url('admin/customers/create') }}" method="post">
    {{csrf_field()}}

<div class="form-group">
<label for="name">Name</label>
<input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="Enter name" required>
</div>



<div class="form-group">
<label for="phone">Phone</label>
<input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone') }}" placeholder="Enter phone">
</div>

<div class="form-group">
<label for="email">Email</label>
<input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="Enter email">
</div>

<div class="form-group">
<label for="address">Address</label>
<input type="text" name="address" class="form-control" id="address" value="{{ old('address') }}" placeholder="Enter password">
</div>

<div class="form-group">
    <label for="doctor_name">Doctor's name</label>
    <input type="text"
           name="doctor_name"
           class="form-control"
           id="doctor_name"
           value="{{ old('doctor_name') }}"
           placeholder="Enter doctor's name">
</div>

<div class="form-group">
    <label for="doctor_address">Doctor's Address</label>
    <input type="text"
           name="doctor_address"
           class="form-control"
           id="doctor_address"
           value="{{ old('doctor_address') }}"
           placeholder="Enter doctor's address">
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