<aside class="app-sidebar shadow">
    <div class="sidebar-brand">
        <a href="{{ url('admin/dashboard') }}" class="brand-link">
            <img
                src="https://cdn-icons-png.flaticon.com/512/822/822143.png"
                alt="Pharmacy Logo"
                class="brand-image opacity-75"
                style="filter: invert(1); width: 33px;"
            />
            <span class="brand-text fw-bold">Medora Pharm</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                data-accordion="false"
            >
                <!-- Dashboard (Shared) -->
                <li class="nav-item">
                    <a href="{{ url('admin/dashboard') }}"
                       class="nav-link {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if(Auth::user()->is_role == 1)
                <!-- Staff Users (Admin Only) -->
                <li class="nav-item">
                    <a href="{{ url('admin/users') }}"
                       class="nav-link {{ Request::segment(2) == 'users' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Staff Users</p>
                    </a>
                </li>

                <!-- Suppliers (Admin Only) -->
                <li class="nav-item">
                    <a href="{{ url('admin/suppliers') }}"
                       class="nav-link {{ Request::segment(2) == 'suppliers' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-truck"></i>
                        <p>Suppliers</p>
                    </a>
                </li>
                @endif

                <!-- Customers (Shared) -->
                <li class="nav-item">
                    <a href="{{ url('admin/customers') }}"
                       class="nav-link {{ Request::segment(2) == 'customers' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-badge"></i>
                        <p>Customers</p>
                    </a>
                </li>

                <!-- Medicines (Shared) -->
                <li class="nav-item">
                    <a href="{{ url('admin/medicines') }}"
                       class="nav-link {{ Request::segment(2) == 'medicines' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-capsule"></i>
                        <p>Medicines</p>
                    </a>
                </li>

                @if(Auth::user()->is_role == 1)
                <!-- Stocks (Admin Only) -->
                <li class="nav-item">
                    <a href="{{ url('admin/stocks') }}"
                       class="nav-link {{ Request::segment(2) == 'stocks' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-boxes"></i>
                        <p>Stocks</p>
                    </a>
                </li>
                @endif

                <!-- Invoices / Sales (Shared) -->
                <li class="nav-item">
                    <a href="{{ url('admin/invoices') }}"
                       class="nav-link {{ Request::segment(2) == 'invoices' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-receipt"></i>
                        <p>Sales (Invoices)</p>
                    </a>
                </li>

                @if(Auth::user()->is_role == 1)
                <!-- Purchases (Admin Only) -->
                <li class="nav-item">
                    <a href="{{ url('admin/purchases') }}"
                       class="nav-link {{ Request::segment(2) == 'purchases' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-cart-plus-fill"></i>
                        <p>Purchases</p>
                    </a>
                </li>
                @endif

                <!-- My Account (Shared) -->
                <li class="nav-item mt-4">
                    <a href="{{ url('admin/my-account') }}"
                       class="nav-link {{ Request::segment(2) == 'my-account' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>My Account</p>
                    </a>
                </li>

                @if(Auth::user()->is_role == 1)
                <li class="nav-header">System Admin</li>

                <!-- System Settings (Admin Only) -->
                <li class="nav-item">
                    <a href="{{ url('admin/settings') }}"
                       class="nav-link {{ Request::segment(2) == 'settings' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-gear-fill"></i>
                        <p>System Settings</p>
                    </a>
                </li>
                @endif

                <!-- Logout (Shared) -->
                <li class="nav-item mt-2">
                    <a href="{{ url('logout') }}" class="nav-link text-danger">
                        <i class="nav-icon bi bi-box-arrow-right text-danger"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
