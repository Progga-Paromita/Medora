@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Customers</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customers</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/customers/create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Add New Customer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            @include('message')

            <div class="card shadow-sm border-0">
                <div class="card-header p-4">
                    <h4 class="card-title fw-bold mb-0 text-white">Customer Registry</h4>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>

                                    <th>Customer Name</th>
                                    <th>Phone Number</th>
                                    <th>Email Address</th>
                                    <th>Home Address</th>
                                    <th>Doctor Name</th>
                                    <th>Clinic Address</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($customers as $value)
                                    <tr>

                                        <td class="fw-bold text-white">{{ $value->name }}</td>
                                        <td>{{ $value->phone ?? 'N/A' }}</td>
                                        <td class="text-secondary">{{ $value->email ?? 'N/A' }}</td>
                                        <td>{{ $value->address ?? 'N/A' }}</td>
                                        <td class="fw-bold" style="color: #FACA5A;">{{ $value->doctor_name ?? 'N/A' }}</td>
                                        <td class="text-xs text-muted">{{ $value->doctor_address ?? 'N/A' }}</td>
                                        <td class="text-xs text-muted">{{ $value->created_at }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ url('admin/customers/edit/'.$value->id) }}"
                                                   class="btn btn-outline-primary btn-sm rounded-3 me-1">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <a href="{{ url('admin/customers/delete/'.$value->id) }}"
                                                   class="btn btn-outline-danger btn-sm rounded-3"
                                                   onclick="return confirm('Are you sure you want to delete this customer?');">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-danger py-4">
                                            No Customers Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
