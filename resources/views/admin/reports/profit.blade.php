@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Monthly Profit Analysis</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/reports/dashboard') }}" class="text-decoration-none">BI Analytics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profit Analysis</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <!-- Profit analysis list card -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4">
                    <h4 class="card-title fw-bold mb-0 text-white">Monthly profit breakdown</h4>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Year - Month</th>
                                    <th class="text-end">Sales Revenue ($)</th>
                                    <th class="text-end">Purchases Cost ($)</th>
                                    <th class="text-end">Gross Profit ($)</th>
                                    <th class="text-end">Margin (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $index => $value)
                                    @php
                                        // Margin = Profit / Revenue
                                        $margin = $value['sales'] > 0 ? ($value['profit'] / $value['sales']) * 100 : 0.00;
                                    @endphp
                                    <tr>
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value['year'] }} - {{ $value['month_name'] }}</td>
                                        <td class="text-end fw-semibold text-white">${{ number_format($value['sales'], 2) }}</td>
                                        <td class="text-end text-danger">-${{ number_format($value['purchases'], 2) }}</td>
                                        <td class="text-end fw-bold text-success">${{ number_format($value['profit'], 2) }}</td>
                                        <td class="text-end fw-bold text-success">{{ number_format($margin, 1) }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-danger py-4">
                                            No financial data recorded for profit analysis yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        {!! $getRecord->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
