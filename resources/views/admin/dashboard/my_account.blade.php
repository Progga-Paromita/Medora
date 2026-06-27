@extends('layouts.app')

@section('content')

<section class="content">
<div class="container-fluid mt-3 mb-3">
<div class="row">
<div class="col-md-8">

<div class="card card-primary">

<div class="card-header">
<h3 class="card-title">Profile Update</h3>
</div>

<div class="card-body">

<form action="{{ url('admin/users/edit/'.$getRecord->id) }}" method="post" enctype="multipart/form-data">
{{ csrf_field() }}




<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="nav-icon bi bi-save"></i> Update</button>
</div>

</form>

</div>
</div>

</div>
</div>
</div>
</section>

@endsection
