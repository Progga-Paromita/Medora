<aside class="app-sidebar shadow">
    <div class="sidebar-brand">
        <a href="{{ url('admin/dashboard') }}" class="brand-link">
            <img
                src="https://cdn-icons-png.flaticon.com/512/822/822143.png"
                alt="Pharmacy Logo"
                class="brand-image opacity-75"
                style="filter: invert(1); width: 33px;"
            />
            <span class="brand-text fw-bold">{{ \App\Models\SettingsModel::getValue('pharmacy_name', 'Medora Pharmacy') }}</span>
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
                        <p>User Management</p>
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

                <!-- Inventory Control (Shared) -->
                <li class="nav-item {{ Request::segment(2) == 'inventory' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::segment(2) == 'inventory' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-shield-check"></i>
                        <p>
                            Inventory Control
                            <i class="end bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/inventory/dashboard') }}" class="nav-link {{ Request::segment(3) == 'dashboard' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/inventory/stock') }}" class="nav-link {{ Request::segment(3) == 'stock' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-list-check"></i>
                                <p>Stock Registry</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/inventory/low-stock') }}" class="nav-link {{ Request::segment(3) == 'low-stock' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-exclamation-triangle text-warning"></i>
                                <p>Low Stock</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/inventory/expired') }}" class="nav-link {{ Request::segment(3) == 'expired' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-x-circle text-danger"></i>
                                <p>Expired Stock</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/inventory/near-expiry') }}" class="nav-link {{ Request::segment(3) == 'near-expiry' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-clock text-warning"></i>
                                <p>Near Expiry</p>
                            </a>
                        </li>
                        @if(Auth::user()->is_role == 1)
                        <li class="nav-item">
                            <a href="{{ url('admin/inventory/adjust') }}" class="nav-link {{ Request::segment(3) == 'adjust' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-sliders"></i>
                                <p>Stock Adjust</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ url('admin/inventory/history') }}" class="nav-link {{ Request::segment(3) == 'history' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-journal-text"></i>
                                <p>Stock History</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @if(Auth::user()->is_role == 1)
                <!-- Reports & Analytics (Admin Only) -->
                <li class="nav-item {{ Request::segment(2) == 'reports' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::segment(2) == 'reports' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-file-earmark-bar-graph"></i>
                        <p>
                            Reports & BI
                            <i class="end bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/reports/dashboard') }}" class="nav-link {{ Request::segment(3) == 'dashboard' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/reports/sales') }}" class="nav-link {{ Request::segment(3) == 'sales' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-receipt"></i>
                                <p>Sales Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/reports/purchases') }}" class="nav-link {{ Request::segment(3) == 'purchases' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-cart"></i>
                                <p>Purchases Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/reports/inventory') }}" class="nav-link {{ Request::segment(3) == 'inventory' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-boxes"></i>
                                <p>Inventory Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/reports/customers') }}" class="nav-link {{ Request::segment(3) == 'customers' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Customer Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/reports/suppliers') }}" class="nav-link {{ Request::segment(3) == 'suppliers' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-building"></i>
                                <p>Supplier Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/reports/medicines') }}" class="nav-link {{ Request::segment(3) == 'medicines' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-capsule"></i>
                                <p>Medicine Performance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/reports/profit') }}" class="nav-link {{ Request::segment(3) == 'profit' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-currency-dollar"></i>
                                <p>Profit Analysis</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/reports/financial') }}" class="nav-link {{ Request::segment(3) == 'financial' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-bank"></i>
                                <p>Financial Summary</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Notification Center (Shared) -->
                <li class="nav-item">
                    <a href="{{ url('admin/inventory/notifications') }}"
                       class="nav-link {{ Request::segment(3) == 'notifications' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bell"></i>
                        <p>Notification Center</p>
                    </a>
                </li>

                <!-- Help & Support (Shared) -->
                <li class="nav-item">
                    <a href="{{ url('admin/inventory/help') }}"
                       class="nav-link {{ Request::segment(3) == 'help' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-question-circle"></i>
                        <p>Help & Support</p>
                    </a>
                </li>

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

                <!-- Activity Logs (Admin Only) -->
                <li class="nav-item">
                    <a href="{{ url('admin/activity-logs') }}"
                       class="nav-link {{ Request::segment(2) == 'activity-logs' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-journal-text"></i>
                        <p>Activity Logs</p>
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
