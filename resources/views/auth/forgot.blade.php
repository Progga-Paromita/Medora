<!doctype html>
<html lang="en" data-bs-theme="dark">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Forgot Password | Pharmacy Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="dark" />

    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}" />

    <style>
      body {
        background-color: #0B0F19 !important;
        color: #FFFFFF !important;
        font-family: 'Poppins', sans-serif;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
      }

      /* Animated Background Elements */
      .luxury-bg-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: -2;
        overflow: hidden;
      }
      .blob {
        position: absolute;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        filter: blur(140px);
        opacity: 0.12;
        animation: float-blob 22s infinite alternate ease-in-out;
        will-change: transform;
      }
      .blob-1 {
        background: #FACA5A;
        top: -100px;
        left: -100px;
        animation-delay: 0s;
      }
      .blob-2 {
        background: #F2B527;
        bottom: -150px;
        right: -100px;
        animation-delay: 5s;
      }
      .blob-3 {
        background: #FAC446;
        top: 40%;
        left: 50%;
        width: 400px;
        height: 400px;
        animation-delay: 10s;
      }
      .noise-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.02'/%3E%3C/svg%3E");
        pointer-events: none;
      }
      @keyframes float-blob {
        0% {
          transform: translate(0, 0) scale(1) rotate(0deg);
        }
        33% {
          transform: translate(50px, 80px) scale(1.1) rotate(120deg);
        }
        66% {
          transform: translate(-40px, -50px) scale(0.9) rotate(240deg);
        }
        100% {
          transform: translate(0, 0) scale(1) rotate(360deg);
        }
      }

      .login-box {
        width: 100%;
        max-width: 420px;
        padding: 15px;
        z-index: 10;
        animation: scaleIn 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
      }
      @keyframes scaleIn {
        from {
          opacity: 0;
          transform: scale(0.95);
        }
        to {
          opacity: 1;
          transform: scale(1);
        }
      }

      .card {
        background: rgba(22, 31, 46, 0.6) !important;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 24px !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3), inset 0 1px 1px rgba(255, 255, 255, 0.05) !important;
      }
      .form-control {
        background-color: rgba(17, 24, 39, 0.6) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        color: #FFFFFF !important;
        border-radius: 12px !important;
        padding: 12px 16px !important;
        transition: all 0.3s ease;
      }
      .form-control:focus {
        border-color: #FACA5A !important;
        box-shadow: 0 0 0 3px rgba(250, 202, 90, 0.2) !important;
        background-color: rgba(17, 24, 39, 0.8) !important;
        color: #FFFFFF !important;
      }
      .input-group-text {
        background-color: rgba(17, 24, 39, 0.6) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        color: #C9D1D9 !important;
        border-radius: 0 12px 12px 0 !important;
      }
      .btn-primary {
        background: linear-gradient(135deg, #FACA5A, #FAC446, #F2B527) !important;
        border: none !important;
        color: #0B0F19 !important;
        font-weight: 700 !important;
        border-radius: 12px !important;
        padding: 12px !important;
        box-shadow: 0 4px 15px rgba(250, 202, 90, 0.25) !important;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
      }
      .btn-primary:hover {
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 8px 25px rgba(250, 202, 90, 0.4) !important;
        filter: brightness(1.05);
      }
      .login-logo a {
        color: #FACA5A !important;
        font-weight: 800;
        letter-spacing: -0.5px;
        text-shadow: 0 0 15px rgba(250, 202, 90, 0.25);
      }
    </style>
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body>
    <!-- Live Animated Background -->
    <div class="luxury-bg-wrapper">
      <div class="blob blob-1"></div>
      <div class="blob blob-2"></div>
      <div class="blob blob-3"></div>
      <div class="noise-overlay"></div>
    </div>

    <div class="login-box">
      <div class="login-logo mb-4 text-center">
        <a href="{{ url('/') }}" class="text-decoration-none">
          <i class="bi bi-capsule-capside me-2"></i><b>Medora</b> Pharm
        </a>
      </div>

      <div class="card">
        <div class="card-body p-4">
          @include('message')

          <p class="login-box-msg mb-3 text-secondary">Forgot Password</p>
          <p class="text-sm text-muted text-center mb-4">Enter your registered email address, and we'll transmit a secure reset link.</p>

          <form action="{{ url('forgot_post') }}" method="post">
            @csrf
            <!-- Email -->
            <div class="input-group mb-4">
              <input type="email" name="email" class="form-control" placeholder="Email Address" required autofocus />
              <span class="input-group-text">
                <i class="bi bi-envelope"></i>
              </span>
            </div>

            <!-- Submit -->
            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary">Send Reset Link</button>
            </div>
          </form>

          <div class="mt-4 pt-3 border-top text-center" style="border-color: rgba(255, 255, 255, 0.08) !important;">
            <p class="mb-0 text-sm text-secondary">
              Go back to <a href="{{ url('/') }}" class="text-decoration-none fw-bold" style="color: #FACA5A;">Login</a>
            </p>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  <!--end::Body-->
</html>
