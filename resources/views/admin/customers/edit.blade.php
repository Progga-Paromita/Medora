@extends('layouts.app')

@section('content')

<section class="content">
    <div class="container-fluid mt-3 mb-3">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card card-primary">

                    <div class="card-header">
                        <h3 class="card-title">Edit User</h3>
                    </div>

                    <div class="card-body">

                        <form action="{{ url('admin/customers/edit/'.$getRecord->id) }}" method="post">
                            {{ csrf_field() }}

                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name"
                                    class="form-control"
                                    value="{{ $getRecord->name }}"
                                    placeholder="Enter name">
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email"
                                    class="form-control"
                                    value="{{ $getRecord->email }}"
                                    placeholder="Enter email">
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone"
                                    class="form-control"
                                    value="{{ $getRecord->phone }}"
                                    placeholder="Enter phone">
                            </div>

                            <div class="form-group mb-3">
                                <label for="address">Address</label>
                                <input type="text" name="address" id="address"
                                    class="form-control"
                                    value="{{ $getRecord->address }}"
                                    placeholder="Enter address">
                            </div>

                            <div class="form-group mb-3">
                                <label for="doctor_name">Doctor's Name</label>
                                <input type="text" name="doctor_name" id="doctor_name"
                                    class="form-control"
                                    value="{{ $getRecord->doctor_name }}"
                                    placeholder="Enter doctor's name">
                            </div>

                            <div class="form-group mb-3">
                                <label for="doctor_address">Doctor's Address</label>
                                <input type="text" name="doctor_address" id="doctor_address"
                                    class="form-control"
                                    value="{{ $getRecord->doctor_address }}"
                                    placeholder="Enter doctor's address">
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