@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Stock Registry</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/inventory/dashboard') }}" class="text-decoration-none">Inventory Control</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Registry</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mt-3 mb-5">
            <!-- Search & Filters Panel -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="row g-3">
                        <!-- Keyword Search -->
                        <div class="col-lg-3 col-md-6">
                            <label for="search" class="form-label small fw-medium">Search stock</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" id="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Medicine, generic, or batch...">
                            </div>
                        </div>

                        <!-- Supplier -->
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <label for="supplier_id" class="form-label small fw-medium">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-select">
                                <option value="">All Suppliers</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stock Level -->
                        <div class="col-lg-2 col-md-3 col-sm-6">
                            <label for="stock_status" class="form-label small fw-medium">Stock Status</label>
                            <select name="stock_status" id="stock_status" class="form-select">
                                <option value="">All Levels</option>
                                <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock (>= 20)</option>
                                <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock (< 20)</option>
                                <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock (= 0)</option>
                            </select>
                        </div>

                        <!-- Expiration Status -->
                        <div class="col-lg-2 col-md-3 col-sm-6">
                            <label for="expiry_status" class="form-label small fw-medium">Expiry status</label>
                            <select name="expiry_status" id="expiry_status" class="form-select">
                                <option value="">All Expiries</option>
                                <option value="active" {{ request('expiry_status') === 'active' ? 'selected' : '' }}>Active (Non-Expired)</option>
                                <option value="near_expiry" {{ request('expiry_status') === 'near_expiry' ? 'selected' : '' }}>Near Expiry (<= 30 days)</option>
                                <option value="expired" {{ request('expiry_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>

                        <!-- Sort inputs -->
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'stock.id') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

                        <!-- Actions -->
                        <div class="col-lg-2 col-md-12 text-end align-self-end">
                            <a href="{{ url('admin/inventory/stock') }}" class="btn btn-secondary me-1">
                                <i class="bi bi-arrow-clockwise"></i> Clear
                            </a>
                            <button type="submit" class="btn btn-primary px-3">
                                <i class="bi bi-funnel-fill"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stock List Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header p-4 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold mb-0 text-white">Stock registry records</h4>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $getRecord->total() }} batches tracked</span>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'medicine', 'sort_order' => request('sort_by') == 'medicine' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Medicine Product
                                            @if(request('sort_by') == 'medicine')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Generic Name</th>
                                    <th>Packaging</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'batch', 'sort_order' => request('sort_by') == 'batch' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Batch Number
                                            @if(request('sort_by') == 'batch')
                                                <i class="bi bi-sort-alpha-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'quantity', 'sort_order' => request('sort_by') == 'quantity' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Current Quantity
                                            @if(request('sort_by') == 'quantity')
                                                <i class="bi bi-sort-numeric-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Purchase Rate</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'mrp', 'sort_order' => request('sort_by') == 'mrp' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Selling MRP
                                            @if(request('sort_by') == 'mrp')
                                                <i class="bi bi-sort-numeric-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'expiry', 'sort_order' => request('sort_by') == 'expiry' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                            Expiry Date
                                            @if(request('sort_by') == 'expiry')
                                                <i class="bi bi-sort-numeric-{{ request('sort_order') == 'asc' ? 'down' : 'up' }} ms-1"></i>
                                            @else
                                                <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size: 0.8rem;"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Inventory Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    @php
                                        $today = date('Y-m-d');
                                        $expiry = $value->expiry_date;
                                        $daysLeft = (strtotime($expiry) - strtotime($today)) / 86400;

                                        // Status determinations
                                        if ($expiry < $today) {
                                            $statusText = 'Expired';
                                            $badgeClass = 'bg-danger text-white'; // Dark Red
                                            $style = 'background-color: #7f1d1d !important; color: #fff !important;';
                                        } elseif ($daysLeft >= 0 && $daysLeft <= 30) {
                                            $statusText = 'Near Expiry';
                                            $badgeClass = 'bg-warning text-dark'; // Orange
                                            $style = 'background-color: #f97316 !important; color: #fff !important;';
                                        } elseif ($value->quantity == 0) {
                                            $statusText = 'Out of Stock';
                                            $badgeClass = 'bg-danger text-white'; // Red
                                            $style = 'background-color: #ef4444 !important; color: #fff !important;';
                                        } elseif ($value->quantity < 20) {
                                            $statusText = 'Low Stock';
                                            $badgeClass = 'bg-warning text-dark'; // Yellow
                                            $style = 'background-color: #eab308 !important; color: #000 !important;';
                                        } else {
                                            $statusText = 'In Stock';
                                            $badgeClass = 'bg-success text-white'; // Green
                                            $style = 'background-color: #22c55e !important; color: #fff !important;';
                                        }
                                    @endphp
                                    <tr>
                                        <!-- No Database IDs, sequence aware -->
                                        <td>{{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}</td>
                                        <td class="fw-bold text-white">{{ $value->medicine_name }}</td>
                                        <td class="text-secondary">{{ $value->generic_name }}</td>
                                        <td>{{ $value->packaging }}</td>
                                        <td><span class="badge bg-secondary">{{ $value->batch_id }}</span></td>
                                        <td class="fw-semibold text-white">{{ $value->quantity }} units</td>
                                        <td>${{ number_format($value->rate, 2) }}</td>
                                        <td class="fw-bold text-success">${{ number_format($value->mrp, 2) }}</td>
                                        <td>
                                            {{ date('M d, Y', strtotime($value->expiry_date)) }}
                                            @if($expiry >= $today && $daysLeft <= 30)
                                                <small class="d-block text-warning text-xs">({{ (int)$daysLeft }} days left)</small>
                                            @elseif($expiry < $today)
                                                <small class="d-block text-danger text-xs">(Expired)</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill px-3 py-1.5 fw-semibold" style="{{ $style }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-danger py-4">
                                            No stock records matching the filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-4">
                        {!! $getRecord->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
