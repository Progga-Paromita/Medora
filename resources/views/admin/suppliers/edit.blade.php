@extends('layouts.app')

@section('content')
<section class="content">
    <div class="container-fluid mt-3 mb-3">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">

                    <div class="card-header">
                        <h3 class="card-title">Edit Supplier</h3>
                    </div>

                    <form action="{{ url('admin/suppliers/edit/' . $getRecord->id) }}" method="post">
                        @csrf

                        <div class="card-body">

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control" 
                                       id="name" 
                                       value="{{ $getRecord->name }}" 
                                       placeholder="Enter name" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control" 
                                       id="email" 
                                       value="{{ $getRecord->email }}" 
                                       placeholder="Enter email">
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" 
                                       name="phone" 
                                       class="form-control" 
                                       id="phone" 
                                       value="{{ $getRecord->phone }}" 
                                       placeholder="Enter phone">
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" 
                                       name="address" 
                                       class="form-control" 
                                       id="address" 
                                       value="{{ $getRecord->address }}" 
                                       placeholder="Enter address">
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="nav-icon bi bi-save"></i> Update
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection