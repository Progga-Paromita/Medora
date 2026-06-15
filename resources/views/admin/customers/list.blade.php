@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Customers</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Customers</li>
                    </ol>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/customers/create') }}" class="btn btn-primary">
                        <i class="nav-icon bi bi-plus"></i> Add New
                    </a>
                </div>
            </div>

        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            @include('message')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer List</h3>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Doctor Name</th>
                                <th>Doctor Address</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($customers as $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->phone }}</td>
                                    <td>{{ $value->email }}</td>
                                    <td>{{ $value->address }}</td>
                                    <td>{{ $value->doctor_name }}</td>
                                    <td>{{ $value->doctor_address }}</td>
                                    <td>{{ $value->status ?? 'N/A' }}</td>
                                    <td>{{ $value->created_at }}</td>
                                    <td>{{ $value->updated_at }}</td>

                                    <td>
                                        <a href="{{ url('admin/customers/edit/'.$value->id) }}" class="btn btn-primary btn-sm">
                                            Edit
                                        </a>

                                        <a href="{{ url('admin/customers/delete/'.$value->id) }}" class="btn btn-danger btn-sm">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-danger">
                                        No Record Found
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>
            </div>

        </div>
    </section>

</div>

@endsection