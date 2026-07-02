@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Sales Invoices</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Invoices</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/invoices/create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Generate New Invoice
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
                    <h4 class="card-title fw-bold mb-0 text-white">Invoice Directory</h4>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>

                                    <th>Invoice Number</th>
                                    <th>Customer / Patient</th>
                                    <th>Invoice Date</th>
                                    <th>Gross Total</th>
                                    <th>Discount</th>
                                    <th>VAT / Tax</th>
                                    <th>Net Grand Total</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($getRecord as $value)
                                    <tr>

                                        <td class="fw-bold text-white">{{ $value->invoice_number }}</td>
                                        <td>
                                            {{ optional($value->getCustomerName)->name ?? 'N/A' }}
                                            <small class="d-block text-muted">{{ optional($value->getCustomerName)->phone }}</small>
                                        </td>
                                        <td>{{ $value->invoice_date }}</td>
                                        <td>${{ number_format($value->total_amount, 2) }}</td>
                                        <td class="text-danger">-${{ number_format($value->total_discount, 2) }}</td>
                                        <td>{{ number_format($value->tax, 1) }}%</td>
                                        <td class="fw-bold text-success">${{ number_format($value->net_total, 2) }}</td>
                                        <td class="text-xs text-muted">{{ $value->created_at }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ url('admin/invoices/edit/'.$value->id) }}"
                                                   class="btn btn-outline-primary btn-sm rounded-3 me-1">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <a href="{{ url('admin/invoices/delete/'.$value->id) }}"
                                                   class="btn btn-outline-danger btn-sm rounded-3"
                                                   onclick="return confirm('Are you sure you want to delete this invoice?');">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-danger py-4">
                                            No Invoices Found
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
