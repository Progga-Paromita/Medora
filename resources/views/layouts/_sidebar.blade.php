<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    <div class="sidebar-brand">
        <a href="{{ url('admin/dashboard') }}" class="brand-link">
            <img
                src="{{ asset('assets/img/AdminLTELogo.png') }}"
                alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow"
            />
            <span class="brand-text fw-light">Pharmacy</span>
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

            <li class="nav-item">
    <a href="{{ url('admin/dashboard') }}"
       class="nav-link {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}">
        <i class="nav-icon bi bi-speedometer"></i>
        <p>Dashboard</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ url('admin/users') }}"
       class="nav-link {{ Request::segment(2) == 'users' ? 'active' : '' }}">
        <i class="nav-icon bi bi-people"></i>
        <p>Staff Users</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ url('admin/suppliers') }}"
       class="nav-link {{ Request::segment(2) == 'suppliers' ? 'active' : '' }}">

        <i class="nav-icon bi bi-people"></i>
        <p>Suppliers</p>

    </a>
</li>

<li class="nav-item">
    <a href="{{ url('admin/customers') }}"
       class="nav-link {{ Request::segment(2) == 'customers' ? 'active' : '' }}">
        <i class="nav-icon bi bi-people"></i>
        <p>Customers</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ url('admin/medicines') }}"
       class="nav-link {{ Request::segment(2) == 'medicines' ? 'active' : '' }}">
        <i class="nav-icon bi bi-capsule"></i>
        <p>Medicines</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ url('admin/stocks') }}"
       class="nav-link {{ Request::segment(2) == 'stocks' ? 'active' : '' }}">

        <i class="nav-icon bi bi-boxes"></i>
        <p>Stocks</p>

    </a>
</li>
<li class="nav-item">
    <a href="{{ url('admin/invoices') }}" class="nav-link {{ Request::segment(2) == 'invoices' ? 'active' : '' }}">
        <i class="nav-icon bi bi-receipt"></i>
        <p>Invoices</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ url('admin/purchases') }}" class="nav-link {{ Request::segment(2) == 'purchases' ? 'active' : '' }}">
        <i class="nav-icon bi bi-cart4"></i>
        <p>Purchases</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ url('logout') }}" class="nav-link">
        <i class="nav-icon bi bi-box-arrow-right"></i>
        <p>Logout</p>
    </a>
</li>



            </ul>
        </nav>
    </div>

</aside>
