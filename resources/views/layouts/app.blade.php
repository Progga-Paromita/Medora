<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ !empty($header_title) ? $header_title : '' }} | Pharmacy Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- Fast Synchronous Theme Bootstrap (prevents white/black flashes on load) -->
    <script>
      (function() {
        const storedTheme = '{{ \App\Models\SettingsModel::getValue('theme', 'dark') }}';
        document.documentElement.setAttribute('data-bs-theme', storedTheme);
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
        background-color: var(--bs-body-bg) !important;
        color: var(--bs-body-color) !important;
      }

      /* ========================================================
       * 1. PREMIUM LIGHT THEME (As implemented previously)
       * ======================================================== */
      [data-bs-theme="light"] {
        --bs-body-bg: #FFFDF7;
        --bs-body-color: #15171A;
        --bs-primary: #FACA5A;
        --bs-primary-rgb: 250, 202, 90;
        --bs-secondary: #FAC446;
        --bs-accent: #F2B527;
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
        --text-secondary: #5C6370;
        --text-muted: #94A3B8;
        --glow: rgba(250, 202, 90, 0.15);
      }

      [data-bs-theme="light"] .app-sidebar {
        background-color: #FFFFFF !important;
        border-right: 1px solid #E5E7EB !important;
      }
      [data-bs-theme="light"] .app-sidebar .sidebar-brand {
        border-bottom: 1px solid #E5E7EB !important;
        background-color: #FFFFFF !important;
        color: #15171A !important;
      }
      [data-bs-theme="light"] .app-sidebar .sidebar-brand a {
        color: #15171A !important;
      }
      [data-bs-theme="light"] .app-sidebar .sidebar-brand img {
        filter: none !important;
      }
      [data-bs-theme="light"] .nav-link {
        color: #15171A !important;
      }
      [data-bs-theme="light"] .nav-link:hover {
        background-color: #FFF8E8 !important;
        color: #FACA5A !important;
      }
      [data-bs-theme="light"] .nav-link.active {
        background: linear-gradient(135deg, #FACA5A, #FAC446, #F2B527) !important;
        color: #0B0F19 !important;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(250, 202, 90, 0.25) !important;
        border-left: 3px solid #0B0F19;
      }
      [data-bs-theme="light"] .nav-link.active i {
        color: #0B0F19 !important;
      }
      [data-bs-theme="light"] .app-header {
        background-color: #FFFFFF !important;
        border-bottom: 1px solid #E5E7EB !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03) !important;
      }
      [data-bs-theme="light"] .app-footer {
        background-color: #FFF8E8 !important;
        border-top: 1px solid #E5E7EB !important;
        color: #15171A !important;
      }
      [data-bs-theme="light"] .luxury-bg-wrapper {
        display: none !important; /* Hide blobs in Light Mode */
      }

      /* ========================================================
       * 2. PREMIUM FUTURISTIC DARK THEME (Redesigned)
       * ======================================================== */
      [data-bs-theme="dark"] {
        --bs-body-bg: #0B0F19;
        --bs-body-color: #FFFFFF;
        --bs-primary: #FACA5A;
        --bs-primary-rgb: 250, 202, 90;
        --bs-secondary: #FAC446;
        --bs-accent: #F2B527;
        --bs-success: #22C55E;
        --bs-success-rgb: 34, 197, 94;
        --bs-danger: #EF4444;
        --bs-danger-rgb: 239, 68, 68;
        --bs-warning: #FAC446;
        --bs-warning-rgb: 250, 196, 70;
        --bs-border-color: rgba(255, 255, 255, 0.08);
        --bs-card-bg: #161F2E;
        --bs-tertiary-bg: #111827;
        --bs-secondary-bg: #111827;
        --text-secondary: #C9D1D9;
        --text-muted: #94A3B8;
        --glow: rgba(250, 202, 90, 0.25);
      }

      [data-bs-theme="dark"] .app-sidebar {
        background-color: #0F172A !important;
        border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(12px);
      }
      [data-bs-theme="dark"] .app-sidebar .sidebar-brand {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        background-color: #0F172A !important;
        color: #FFFFFF !important;
      }
      [data-bs-theme="dark"] .app-sidebar .sidebar-brand a {
        color: #FFFFFF !important;
      }
      [data-bs-theme="dark"] .app-sidebar .sidebar-brand img {
        filter: invert(1) !important;
      }
      [data-bs-theme="dark"] .nav-link {
        color: #C9D1D9 !important;
      }
      [data-bs-theme="dark"] .nav-link:hover {
        background-color: rgba(250, 202, 90, 0.05) !important;
        color: #FACA5A !important;
      }
      [data-bs-theme="dark"] .nav-link.active {
        background: linear-gradient(135deg, #FACA5A, #FAC446, #F2B527) !important;
        color: #0B0F19 !important;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(250, 202, 90, 0.35) !important;
        border-left: 3px solid #FFFFFF;
      }
      [data-bs-theme="dark"] .nav-link.active i {
        color: #0B0F19 !important;
      }
      [data-bs-theme="dark"] .app-header {
        background-color: rgba(17, 24, 39, 0.85) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(12px);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1) !important;
      }
      [data-bs-theme="dark"] .app-footer {
        background-color: #111827 !important;
        border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #C9D1D9 !important;
      }
      [data-bs-theme="dark"] .luxury-bg-wrapper {
        display: block !important; /* Show blobs in Dark Mode */
      }

      /* Global Layout Integrations */
      .content-wrapper, .app-main {
        background-color: var(--bs-secondary-bg) !important;
      }

      /* Animated Background Elements (Dark Only) */
      .luxury-bg-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: -2;
        background: #0B0F19;
        overflow: hidden;
      }
      .blob {
        position: absolute;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        filter: blur(150px);
        opacity: 0.12;
        animation: float-blob 28s infinite alternate ease-in-out;
        will-change: transform;
      }
      .blob-1 {
        background: #FACA5A;
        top: -150px;
        left: -150px;
        animation-delay: 0s;
      }
      .blob-2 {
        background: #F2B527;
        bottom: -200px;
        right: -150px;
        animation-delay: 6s;
      }
      .blob-3 {
        background: #FAC446;
        top: 35%;
        left: 45%;
        width: 450px;
        height: 450px;
        animation-delay: 12s;
      }
      .noise-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.015'/%3E%3C/svg%3E");
        pointer-events: none;
      }
      @keyframes float-blob {
        0% {
          transform: translate(0, 0) scale(1) rotate(0deg);
        }
        33% {
          transform: translate(60px, 90px) scale(1.1) rotate(120deg);
        }
        66% {
          transform: translate(-50px, -60px) scale(0.95) rotate(240deg);
        }
        100% {
          transform: translate(0, 0) scale(1) rotate(360deg);
        }
      }

      /* Animated Sidebar Icon Scale */
      .app-sidebar .nav-link i {
        transition: transform 0.3s ease;
      }
      .app-sidebar .nav-link:hover i {
        transform: scale(1.2) rotate(5deg);
        color: #FACA5A !important;
      }

      /* Premium Card Overrides */
      .card {
        border: 1px solid var(--bs-border-color) !important;
        background: var(--bs-card-bg) !important;
        border-radius: 20px !important;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03), inset 0 1px 1px rgba(255, 255, 255, 0.02) !important;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
      }
      .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
          90deg,
          transparent,
          rgba(250, 202, 90, 0.06),
          transparent
        );
        transition: 0.5s;
        pointer-events: none;
      }
      [data-bs-theme="light"] .card:hover {
        transform: translateY(-5px) scale(1.02);
        border-color: rgba(250, 202, 90, 0.4) !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08) !important;
      }
      [data-bs-theme="dark"] .card:hover {
        transform: translateY(-5px) scale(1.02);
        border-color: rgba(250, 202, 90, 0.3) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), 0 0 20px rgba(250, 202, 90, 0.12), inset 0 1px 1px rgba(255, 255, 255, 0.08) !important;
      }
      .card:hover::before {
        left: 100%;
        transition: 0.8s ease-in-out;
      }
      .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid var(--bs-border-color);
        font-weight: 600;
        color: var(--bs-body-color);
      }

      /* Premium Buttons */
      .btn {
        border-radius: 12px !important;
        padding: 10px 22px;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        font-weight: 500;
      }
      .btn-primary {
        background: linear-gradient(135deg, #FACA5A, #FAC446, #F2B527) !important;
        border: none !important;
        color: #0B0F19 !important;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(250, 202, 90, 0.25) !important;
      }
      .btn-primary:hover {
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 8px 25px rgba(250, 202, 90, 0.45) !important;
        filter: brightness(1.05);
      }
      .btn-secondary {
        background-color: transparent !important;
        border: 2px solid var(--bs-primary) !important;
        color: var(--bs-primary) !important;
        font-weight: 600;
      }
      .btn-secondary:hover {
        background: linear-gradient(135deg, #FACA5A, #FAC446, #F2B527) !important;
        color: #0B0F19 !important;
        border-color: transparent !important;
        box-shadow: 0 4px 15px rgba(250, 202, 90, 0.25) !important;
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

      /* Data Tables */
      .table-responsive {
        background: var(--bs-card-bg) !important;
        border: 1px solid var(--bs-border-color);
        border-radius: 18px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.02) !important;
      }
      .table {
        margin-bottom: 0 !important;
      }
      [data-bs-theme="light"] .table th {
        background-color: #FFF8E8 !important;
        color: #15171A !important;
        border-bottom: 2px solid #E5E7EB !important;
        font-weight: 600;
      }
      [data-bs-theme="dark"] .table th {
        background-color: rgba(17, 24, 39, 0.9) !important;
        color: #FFFFFF !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.08) !important;
        font-weight: 600;
      }
      .table td {
        border-bottom: 1px solid var(--bs-border-color) !important;
        color: var(--bs-body-color) !important;
        opacity: 0.85;
        vertical-align: middle;
      }
      .table tbody tr {
        transition: background-color 0.2s ease;
      }
      .table tbody tr:hover {
        background-color: rgba(250, 202, 90, 0.02) !important;
      }

      /* Form inputs */
      .form-control:not(.form-control-sm), .form-select:not(.form-select-sm) {
        height: 48px;
      }
      .form-control, .form-select {
        border-color: var(--bs-border-color) !important;
        background-color: var(--bs-card-bg) !important;
        color: var(--bs-body-color) !important;
        border-radius: 12px !important;
        backdrop-filter: blur(5px);
        transition: all 0.3s ease;
      }
      .form-control:focus, .form-select:focus {
        border-color: #FACA5A !important;
        box-shadow: 0 0 0 3px rgba(250, 202, 90, 0.25) !important;
        color: var(--bs-body-color) !important;
      }
      .form-group {
        margin-bottom: 1.25rem !important;
      }
      .form-group label {
        margin-bottom: 0.5rem !important;
        font-weight: 500 !important;
        color: var(--bs-body-color) !important;
        font-size: 14px !important;
        display: inline-block !important;
      }

      /* Pagination */
      .page-item.active .page-link {
        background-color: var(--bs-primary) !important;
        border-color: var(--bs-primary) !important;
        color: #0B0F19 !important;
        font-weight: 600;
      }
      .page-link {
        background-color: var(--bs-card-bg) !important;
        border-color: var(--bs-border-color) !important;
        color: var(--bs-primary);
      }
      .page-link:hover {
        color: var(--bs-primary);
        filter: brightness(0.9);
      }

      /* Header hover buttons */
      .hover-bg-light {
        transition: all 0.2s ease-in-out;
      }
      .hover-bg-light:hover {
        background-color: rgba(255, 255, 255, 0.06) !important;
      }
    </style>
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg">
    <!-- Live Animated Background -->
    <div class="luxury-bg-wrapper">
      <div class="blob blob-1"></div>
      <div class="blob blob-2"></div>
      <div class="blob blob-3"></div>
      <div class="noise-overlay"></div>
    </div>

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
