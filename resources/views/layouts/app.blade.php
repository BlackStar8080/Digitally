<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tally Sheets')</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-blue: #2563eb;
            --secondary-blue: #3b82f6;
            --accent-blue: #1d4ed8;
            --text-light: rgba(255, 255, 255, 0.9);
            --text-white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            padding-top: 80px;
            line-height: 1.6;
        }

        /* 🔹 Enhanced Navbar */
        .navbar {
            background: #9d4edd;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--shadow-lg);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 0;
            transition: all 0.3s ease;
        }

        .navbar-content {
            display: flex;
            align-items: center;
            padding: 16px 32px;
            max-width: 1400px;
            margin: 0 auto;
            justify-content: space-between;
            gap: 32px;
        }

        /* Brand/Logo area (optional - can add later) */
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-white);
            text-decoration: none;
            letter-spacing: -0.025em;
        }

        /* Navigation Links */
        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 8px;
            align-items: center;
            flex: 1;
            justify-content: center;
        }

        .nav-links li {
            position: relative;
        }

        /* Dropdown Navigation Styles */
        .dropdown-nav {
            position: relative;
        }

        .dropdown-nav > a {
            position: relative;
        }

        .dropdown-icon {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .dropdown-nav:hover .dropdown-icon {
            transform: rotate(180deg);
        }

        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            left: 0;
            background: #ffffff;
            backdrop-filter: blur(10px);
            min-width: 200px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            list-style: none;
            padding: 8px;
            margin: 8px 0 0 0;
            z-index: 1000;
            border: 1px solid #e2e8f0;
        }

        .dropdown-nav:hover .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu-custom li {
            margin: 0;
        }

        .dropdown-menu-custom li a {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    color: #9d4edd !important;    /* ✅ PURPLE TEXT */
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
    width: 100%;
}

