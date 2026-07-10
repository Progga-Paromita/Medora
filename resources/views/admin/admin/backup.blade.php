@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Database Backup & Recovery Console</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Backups</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            @include('message')

            <div class="row g-4">
                <!-- Backup actions column -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header p-4">
                            <h5 class="fw-bold mb-0 text-white">Create New Backup</h5>
                        </div>
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-cloud-arrow-up text-primary" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Exports the entire SQLite/MySQL database schema structure along with table records into a self-contained SQL script file.</p>
                            <form action="{{ url('admin/backup') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold">
                                    <i class="bi bi-cpu me-2"></i> Run Backup Exporter
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h5 class="fw-bold mb-0 text-white">Upload & Restore Database</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
                                <div class="small">
                                    <strong>WARNING:</strong> Restoring will drop all current tables and replace them with the uploaded SQL backup files. Take a current backup first!
                                </div>
                            </div>
                            <form action="{{ url('admin/backup/restore') }}" method="POST" enctype="multipart/form-dom" class="mt-4" id="restoreForm">
                                @csrf
                                <input type="hidden" name="_method" value="POST">
                                <div class="mb-3">
                                    <label for="backup_file" class="form-label small fw-semibold">Select .sql Backup File</label>
                                    <!-- Use name="backup_file" -->
                                    <input type="file" name="backup_file" id="backup_file" class="form-control" accept=".sql" required>
                                </div>
                                <button type="submit" class="btn btn-danger w-100 py-2.5 fw-bold" onclick="return confirm('Are you absolutely sure you want to drop current database and restore this file? This cannot be undone!')">
                                    <i class="bi bi-arrow-counterclockwise me-2"></i> Upload & Restore Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Backups list column -->
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header p-4">
                            <h5 class="fw-bold mb-0 text-white">Available Backup SQL Scripts</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>File Name</th>
                                            <th>File Size</th>
                                            <th>Generated Date</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($backups as $index => $b)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="fw-bold text-white"><i class="bi bi-filetype-sql text-primary me-2"></i> {{ $b['name'] }}</td>
                                                <td>{{ $b['size'] }}</td>
                                                <td>{{ date('M d, Y h:i A', strtotime($b['date'])) }}</td>
                                                <td class="text-end">
                                                    <a href="{{ url('admin/backup/download/' . $b['name']) }}" class="btn btn-outline-success btn-sm rounded-pill px-3 me-1">
                                                        <i class="bi bi-download"></i> Download
                                                    </a>
                                                    <a href="{{ url('admin/backup/delete/' . $b['name']) }}" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this backup file?')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-5">
                                                    No backup files generated yet. Run the exporter above.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
