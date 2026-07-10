@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Notification Center</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Real-Time Inventory & Business Alerts</h4>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill fw-bold">{{ count($notifications) }} Active Signals</span>
                </div>
                <div class="card-body p-4">
                    @forelse($notifications as $n)
                        <div class="d-flex align-items-start p-3 mb-3 border rounded" style="border-color: var(--bs-border-color) !important;">
                            <div class="rounded-circle p-3 me-3 d-flex align-items-center justify-content-center {{ $n['class'] }}" style="width: 50px; height: 50px; flex-shrink: 0;">
                                <i class="bi {{ $n['icon'] }} fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 class="fw-bold mb-0 text-white" style="font-size: 1rem;">{{ $n['title'] }}</h5>
                                    <span class="badge bg-secondary-subtle text-secondary small px-2 py-1 rounded">{{ $n['type'] }} Alert</span>
                                </div>
                                <p class="text-muted mb-0 small">{!! $n['message'] !!}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-shield-check text-success fs-1"></i>
                            <h5 class="fw-bold text-white mt-3">All Systems Nominal</h5>
                            <p class="text-xs mb-0">No inventory threshold anomalies, expired medicines, or payment reminders found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
