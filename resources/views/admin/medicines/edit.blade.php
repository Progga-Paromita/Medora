@extends('layouts.app')

@section('content')

<section class="content">
    <div class="container-fluid mt-3 mb-3">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card card-primary">

                    <div class="card-header">
                        <h3 class="card-title">Edit Medicines</h3>
                    </div>

                    <div class="card-body">

                        <form action="{{ url('admin/medicines/edit/'.$getRecord->id) }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group mb-3">
                                <label for="profile_image">Profile Picture</label>
                                <input type="file" name="profile_image" class="form-control" id="profile_image">

                                @if(!empty($getRecord->getProfileImage()))
                                    <br>
                                    <img src="{{ $getRecord->getProfileImage() }}" width="100" height="100">
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name"
                                       value="{{ $getRecord->name }}"
                                       placeholder="Enter name" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="packaging">Packaging</label>
                                <input type="text" name="packaging" class="form-control" id="packaging"
                                       value="{{ $getRecord->packaging }}"
                                       placeholder="Enter packaging">
                            </div>

                            <div class="form-group mb-3">
                                <label for="generic_name">Generic Name</label>
                                <input type="text" name="generic_name" class="form-control" id="generic_name"
                                       value="{{ $getRecord->generic_name }}"
                                       placeholder="Enter generic name">
                            </div>

                            <div class="form-group mb-3">
                                <label for="supplier_id">Supplier ID</label>
                                <input type="text" name="supplier_id" class="form-control" id="supplier_id"
                                       value="{{ $getRecord->supplier_id }}"
                                       placeholder="Enter supplier name">
                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="nav-icon bi bi-save"></i> Update
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>
</section>

@endsection