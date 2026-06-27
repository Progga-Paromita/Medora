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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
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
      /* Light Theme Overrides */
      [data-bs-theme="light"] {
        --bs-body-bg: #FFFFFF;
        --bs-body-color: #15171A;
        --bs-primary: #2F5DD7;
        --bs-primary-rgb: 47, 93, 215;
        --bs-success: #236B45;
        --bs-success-rgb: 35, 107, 69;
        --bs-danger: #C2410C;
        --bs-danger-rgb: 194, 65, 12;
        --bs-warning: #C2410C;
        --bs-warning-rgb: 194, 65, 12;
        --bs-border-color: #E4E2DC;
        --bs-card-bg: #FFFFFF;
        --bs-tertiary-bg: #F7F8FA;
        --bs-secondary-bg: #F7F8FA;
        
        .content-wrapper, .app-main {
          background-color: #F7F8FA !important;
        }
        .app-sidebar {
          background-color: #FFFFFF !important;
          border-right: 1px solid #E4E2DC !important;
        }
        .app-header {
          background-color: #FFFFFF !important;
          border-bottom: 1px solid #E4E2DC !important;
        }
        .app-sidebar .sidebar-brand {
          border-bottom: 1px solid #E4E2DC !important;
          background-color: #FFFFFF !important;
          color: #15171A !important;
        }
        .app-sidebar .sidebar-brand a {
          color: #15171A !important;
        }
        .nav-link {
          color: #15171A !important;
        }
        .nav-link:hover {
          background-color: rgba(47, 93, 215, 0.05) !important;
          color: #2F5DD7 !important;
        }
        .nav-link.active {
          background-color: rgba(47, 93, 215, 0.1) !important;
          color: #2F5DD7 !important;
          font-weight: 600;
        }
        .app-footer {
          background-color: #FFFFFF !important;
          border-top: 1px solid #E4E2DC !important;
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
      .card {
        border: 1px solid var(--bs-border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02) !important;
        border-radius: 0.5rem;
      }
      .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid var(--bs-border-color);
        font-weight: 600;
      }
      .btn-primary {
        background-color: var(--bs-primary) !important;
        border-color: var(--bs-primary) !important;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
      }
      .btn-primary:hover {
        filter: brightness(1.1);
      }
      .table-striped tbody tr:nth-of-type(odd) {
        --bs-table-accent-bg: rgba(0,0,0, 0.015) !important;
      }
      [data-bs-theme="dark"] .table-striped tbody tr:nth-of-type(odd) {
        --bs-table-accent-bg: rgba(255,255,255, 0.015) !important;
      }
      /* Ensure inputs look clean */
      .form-control, .form-select {
        border-color: var(--bs-border-color);
        background-color: var(--bs-card-bg);
        color: var(--bs-body-color);
      }
      .form-control:focus, .form-select:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.15);
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
