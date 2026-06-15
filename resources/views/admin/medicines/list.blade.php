@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Medicines</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Medicines</li>
                    </ol>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/medicines/create') }}" class="btn btn-primary">
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
                    <h3 class="card-title">Medicine List</h3>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Image</th>
                                <th>Name</th>
                                <th>Packaging</th>
                                <th>Generic Name</th>
                                <th>Supplier</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($getRecord as $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <img src="{{ $value->getProfileImage() }}"
                                             style="width:50px; height:50px; border-radius:50%;">
                                    </td>

                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->packaging }}</td>
                                    <td>{{ $value->generic_name }}</td>
                                    <td>{{ optional($value->getSupplierName)->name }}</td>
                                    <td>{{ $value->created_at }}</td>
                                    <td>{{ $value->updated_at }}</td>

                                    <td>
                                        <a href="{{ url('admin/medicines/edit/'.$value->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="nav-icon bi bi-pencil"></i> Edit
                                        </a>

                                        <a href="{{ url('admin/medicines/delete/'.$value->id) }}"
                                           class="btn btn-danger btn-sm">
                                            <i class="nav-icon bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-danger">
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