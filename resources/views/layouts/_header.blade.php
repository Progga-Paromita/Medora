<nav class="app-header navbar navbar-expand bg-body align-items-center py-2 px-3">
    <!--begin::Container-->
    <div class="container-fluid d-flex justify-content-between align-items-center">
        
        <!-- Left Section: Toggle & Search -->
        <div class="d-flex align-items-center">
            <li class="nav-item list-unstyled">
                <a class="nav-link btn btn-link p-2 rounded-circle hover-bg-light me-2" data-lte-toggle="sidebar" href="#" role="button" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: var(--bs-body-color);">
                    <i class="bi bi-list fs-5"></i>
                </a>
            </li>

            <!-- Pill-shaped rounded search box -->
            <div class="d-none d-md-flex align-items-center px-3 rounded-pill" style="height: 40px; width: 300px; transition: all 0.3s ease; background: rgba(255, 255, 255, 0.04) !important; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
                <i class="bi bi-search text-muted me-2"></i>
                <input type="text" class="border-0 bg-transparent text-sm w-100" placeholder="Search medicines, orders..." style="outline: none; font-size: 14px; color: #FFFFFF;">
            </div>
        </div>

        <!-- Right Section: Theme, Notifications, Profile -->
        <ul class="navbar-nav align-items-center ms-auto">
            <!-- Theme Toggle Button -->
            <li class="nav-item dropdown me-2">
                <a
                    class="nav-link btn btn-link p-2 rounded-circle hover-bg-light"
                    href="#"
                    id="bd-theme"
                    aria-label="Toggle color scheme"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: var(--bs-body-color);"
                >
                    <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
                    <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
                    <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
                </a>
                <ul
                    class="dropdown-menu dropdown-menu-end border-0 shadow mt-2 rounded-4"
                    aria-labelledby="bd-theme"
                    style="--bs-dropdown-min-width: 8rem; background: var(--bs-card-bg); border: 1px solid var(--bs-border-color) !important;"
                >
                    <li>
                        <button
                            type="button"
                            class="dropdown-item d-flex align-items-center text-sm"
                            data-bs-theme-value="light"
                            aria-pressed="false"
                        >
                            <i class="bi bi-sun-fill me-2"></i>
                            Light
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="dropdown-item d-flex align-items-center text-sm"
                            data-bs-theme-value="dark"
                            aria-pressed="false"
                        >
                            <i class="bi bi-moon-fill me-2"></i>
                            Dark
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="dropdown-item d-flex align-items-center text-sm"
                            data-bs-theme-value="auto"
                            aria-pressed="false"
                        >
                            <i class="bi bi-circle-half me-2"></i>
                            Auto
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                </ul>
            </li>



            <!-- User Menu Dropdown -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <img
                        src="{{ Auth::user()->getProfileImage() }}"
                        class="user-image rounded-circle shadow-sm"
                        alt="User Image"
                        style="width: 32px; height: 32px; object-fit: cover; border: 1px solid rgba(255, 255, 255, 0.15);"
                    />
                    <span class="d-none d-md-inline ms-2 fw-semibold text-sm" style="color: var(--bs-body-color);">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end border-0 shadow mt-2 rounded-4" style="background: var(--bs-card-bg); border: 1px solid var(--bs-border-color) !important;">
                    <!--User Image-->
                    <li class="user-header text-center py-4 rounded-top-4" style="background: linear-gradient(135deg, rgba(22, 31, 46, 0.95), rgba(17, 24, 39, 0.95)); border-bottom: 1px solid var(--bs-border-color);">
                        <img
                            src="{{ Auth::user()->getProfileImage() }}"
                            class="rounded-circle shadow mb-2"
                            alt="User Image"
                            style="width: 80px; height: 80px; object-fit: cover; border: 2px solid var(--bs-primary);"
                        />
                        <p class="mb-0 text-white">
                            {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                            <small class="d-block text-white-50">{{ Auth::user()->is_role == 1 ? 'Administrator' : 'Pharmacy Staff' }}</small>
                        </p>
                    </li>
                    <!--Menu Footer-->
                    <li class="user-footer d-flex justify-content-between p-3 rounded-bottom-4" style="background: var(--bs-secondary-bg);">
                        <a href="{{ url('admin/my-account') }}" class="btn btn-outline-secondary btn-sm rounded-3 text-white border-secondary">
                            <i class="bi bi-person me-1"></i> Profile
                        </a>
                        <a href="{{ url('logout') }}" class="btn btn-outline-danger btn-sm rounded-3">
                            <i class="bi bi-box-arrow-right me-1"></i> Sign out
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <!--end::Right Section-->
    </div>
    <!--end::Container-->
</nav>

<!-- Theme Switcher Logic -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const getStoredTheme = () => localStorage.getItem('theme') || 'light';
        const setStoredTheme = theme => localStorage.setItem('theme', theme);

        const getPreferredTheme = () => {
            const storedTheme = getStoredTheme();
            if (storedTheme) {
                return storedTheme;
            }
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        const setTheme = theme => {
            let activeTheme = theme;
            if (theme === 'auto') {
                activeTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-bs-theme', activeTheme);
            
            // Update icons inside header color switch button
            const lightIcon = document.querySelector('#bd-theme [data-lte-theme-icon="light"]');
            const darkIcon = document.querySelector('#bd-theme [data-lte-theme-icon="dark"]');
            const autoIcon = document.querySelector('#bd-theme [data-lte-theme-icon="auto"]');
            
            if (lightIcon && darkIcon && autoIcon) {
                if (theme === 'light') {
                    lightIcon.classList.remove('d-none');
                    darkIcon.classList.add('d-none');
                    autoIcon.classList.add('d-none');
                } else if (theme === 'dark') {
                    lightIcon.classList.add('d-none');
                    darkIcon.classList.remove('d-none');
                    autoIcon.classList.add('d-none');
                } else {
                    lightIcon.classList.add('d-none');
                    darkIcon.classList.add('d-none');
                    autoIcon.classList.remove('d-none');
                }
            }
        }

        const showActiveTheme = (theme) => {
            const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);
            
            document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                element.classList.remove('active');
                element.setAttribute('aria-pressed', 'false');
                const checkIcon = element.querySelector('.bi-check-lg');
                if (checkIcon) checkIcon.classList.add('d-none');
            });

            if (btnToActive) {
                btnToActive.classList.add('active');
                btnToActive.setAttribute('aria-pressed', 'true');
                const checkIcon = btnToActive.querySelector('.bi-check-lg');
                if (checkIcon) checkIcon.classList.remove('d-none');
            }
        }

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const storedTheme = getStoredTheme();
            if (storedTheme !== 'light' && storedTheme !== 'dark') {
                setTheme(getPreferredTheme());
            }
        });

        // Initialize theme and switcher state
        const currentTheme = getStoredTheme();
        setTheme(currentTheme);
        showActiveTheme(currentTheme);

        document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
            toggle.addEventListener('click', () => {
                const theme = toggle.getAttribute('data-bs-theme-value');
                setStoredTheme(theme);
                setTheme(theme);
                showActiveTheme(theme);
            });
        });
    });
</script>
