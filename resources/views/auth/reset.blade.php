<!doctype html>
<html lang="en" data-bs-theme="light">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reset Password | Pharmacy Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light dark" />

    <!-- Theme Initialization -->
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

    <!-- Fonts and Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}" />

    <style>
      /* Theme styles matching user specifications */
      [data-bs-theme="light"] {
        --bg-color: #F7F8FA;
        --card-bg: #FFFFFF;
        --text-color: #15171A;
        --border-color: #E4E2DC;
        --primary-color: #2F5DD7;
        --primary-hover: #1E4BB8;
        --input-bg: #FFFFFF;
        --logo-color: #2F5DD7;
      }
      [data-bs-theme="dark"] {
        --bg-color: #0B0D12;
        --card-bg: #1A1D24;
        --text-color: #F2F3F5;
        --border-color: #2A2D33;
        --primary-color: #6E8DF0;
        --primary-hover: #5A7AE0;
        --input-bg: #1A1D24;
        --logo-color: #6E8DF0;
      }
      body {
        background-color: var(--bg-color) !important;
        color: var(--text-color) !important;
        font-family: 'Source Sans 3', sans-serif;
        transition: background-color 0.3s ease, color 0.3s ease;
      }
      .login-box {
        width: 100%;
        max-width: 420px;
        padding: 15px;
      }
      .card {
        background-color: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 16px -6px rgba(0, 0, 0, 0.02) !important;
      }
      .form-control {
        background-color: var(--input-bg) !important;
        border-color: var(--border-color) !important;
        color: var(--text-color) !important;
        border-radius: 8px !important;
        padding: 10px 14px !important;
      }
      .form-control:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(47, 93, 215, 0.15) !important;
        background-color: var(--input-bg) !important;
        color: var(--text-color) !important;
      }
      .input-group-text {
        background-color: var(--card-bg) !important;
        border-color: var(--border-color) !important;
        color: var(--text-color) !important;
        border-radius: 0 8px 8px 0 !important;
      }
      .btn-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        border-radius: 8px !important;
        padding: 10px !important;
        font-weight: 600 !important;
        transition: all 0.2s ease;
      }
      .btn-primary:hover {
        background-color: var(--primary-hover) !important;
        border-color: var(--primary-hover) !important;
      }
      .login-logo a {
        color: var(--logo-color) !important;
        font-weight: 800;
        letter-spacing: -0.5px;
      }
      .theme-btn-float {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-color);
        box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        cursor: pointer;
        transition: all 0.2s ease;
      }
      .theme-btn-float:hover {
        transform: scale(1.05);
      }
    </style>
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="login-page">
    <!-- Floating Theme Toggle Button -->
    <button class="theme-btn-float" id="themeToggleBtn" aria-label="Toggle Theme">
      <i class="bi bi-sun-fill" id="themeIcon"></i>
    </button>

    <div class="login-box">
      <div class="login-logo mb-4 text-center">
        <a href="{{ url('/') }}" class="text-decoration-none">
          <i class="bi bi-capsule-capside me-2"></i><b>Medora</b> Pharm
        </a>
      </div>

      <div class="card">
        <div class="card-body p-4">
          @include('message')

          <p class="login-box-msg mb-4 text-secondary">Reset Password</p>
          <p class="text-sm text-muted text-center mb-4">Enter your new secure password below.</p>

          <form action="{{ url('reset/' . $token) }}" method="post">
            @csrf
            <!-- Password -->
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="New Password" required autofocus />
              <span class="input-group-text">
                <i class="bi bi-lock-fill"></i>
              </span>
              @error('password')
                <div class="invalid-feedback w-100">{{ $message }}</div>
              @enderror
            </div>

            <!-- Confirm Password -->
            <div class="input-group mb-4">
              <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password" required />
              <span class="input-group-text">
                <i class="bi bi-lock-fill"></i>
              </span>
            </div>

            <!-- Submit -->
            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Theme Switcher Script -->
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const html = document.documentElement;
        const toggleBtn = document.getElementById('themeToggleBtn');
        const themeIcon = document.getElementById('themeIcon');
        
        const updateIcon = (theme) => {
          if (theme === 'dark') {
            themeIcon.className = 'bi bi-moon-fill';
          } else {
            themeIcon.className = 'bi bi-sun-fill';
          }
        }

        const savedTheme = localStorage.getItem('theme') || 'light';
        let activeTheme = savedTheme;
        if (savedTheme === 'auto') {
          activeTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        html.setAttribute('data-bs-theme', activeTheme);
        updateIcon(activeTheme);

        toggleBtn.addEventListener('click', () => {
          const currentTheme = html.getAttribute('data-bs-theme');
          const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
          html.setAttribute('data-bs-theme', newTheme);
          localStorage.setItem('theme', newTheme);
          updateIcon(newTheme);
        });
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  <!--end::Body-->
</html>
