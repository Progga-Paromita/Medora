@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Stock Movement Ledger</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/inventory/dashboard') }}" class="text-decoration-none">Inventory Control</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Movement History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Inventory Transaction History Log</h4>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill fw-bold">Real-time Movement Feed</span>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Transaction Date</th>
                                    <th>Medicine Name</th>
                                    <th>Movement Type</th>
                                    <th>Quantity Transacted</th>
                                    <th>Reference / Justification</th>
                                    <th>Handled By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    @php
                                        // style movement tags
                                        if (str_contains($value->type, 'Purchase')) {
                                            $style = 'background-color: #22c55e !important; color: #fff !important;';
                                            $badgeText = 'Stock In (Purchase)';
                                        } elseif (str_contains($value->type, 'Sale')) {
                                            $style = 'background-color: #ef4444 !important; color: #fff !important;';
                                            $badgeText = 'Stock Out (Sale)';
                                        } elseif (str_contains($value->type, 'Increase')) {
                                            $style = 'background-color: #3b82f6 !important; color: #fff !important;';
                                            $badgeText = 'Manual Increase';
                                        } else {
                                            $style = 'background-color: #f97316 !important; color: #fff !important;';
                                            $badgeText = 'Manual Decrease';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td>{{ date('M d, Y h:i A', strtotime($value->transaction_date)) }}</td>
                                        <td class="fw-bold text-white">{{ $value->medicine_name }}</td>
                                        <td>
                                            <span class="badge rounded-pill px-3 py-1.5 fw-semibold" style="{{ $style }}">
                                                {{ $badgeText }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-white">
                                            @if(str_contains($value->type, 'Purchase') || str_contains($value->type, 'Increase'))
                                                +{{ $value->quantity }} units
                                            @else
                                                -{{ $value->quantity }} units
                                            @endif
                                        </td>
                                        <td>
                                            @if(str_contains($value->type, 'Purchase'))
                                                Voucher: <strong class="text-white">{{ $value->reference }}</strong>
                                            @elseif(str_contains($value->type, 'Sale'))
                                                Invoice: <strong class="text-white">{{ $value->reference }}</strong>
                                            @else
                                                Reason: <span class="text-muted text-xs">{{ $value->reference }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $value->user_name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            No inventory movements recorded yet. Make purchases, generate sales, or perform adjustments.
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
