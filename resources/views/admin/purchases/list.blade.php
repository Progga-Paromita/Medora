@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Purchase List</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Purchase List
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/purchases/add') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i>
                        Add New Purchase
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
                    <h3 class="card-title">Purchase List</h3>
                </div>

                <div class="card-body">

                    <table class="table table-bordered table-striped">

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Supplier Name</th>
                                <th>Invoice Number</th>
                                <th>Voucher Number</th>
                                <th>Purchase Date</th>
                                <th>Total Amount</th>
                                <th>Payment Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th width="180">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($getPurchases as $purchase)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $purchase->getSupplierName->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $purchase->getInvoiceNo->invoice_number ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $purchase->voucher_number }}
                                    </td>

                                    <td>
                                        {{ date('Y-m-d', strtotime($purchase->purchase_date)) }}
                                    </td>

                                    <td>
                                        {{ $purchase->net_total }}
                                    </td>

                                    <td>

                                        @if($purchase->payment_status == 1)

                                            <span class="badge bg-warning">
                                                Pending
                                            </span>

                                        @elseif($purchase->payment_status == 2)

                                            <span class="badge bg-success">
                                                Accepted
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>

                                        @endif

                                    </td>

                                    <td>
                                        {{ date('Y-m-d H:i:s', strtotime($purchase->created_at)) }}
                                    </td>

                                    <td>
                                        {{ date('Y-m-d H:i:s', strtotime($purchase->updated_at)) }}
                                    </td>

                                    <td>

                                        <a href="{{ url('admin/purchases/edit/'.$purchase->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>

                                        <a href="{{ url('admin/purchases/delete/'.$purchase->id) }}"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this purchase?');">
                                            <i class="bi bi-trash"></i>
                                            Delete
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="10" class="text-center">
                                        No Purchase Record Found
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
