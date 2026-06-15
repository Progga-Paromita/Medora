@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Stock List</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Stock</li>
                    </ol>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/stocks/create') }}" class="btn btn-primary">
                        Add New
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
                    <h3 class="card-title">Stock List</h3>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Medicine</th>
                                <th>Batch ID</th>
                                <th>Expiry Date</th>
                                <th>Quantity</th>
                                <th>MRP</th>
                                <th>Rate</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        @forelse ($getRecord as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ optional($value->getMedicine)->name ?? 'N/A' }}
                                </td>

                                <td>{{ $value->batch_id }}</td>
                                <td>{{ $value->expiry_date }}</td>
                                <td>{{ $value->quantity }}</td>
                                <td>{{ $value->mrp }}</td>
                                <td>{{ $value->rate }}</td>

                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->updated_at }}</td>

                                <td>
                                    <a href="{{ url('admin/stocks/edit/'.$value->id) }}" class="btn btn-primary btn-sm">
                                        Edit
                                    </a>

                                    <a href="{{ url('admin/stocks/delete/'.$value->id) }}" class="btn btn-danger btn-sm">
                                        Delete
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-danger">
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