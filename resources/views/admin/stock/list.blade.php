@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Inventory Stock</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Stock</li>
                    </ol>
                </div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ url('admin/stocks/create') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add New Stock
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            @include('message')

            <!-- Search and Filter Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <!-- Search Box -->
                        <div class="col-lg-3 col-md-6">
                            <label for="search" class="form-label small fw-medium">Search</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" id="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Medicine name or Batch ID...">
                            </div>
                        </div>

                        <!-- Medicine ID dropdown -->
                        <div class="col-lg-3 col-md-6">
                            <label for="medicine_id" class="form-label small fw-medium">Medicine Filter</label>
                            <select name="medicine_id" id="medicine_id" class="form-select">
                                <option value="">All Medicines</option>
                                @foreach($medicines as $med)
                                    <option value="{{ $med->id }}" {{ request('medicine_id') == $med->id ? 'selected' : '' }}>
                                        {{ $med->name }} ({{ $med->packaging }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Expiry Status -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label for="expiry_status" class="form-label small fw-medium">Expiry status</label>
                            <select name="expiry_status" id="expiry_status" class="form-select">
                                <option value="">All Batches</option>
                                <option value="expired" {{ request('expiry_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="near_expiry" {{ request('expiry_status') === 'near_expiry' ? 'selected' : '' }}>Near Expiry (90 Days)</option>
                            </select>
                        </div>

                        <!-- Quantity Filter -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label for="low_stock" class="form-label small fw-medium">Quantity</label>
                            <select name="low_stock" id="low_stock" class="form-select">
                                <option value="">All Quantities</option>
                                <option value="low" {{ request('low_stock') === 'low' ? 'selected' : '' }}>Low Stock (< 10)</option>
                                <option value="mid" {{ request('low_stock') === 'mid' ? 'selected' : '' }}>Medium Stock (10 - 100)</option>
                                <option value="high" {{ request('low_stock') === 'high' ? 'selected' : '' }}>High Stock (> 100)</option>
                            </select>
                        </div>

                        <!-- Limit size -->
                        <div class="col-lg-1 col-md-2 col-sm-6">
                            <label for="limit" class="form-label small fw-medium">Show</label>
                            <select name="limit" id="limit" class="form-select">
                                <option value="10" {{ request('limit') == '10' ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('limit') == '20' ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('limit') == '50' ? 'selected' : '' }}>50</option>
                            </select>
                        </div>

                        <!-- Preserved Sorting -->
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                        <!-- Actions -->
                        <div class="col-lg-1 col-md-2 col-sm-6 align-self-end text-end">
                            <a href="{{ url('admin/stocks') }}" class="btn btn-secondary w-100 me-2" title="Clear Filters">
                                <i class="bi bi-arrow-clockwise"></i> Clear
                            </a>
                        </div>
                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-funnel-fill me-1"></i> Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stock directory Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Stock Registry</h4>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $getRecord->total() }} batches tracked</span>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'medicine', 'sort_order' => request('sort_by') == 'medicine' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Medicine Name
                                            @if(request('sort_by') == 'medicine')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'batch', 'sort_order' => request('sort_by') == 'batch' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Batch ID
                                            @if(request('sort_by') == 'batch')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'quantity', 'sort_order' => request('sort_by') == 'quantity' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Available Qty
                                            @if(request('sort_by') == 'quantity')
                                                <i class="bi bi-sort-numeric-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($getRecord as $value)
                                    <tr>
                                        <!-- No Database IDs displayed, using sequential row number -->
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">
                                            {{ optional($value->getMedicine)->name ?? 'N/A' }}
                                            <small class="d-block text-secondary">{{ optional($value->getMedicine)->generic_name }}</small>
                                        </td>
                                        <td class="fw-medium text-warning">{{ $value->batch_id }}</td>
                                        <td class="fw-bold fs-6">{{ $value->quantity }}</td>
                                        <td>
                                            @if($value->expiry_date < date('Y-m-d'))
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Expired</span>
                                            @elseif($value->quantity == 0)
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill">Out of Stock</span>
                                            @elseif($value->quantity < 10)
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Low Stock</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">In Stock</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                <a href="{{ url('admin/stocks/show/'.$value->id) }}"
                                                   class="btn btn-outline-info btn-sm rounded-3" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ url('admin/stocks/edit/'.$value->id) }}"
                                                   class="btn btn-outline-primary btn-sm rounded-3" title="Edit Batch">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ url('admin/stocks/delete/'.$value->id) }}"
                                                   class="btn btn-outline-danger btn-sm rounded-3" title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this stock entry?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-danger py-5">
                                            <i class="bi bi-exclamation-circle fs-3 mb-2 d-block"></i>
                                            No Stock Entries Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-end mt-4">
                        {!! $getRecord->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
