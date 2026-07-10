@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">System Activity & Audit Logs</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Audit Logs</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <!-- Search and Filter Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label small fw-medium">Search Logs</label>
                            <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Action, IP address, username...">
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label small fw-medium">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label small fw-medium">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2 text-end align-self-end">
                            <a href="{{ url('admin/activity-logs') }}" class="btn btn-secondary me-2">Clear</a>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Logs list card -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4">
                    <h4 class="card-title fw-bold mb-0 text-white">System Audit Trail</h4>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Account</th>
                                    <th>Performed Action</th>
                                    <th>IP Address</th>
                                    <th>Device / User Agent</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">
                                            @if($value->user_name)
                                                {{ $value->user_name }}
                                                <small class="d-block text-muted">{{ $value->user_email }}</small>
                                            @else
                                                <span class="text-muted">Guest / System</span>
                                            @endif
                                        </td>
                                        <td class="text-white">{{ $value->action }}</td>
                                        <td><code>{{ $value->ip_address }}</code></td>
                                        <td class="text-muted text-xs" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $value->user_agent }}
                                        </td>
                                        <td>{{ date('M d, Y h:i A', strtotime($value->created_at)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-danger py-4">
                                            No system activity logs found.
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
