@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>Administrators</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Administrators</li>
                    </ol>
                </div>

            </div>

            <div class="row mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/users/create') }}" class="btn btn-primary">
                        <i class="nav-icon bi bi-plus"></i> Add New
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            @include('message')
            <div class="row">
                <div class="col-md-12">

                    <div class="card card-primary">

                        <div class="card-header">
                            <h3 class="card-title">Administrators List</h3>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-striped">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                @foreach($getRecord as $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <!-- Photo -->
                                        <td>
                                            <img src="{{ $value->getProfileImage() }}"
                                                 style="width:50px; height:50px; border-radius:50%;">
                                        </td>

                                        <td>{{ $value->name }} {{ $value->last_name }}</td>
                                        <td>{{ $value->phone }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td>{{ $value->status == 1 ? 'Active' : 'Inactive' }}</td>
                                        <td>{{ $value->is_role == 1 ? 'Admin' : 'User' }}</td>
                                        <td>{{ $value->created_at }}</td>
                                        <td>{{ $value->updated_at }}</td>

                                        <td>
                                            <a href="{{ url('admin/users/edit/'.$value->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="nav-icon bi bi-pencil"></i> Edit
                                            </a>

                                            <a href="{{ url('admin/users/delete/'.$value->id) }}"
                                               class="btn btn-danger btn-sm">
                                                <i class="nav-icon bi bi-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>

</div>

@endsection