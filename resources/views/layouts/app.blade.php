<!doctype html>
<html lang="en" data-bs-theme="light">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ !empty($header_title) ? $header_title : '' }} | Pharmacy Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="supported-color-schemes" content="light dark" />

    <!-- Theme Initialization to prevent flicker -->
    <script>
      (function() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        let activeTheme = savedTheme;
        if (savedTheme === 'auto') {
          activeTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        document.documentElement.setAttribute('data-bs-theme', activeTheme);
      })();
    </script>

    <!--begin::Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!--end::Fonts-->

    <!--begin::Third Party Plugins-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <!--end::Third Party Plugins-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ url('vendor/adminlte/dist/css/adminlte.css') }}">
    <!--end::Required Plugin(AdminLTE)-->

    <!-- Custom Theme Styling -->
    <style>
      body {
        font-family: 'Poppins', sans-serif !important;
      }

      /* Light Theme Overrides */
      [data-bs-theme="light"] {
        --bs-body-bg: #FFFDF7;
        --bs-body-color: #15171A;
        --bs-primary: #F2B527;
        --bs-primary-rgb: 242, 181, 39;
        --bs-success: #22C55E;
        --bs-success-rgb: 34, 197, 94;
        --bs-danger: #EF4444;
        --bs-danger-rgb: 239, 68, 68;
        --bs-warning: #FAC446;
        --bs-warning-rgb: 250, 196, 70;
        --bs-border-color: #E5E7EB;
        --bs-card-bg: #FFFFFF;
        --bs-tertiary-bg: #FFF8E8;
        --bs-secondary-bg: #FFF8E8;
        
        .content-wrapper, .app-main {
          background-color: #FFF8E8 !important;
        }
        .app-sidebar {
          background-color: #FFFFFF !important;
          border-right: 1px solid #E5E7EB !important;
        }
        .app-header {
          background-color: #FFFFFF !important;
          border-bottom: 1px solid #E5E7EB !important;
          box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03) !important;
        }
        .app-sidebar .sidebar-brand {
          border-bottom: 1px solid #E5E7EB !important;
          background-color: #FFFFFF !important;
          color: #15171A !important;
        }
        .app-sidebar .sidebar-brand a {
          color: #15171A !important;
        }
        .app-sidebar .sidebar-brand img {
          filter: none !important;
        }
        .nav-link {
          color: #15171A !important;
          border-radius: 8px;
          margin: 2px 10px;
          transition: all 0.3s ease;
        }
        .nav-link:hover {
          background-color: #FFF8E8 !important;
          color: #F2B527 !important;
        }
        .nav-link.active {
          background-color: #F2B527 !important;
          color: #FFFFFF !important;
          font-weight: 600;
          box-shadow: 0 4px 10px rgba(242, 181, 39, 0.3) !important;
        }
        .app-footer {
          background-color: #FFFFFF !important;
          border-top: 1px solid #E5E7EB !important;
          color: #15171A !important;
        }
      }

      /* Dark Theme Overrides */
      [data-bs-theme="dark"] {
        --bs-body-bg: #0B0D12;
        --bs-body-color: #F2F3F5;
        --bs-primary: #6E8DF0;
        --bs-primary-rgb: 110, 141, 240;
        --bs-success: #5FC98C;
        --bs-success-rgb: 95, 201, 140;
        --bs-danger: #E8A06E;
        --bs-danger-rgb: 232, 160, 110;
        --bs-warning: #E8A06E;
        --bs-warning-rgb: 232, 160, 110;
        --bs-border-color: #2A2D33;
        --bs-card-bg: #1A1D24;
        --bs-tertiary-bg: #1A1D24;
        --bs-secondary-bg: #1A1D24;

        .content-wrapper, .app-main {
          background-color: #0B0D12 !important;
        }
        .app-sidebar {
          background-color: #1A1D24 !important;
          border-right: 1px solid #2A2D33 !important;
        }
        .app-header {
          background-color: #1A1D24 !important;
          border-bottom: 1px solid #2A2D33 !important;
        }
        .app-sidebar .sidebar-brand {
          border-bottom: 1px solid #2A2D33 !important;
          background-color: #1A1D24 !important;
          color: #F2F3F5 !important;
        }
        .app-sidebar .sidebar-brand a {
          color: #F2F3F5 !important;
        }
        .app-sidebar .sidebar-brand img {
          filter: invert(1) !important;
        }
        .nav-link {
          color: #F2F3F5 !important;
        }
        .nav-link:hover {
          background-color: rgba(110, 141, 240, 0.05) !important;
          color: #6E8DF0 !important;
        }
        .nav-link.active {
          background-color: rgba(110, 141, 240, 0.1) !important;
          color: #6E8DF0 !important;
          font-weight: 600;
        }
        .app-footer {
          background-color: #1A1D24 !important;
          border-top: 1px solid #2A2D33 !important;
          color: #F2F3F5 !important;
        }
      }

      /* Premium Universal UI Styles */
      .hover-bg-light {
        transition: all 0.2s ease-in-out;
      }
      .hover-bg-light:hover {
        background-color: var(--bs-tertiary-bg) !important;
      }
      .card {
        border: 1px solid var(--bs-border-color) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02) !important;
        border-radius: 15px !important;
        background-color: var(--bs-card-bg) !important;
        transition: all 0.3s ease;
      }
      .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03) !important;
      }
      .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid var(--bs-border-color);
        font-weight: 600;
      }
      .btn {
        border-radius: 10px !important;
        padding: 10px 20px;
        transition: all 0.3s ease;
      }
      .btn-primary {
        background-color: var(--bs-primary) !important;
        border-color: var(--bs-primary) !important;
        color: #FFFFFF !important;
        font-weight: 600;
        box-shadow: 0 4px 6px rgba(var(--bs-primary-rgb), 0.2) !important;
      }
      .btn-primary:hover {
        filter: brightness(1.05);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(var(--bs-primary-rgb), 0.3) !important;
      }
      .btn-secondary {
        background-color: #FFFFFF !important;
        border: 2px solid var(--bs-primary) !important;
        color: var(--bs-primary) !important;
        font-weight: 600;
      }
      .btn-secondary:hover {
        background-color: var(--bs-tertiary-bg) !important;
        color: var(--bs-primary) !important;
        border-color: var(--bs-primary) !important;
      }
      .btn-danger {
        background-color: var(--bs-danger) !important;
        border-color: var(--bs-danger) !important;
        color: #FFFFFF !important;
      }
      .btn-success {
        background-color: var(--bs-success) !important;
        border-color: var(--bs-success) !important;
        color: #FFFFFF !important;
      }
      .table-striped tbody tr:nth-of-type(odd) {
        --bs-table-accent-bg: rgba(0,0,0, 0.01) !important;
      }
      [data-bs-theme="dark"] .table-striped tbody tr:nth-of-type(odd) {
        --bs-table-accent-bg: rgba(255,255,255, 0.01) !important;
      }
      .table-responsive {
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid var(--bs-border-color);
      }
      .table {
        margin-bottom: 0 !important;
      }
      .table th {
        background-color: var(--bs-tertiary-bg) !important;
        color: var(--bs-body-color) !important;
        font-weight: 600;
        border-bottom: 2px solid var(--bs-border-color) !important;
      }
      .page-item.active .page-link {
        background-color: var(--bs-primary) !important;
        border-color: var(--bs-primary) !important;
        color: #FFFFFF !important;
      }
      .page-link {
        color: var(--bs-primary);
      }
      .page-link:hover {
        color: var(--bs-primary);
        filter: brightness(0.9);
      }
      .form-control:not(.form-control-sm), .form-select:not(.form-select-sm) {
        height: 48px;
      }
      .form-control, .form-select {
        border-color: var(--bs-border-color);
        background-color: var(--bs-card-bg);
        color: var(--bs-body-color);
        border-radius: 10px !important;
        transition: all 0.3s ease;
      }
      .form-control:focus, .form-select:focus {
        border-color: var(--bs-primary) !important;
        box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.15) !important;
        background-color: var(--bs-card-bg);
        color: var(--bs-body-color);
      }
    </style>
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      @include('layouts._header')
      <!--end::Header-->

      <!--begin::Sidebar-->
      @include('layouts._sidebar')
      <!--end::Sidebar-->

      <!--begin::App Main-->
      @yield('content')
      <!--end::App Main-->

      <!--begin::Footer-->
      @include('layouts._footer')
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>

    @yield('scripts')
  </body>
  <!--end::Body-->
</html>
