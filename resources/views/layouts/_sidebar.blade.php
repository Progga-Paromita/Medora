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
        <p>Users</p>
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