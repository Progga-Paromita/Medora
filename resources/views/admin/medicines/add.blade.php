@extends('layouts.app')

@section('content')

<section class="content">
<div class="container-fluid mt-3 mb-3">
<div class="row">
<div class="col-md-8">
<div class="card card-primary">
<div class="card-header">
<h3 class="card-title">Add New Medicines</h3>
</div>
<div class="card-body">

<form action="{{ url('admin/medicines/create') }}" method="post" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="form-group">
    <label for="profile_image">Image</label>
    <input type="file" name="profile_image" class="form-control" id="profile_image">
</div>

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="Enter name" required>
</div>

<div class="form-group">
    <label for="packaging">Packaging</label>
    <input type="text" name="packaging" class="form-control" id="packaging" value="{{ old('packaging') }}" placeholder="Enter packaging">
</div>

<div class="form-group">
    <label for="generic_name">Generic Name</label>
    <input type="text" name="generic_name" class="form-control" id="generic_name" value="{{ old('generic_name') }}" placeholder="Enter generic name">
</div>

<div class="form-group">
    <label for="supplier_id">Supplier Name</label>
    <select name="supplier_id" class="form-control">
        <option value="">Select Supplier</option>

        @foreach($getSuppliers as $supplier)
            <option value="{{ $supplier->id }}">{{ $supplier->name }}    
            </option>
        @endforeach

    </select>
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