.dropdown-menu-custom li a:hover {
    background: #f3e8ff !important;    /* ✅ LIGHT PURPLE HOVER */
    color: #7c3aed !important;    /* ✅ DARKER PURPLE ON HOVER */
    transform: translateX(4px);
}

        .dropdown-menu-custom li a i {
            font-size: 16px;
            width: 20px;
        }

        /* Active dropdown state for mobile */
        .dropdown-nav.active-dropdown .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .dropdown-nav.active-dropdown .dropdown-menu-custom {
                transform: translateX(-50%) translateY(0);
            }
        }

        .nav-links li a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            font-weight: 500;
            font-size: 15px;
            padding: 12px 20px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            letter-spacing: 0.025em;
            white-space: nowrap;
        }

        .nav-links li a::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .nav-links li a:hover::before {
            opacity: 1;
        }

        .nav-links li a:hover {
            color: var(--text-white);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .nav-links li a.active {
            background: rgba(255, 255, 255, 0.15);
            color: var(--text-white);
            font-weight: 600;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .nav-links li a.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 2px;
            background: var(--text-white);
            border-radius: 1px;
        }

        /* Actions area - FIXED: removed absolute positioning */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Enhanced Logout button */
        .logout-btn {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.025em;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(157, 78, 221, 0.15);
            backdrop-filter: blur(8px) saturate(180%);
            -webkit-backdrop-filter: blur(8px) saturate(180%);
        }

        .logout-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(239,68,68,0.15));
            opacity: 0.7;
            pointer-events: none;
            transition: opacity 0.3s ease;
            border-radius: 12px;
        }

        .logout-btn:hover::before {
            opacity: 1;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(157, 78, 221, 0.25);
            color: #ffffff;
        }

        .logout-btn:active {
            transform: translateY(0);
        }

        .logout-btn i,
        .logout-btn span {
            position: relative;
            z-index: 1;
        }

        /* Modal Enhancements */
        .modal-header.logout-modal {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-bottom: none;
            padding: 24px;
        }

        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .modal-body {
            padding: 24px;
            background: #ffffff;
        }

        .modal-footer {
            padding: 20px 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .btn-close:hover {
            opacity: 1;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .navbar-content {
                padding: 14px 24px;
            }
            
            .nav-links {
                gap: 4px;
            }
            
            .nav-links li a {
                padding: 10px 16px;
                font-size: 14px;
            }
        }

        @media (max-width: 992px) {
            .nav-links li a {
                padding: 10px 12px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 140px;
            }

            .navbar-content {
                flex-direction: column;
                padding: 16px 20px;
                gap: 16px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 8px;
                width: 100%;
            }

            .nav-links li {
                flex: 1;
                min-width: calc(50% - 4px);
            }

            .nav-links li a {
                justify-content: center;
                padding: 10px 8px;
                font-size: 13px;
                border-radius: 8px;
            }

            .nav-actions {
                width: 100%;
                justify-content: center;
            }

            .logout-btn {
                padding: 10px 24px;
                font-size: 13px;
            }

            /* Mobile dropdown adjustments */
            .dropdown-menu-custom {
                position: fixed;
                left: 50%;
                transform: translateX(-50%) translateY(-10px);
                min-width: 240px;
            }

            .dropdown-nav:hover .dropdown-menu-custom {
                transform: translateX(-50%) translateY(0);
            }
        }

        @media (max-width: 480px) {
            .navbar-content {
                padding: 12px 16px;
            }

            .nav-links li {
                min-width: calc(33.333% - 6px);
            }

            .nav-links li a {
                padding: 8px 4px;
                font-size: 12px;
            }
        }

        /* Scrolled state - slight transparency */
        .navbar.scrolled {
            background: rgba(157, 78, 221, 0.95);
            backdrop-filter: blur(20px);
        }

        /* Add some demo content to test scrolling */
        .demo-content {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .demo-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #e2e8f0;
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- ✅ Enhanced Navbar --}}
    <nav class="navbar" id="mainNavbar">
        <div class="navbar-content">
             <div class="nav-links">
                <a href="{{ route('dashboard') }}" class="navbar-brand">DigiTally</a> 
            <ul class="nav-links">
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-1"></i>
                    <span>Dashboard</span>
                </a></li>
                <li><a href="{{ route('games.index') }}" class="{{ request()->routeIs('games.*') ? 'active' : '' }}">
                    <i class="bi bi-controller me-1"></i>
                    <span>Games</span>
                </a></li>
                <li><a href="{{ route('teams.index') }}" class="{{ request()->routeIs('teams.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill me-1"></i>
                    <span>Teams</span>
                </a></li>
                <li class="dropdown-nav">
                    <a href="{{ route('players.index') }}" class="{{ request()->routeIs('players.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge me-1"></i>
                        <span>Players</span>
                       
                    </a>
                    <ul class="dropdown-menu-custom">
                        <li><a href="{{ route('players.index') }}">
                            <i class="bi bi-list-ul me-2"></i>
                            Player List
                        </a></li>
                        <li><a href="{{ route('players.stats') }}">
                            <i class="bi bi-graph-up me-2"></i>
                            Player Stats
                        </a></li>
                    </ul>
                </li>
                <li><a href="{{ route('tournaments.index') }}" class="{{ request()->routeIs('tournaments.*') ? 'active' : '' }}">
                    <i class="bi bi-trophy me-1"></i>
                    <span>Tournaments</span>
                </a></li>
                @if(!session('is_guest'))
                    <li><a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart me-1"></i>
                        <span>Reports</span>
                    </a></li>
                @endif
            </ul>
            
            <div class="nav-actions">
                <button class="logout-btn" data-bs-toggle="modal" data-bs-target="#logoutModal" type="button">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>
    </nav>

    {{-- ✅ Page Content --}}
    <div class="main-container">
        
        @yield('content')
    </div>

    {{-- Global toast (shows messages like successful login) --}}
    <div class="global-toast-container" id="globalToastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none;">
        <div class="global-toast" id="globalToast" style="background: white; border-radius: 12px; padding: 1rem 1.25rem; box-shadow: 0 10px 40px rgba(0,0,0,0.15); display:flex; gap:0.75rem; align-items:center; min-width:300px; border-left:4px solid #28a745;">
            <div style="width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,#28a745,#20c997); display:flex; align-items:center; justify-content:center; color:white; font-size:18px; flex-shrink:0;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div style="flex:1">
                <div id="globalToastTitle" style="font-weight:700; color:#212529; font-size:14px;">Success</div>
                <div id="globalToastMessage" style="color:#6c757d; font-size:13px;">Operation completed successfully.</div>
            </div>
            <button id="globalToastClose" style="background:none; border:none; color:#6c757d; font-size:18px; cursor:pointer;">&times;</button>
        </div>
    </div>

    {{-- ✅ Enhanced Logout Modal --}}
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

    <div class="modal fade" id="logoutModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header logout-modal">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle me-2"></i> 
                        Confirm Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to logout? You will be redirected to the login page.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmLogout">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        Yes, Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    {{-- ✅ Enhanced Scripts --}}
    <script>
        // Handle logout confirmation
        document.getElementById('confirmLogout').addEventListener('click', function () {
            document.getElementById('logoutForm').submit();
        });

        // Add scroll effect to navbar (optional)
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Add keyboard navigation support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const logoutModal = bootstrap.Modal.getInstance(document.getElementById('logoutModal'));
                if (logoutModal) {
                    logoutModal.hide();
                }
            }
        });

        // Mobile dropdown toggle support
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownNavs = document.querySelectorAll('.dropdown-nav > a');
            
            dropdownNavs.forEach(function(dropdownLink) {
                dropdownLink.addEventListener('click', function(e) {
                    // Only prevent default on mobile
                    if (window.innerWidth <= 768) {
                        e.preventDefault();
                        const parent = this.parentElement;
                        
                        // Close other dropdowns
                        document.querySelectorAll('.dropdown-nav').forEach(function(nav) {
                            if (nav !== parent) {
                                nav.classList.remove('active-dropdown');
                            }
                        });
                        
                        // Toggle current dropdown
                        parent.classList.toggle('active-dropdown');
                    }
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown-nav')) {
                    document.querySelectorAll('.dropdown-nav').forEach(function(nav) {
                        nav.classList.remove('active-dropdown');
                    });
                }
            });
        });
    </script>

    {{-- Page-specific scripts --}}
    @yield('scripts')
    @stack('scripts')

    <script>
        // Global toast helper (used for login success and other global messages)
        function showGlobalToast(message, title = 'Success', duration = 4000) {
            try {
                var container = document.getElementById('globalToastContainer');
                var toast = document.getElementById('globalToast');
                var msgEl = document.getElementById('globalToastMessage');
                var titleEl = document.getElementById('globalToastTitle');
                var closeBtn = document.getElementById('globalToastClose');

                if (!container || !toast || !msgEl) return;

                titleEl.textContent = title;
                msgEl.textContent = message;
                container.style.display = 'block';
                toast.classList.add('show');

                function hide() {
                    toast.classList.remove('show');
                    container.style.display = 'none';
                }

                closeBtn.onclick = hide;

                setTimeout(hide, duration);
            } catch (err) {
                console.error('Toast error', err);
            }
        }

        // If there's a success message in session (e.g., after login), show it
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showGlobalToast({!! json_encode(session('success')) !!});
            @endif
        });
    </script>
</body>
</html>