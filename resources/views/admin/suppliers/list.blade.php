@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <!-- Page Header -->
    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="mb-0">Suppliers</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Suppliers</li>
                    </ol>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12 text-right">
                    <a href="{{ url('admin/suppliers/create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add New
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
                            <h3 class="card-title">Supplier List</h3>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th style="width: 200px;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($getRecord as $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->phone }}</td>
                                            <td>{{ $value->email }}</td>
                                            <td>{{ $value->address }}</td>
                                            <td>{{ $value->created_at }}</td>
                                            <td>{{ $value->updated_at }}</td>

                                            <td>
                                                <a href="{{ url('admin/suppliers/edit/'.$value->id) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    Edit
                                                </a>

                                                <a href="{{ url('admin/suppliers/delete/'.$value->id) }}" 
                                                   class="btn btn-danger btn-sm">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-danger">
                                                No Record Found
                                            </td>
                                        </tr>
                                    @endforelse

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