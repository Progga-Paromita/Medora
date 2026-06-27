<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="{{ url('admin/dashboard') }}" class="nav-link">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
            </li>
        </ul>
        <!--end::Start Navbar Links-->

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--begin::Color Mode Toggle-->
            <li class="nav-item dropdown">
                <a
                    class="nav-link"
                    href="#"
                    id="bd-theme"
                    aria-label="Toggle color scheme"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
                    <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
                    <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
                </a>
                <ul
                    class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="bd-theme"
                    style="--bs-dropdown-min-width: 8rem"
                >
                    <li>
                        <button
                            type="button"
                            class="dropdown-item d-flex align-items-center"
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
                            class="dropdown-item d-flex align-items-center"
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
                            class="dropdown-item d-flex align-items-center"
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
            <!--end::Color Mode Toggle-->

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img
                        src="{{ Auth::user()->getProfileImage() }}"
                        class="user-image rounded-circle shadow"
                        alt="User Image"
                        style="width: 30px; height: 30px; object-fit: cover;"
                    />
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!--begin::User Image-->
                    <li class="user-header text-bg-primary text-center py-4">
                        <img
                            src="{{ Auth::user()->getProfileImage() }}"
                            class="rounded-circle shadow mb-2"
                            alt="User Image"
                            style="width: 80px; height: 80px; object-fit: cover;"
                        />
                        <p class="mb-0">
                            {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                            <small class="d-block text-white-50">{{ Auth::user()->is_role == 1 ? 'Administrator' : 'Pharmacy Staff' }}</small>
                        </p>
                    </li>
                    <!--end::User Image-->
                    <!--begin::Menu Footer-->
                    <li class="user-footer d-flex justify-content-between p-3 bg-light">
                        <a href="{{ url('admin/my-account') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-person me-1"></i> Profile
                        </a>
                        <a href="{{ url('logout') }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i> Sign out
                        </a>
                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
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
