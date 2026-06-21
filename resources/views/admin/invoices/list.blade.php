@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Invoices</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Invoices</li>
                    </ol>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/invoices/create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add New
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
                    <h3 class="card-title">Invoice List</h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice Number</th>
                                <th>Customer Name</th>
                                <th>Invoice Date</th>
                                <th>Email</th>
                                <th>Total Amount</th>
                                <th>Total Discount</th>
                                <th>Tax</th>
                                <th>Grand Total</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($getRecord as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->invoice_number }}</td>
                                <td>{{ $value->getCustomerName->name ?? 'N/A' }}</td>
                                <td>{{ $value->invoice_date }}</td>
                                <td>{{ $value->getCustomerName->email ?? 'N/A' }}</td>
                                <td>{{ number_format($value->total_amount, 2) }}</td>
                                <td>{{ number_format($value->total_discount, 2) }}</td>
                                <td>{{ number_format($value->tax, 2) }}%</td>
                                <td>{{ number_format($value->net_total, 2) }}</td>
                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->updated_at }}</td>
                                <td style="width: 200px;">
                                    <a href="{{ url('admin/invoices/edit/'.$value->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="{{ url('admin/invoices/delete/'.$value->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

</div>

@endsection
