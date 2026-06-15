@extends('layouts.app')

@section('content')

<section class="content">
<div class="container-fluid mt-3 mb-3">
<div class="row">
<div class="col-md-8">
<div class="card card-primary">
<div class="card-header">
<h3 class="card-title">Add New Stock</h3>
</div>
<div class="card-body">

<form action="{{ url('admin/stocks/create') }}" method="post" >
{{ csrf_field() }}


<div class="form-group">
    <label for="name">Medicine</label>
    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="Enter name" required>
</div>

<div class="form-group">
    <label for="medicine_id">Medicine</label>
    <select name="medicine_id" class="form-control">
        <option value="">Select Medicine</option>
        @foreach($medicines as $medicine)
            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="batch_id">Batch ID</label>
    <input type="text" name="batch_id" class="form-control" id="batch_id"
           value="{{ old('batch_id') }}" placeholder="Enter batch id">
</div>

<div class="form-group">
    <label for="expiry_date">Expiry Date</label>
    <input type="date" name="expiry_date" class="form-control" id="expiry_date"
           value="{{ old('expiry_date') }}">
</div>

<div class="form-group">
    <label for="quantity">Quantity</label>
    <input type="number" name="quantity" class="form-control" id="quantity"
           value="{{ old('quantity') }}" placeholder="Enter quantity">
</div>

<div class="form-group">
    <label for="mrp">MRP (Maximum Retail Price)</label>
    <input type="number" name="mrp" class="form-control" id="mrp"
           value="{{ old('mrp') }}" placeholder="Enter mrp">
</div>

<div class="form-group">
    <label for="rate">Rate (Selling Price)</label>
    <input type="number" name="rate" class="form-control" id="rate"
           value="{{ old('rate') }}" placeholder="Enter rate">
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