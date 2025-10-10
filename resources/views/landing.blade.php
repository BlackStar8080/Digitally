{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiTally: Digital Score And Stats For Local Leagues</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <style>
        :root {
            --primary-blue: #7b2cbf;
            --secondary-blue: #9d4edd;7b2cbf
            --light-blue: #ffffff;
            --border-color: #dee2e6;
            --text-dark: #212529;
            --text-muted: #6c757d;
            --table-header: #b8d1f6;
            --hover-blue: #eef4ff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: var(--light-blue);
        }

        /* Header Navigation */
        .header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: white;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background: rgba(255, 255, 255, 0.2);
        }

        .admin-btn {
            background: white;
            color: var(--primary-blue);
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .admin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
            color: var(--primary-blue);
        }

        /* Hero Section */
        .hero-section {
            background: white;
            margin: 2rem auto;
            max-width: 1200px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .hero-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Live Games Section */
        .live-games {
            padding: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-blue);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            text-align: center;
        }

        .live-indicator {
            background: #dc3545;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, 350px);
            gap: 1.5rem;
            justify-content: center;
            max-width: 1200px; /* Limits to 3 cards max per row */
            margin: 0 auto;
        }

        .game-card {
            background: var(--light-blue);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .game-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(44, 124, 249, 0.15);
            border-color: var(--secondary-blue);
        }

        .game-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .sport-tag {
            background: var(--primary-blue);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .game-status {
            font-weight: 600;
            color: var(--text-muted);
        }

        .teams-matchup {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .team {
            text-align: center;
            flex: 1;
        }

        .team-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .team-score {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .vs-divider {
            margin: 0 1.5rem;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-muted);
        }

        .game-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid rgba(222, 226, 230, 0.5);
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* Tournaments Section */
        .tournaments-section {
            background: white;
            margin: 2rem auto;
            max-width: 1200px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .tournaments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .tournament-card {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            background: white;
            transition: all 0.3s ease;
        }

        .tournament-card:hover {
            border-color: var(--secondary-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(66, 133, 244, 0.1);
        }

        .tournament-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .tournament-name {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-dark);
        }

        .tournament-status {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: #e0aaff;
            color: white;
        }

        .status-upcoming {
            background: #ffc107;
            color: var(--text-dark);
        }

        .status-completed {
            background: var(--text-muted);
            color: white;
        }

        .tournament-details {
            margin-bottom: 1rem;
        }

        .tournament-details p {
            margin: 0.25rem 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .view-tournament-btn {
            width: 100%;
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .view-tournament-btn:hover {
            background: var(--secondary-blue);
            transform: translateY(-1px);
            color: white;
            text-decoration: none;
        }

        /* Recent Results */
        .recent-results {
            background: white;
            margin: 2rem auto;
            max-width: 1200px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .results-table th {
            background: var(--table-header);
            color: var(--text-dark);
            font-weight: 600;
            padding: 12px;
            text-align: left;
        }

        .results-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .results-table tr:hover {
            background: var(--hover-blue);
        }

        .winner {
            font-weight: 700;
            color: var(--primary-blue);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Footer */
        .footer {
            background: var(--text-dark);
            color: white;
            padding: 2rem 0 1rem;
            margin-top: 3rem;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            text-align: center;
        }

        .footer-content {
            margin-bottom: 1rem;
        }

        .footer-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }

        /* Mobile Menu */
        .mobile-menu {
            display: none;
            cursor: pointer;
            font-size: 1.5rem;
        }

        /* New Modal Design - Inspired by Your Original */
        .login-modal .modal-dialog {
            max-width: 450px;
            margin: 2rem auto;
        }

        .login-modal .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background: white;
            overflow: hidden;
        }

        .login-modal .modal-body {
            padding: 0;
        }

        /* Modal Header with Image */
        .modal-header-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #9d4edd,#7b2cbf);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .modal-header-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Close Button */
        .modal-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            color: #333;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .modal-close-btn:hover {
            background: white;
            transform: scale(1.1);
        }

        /* Tab Buttons */
        .tab-buttons {
            display: flex;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .tab-button {
            flex: 1;
            padding: 16px 24px;
            background: #f8f9fa;
            border: none;
            color: #6c757d;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-button.active {
            background: white;
            color: #007bff;
            border-bottom: 3px solid #007bff;
        }

        .tab-button:hover:not(.active) {
            background: #e9ecef;
            color: #495057;
        }

        /* Form Container */
        .form-container {
            padding: 32px;
            background: white;
            min-height: 300px;
        }

        .form-container form {
            width: 100%;
        }

        /* Form Inputs */
        .form-input {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 18px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .form-input::placeholder {
            color: #6c757d;
            font-weight: 500;
        }

        /* Submit Button */
        .form-submit-button {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg,  #9d4edd,#7b2cbf);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .form-submit-button:active {
            transform: translateY(0);
        }

        .form-submit-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Error Styling */
        .error-list {
            margin-bottom: 20px;
        }

        .error-list ul {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f1aeb5;
            border-radius: 8px;
            padding: 12px 16px;
            margin: 0;
            list-style: none;
        }

        .error-list li {
            margin: 6px 0;
            font-size: 13px;
            position: relative;
            padding-left: 20px;
        }

        .error-list li::before {
            content: "•";
            position: absolute;
            left: 0;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .mobile-menu {
                display: block;
            }

            .header-container {
                padding: 0 1rem;
            }

            .hero-section,
            .tournaments-section,
            .recent-results {
                margin: 1rem;
                padding: 1rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .games-grid,
            .tournaments-grid {
                grid-template-columns: 1fr;
            }

            .teams-matchup {
                flex-direction: column;
                gap: 1rem;
            }

            .vs-divider {
                margin: 0;
            }

            .results-table {
                font-size: 0.9rem;
            }

            .results-table th,
            .results-table td {
                padding: 8px;
            }

            /* Mobile Responsive */
            .login-modal .modal-dialog {
                margin: 1rem;
                max-width: calc(100vw - 2rem);
            }

            .modal-header-image {
                height: 160px;
            }

            .form-container {
                padding: 24px 20px;
            }

            .tab-button {
                padding: 14px 16px;
                font-size: 13px;
            }

            .form-input {
                padding: 12px 14px;
                margin-bottom: 16px;
            }

            .form-submit-button {
                padding: 12px 20px;
                font-size: 14px;
            }
        }

        .view-all-btn {
            background: var(--primary-blue);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }

        .view-all-btn:hover {
            background: var(--secondary-blue);
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* Tournament Tabs */
        .tournament-tabs {
            display: flex;
            flex-wrap: nowrap;
            gap: 1rem;
            align-items: center;
            justify-content: center; /* center the row within its container */
            margin: 0 auto 1.5rem auto; /* center block and spacing below */
            padding-bottom: 0;
            overflow-x: auto; /* allow horizontal scroll */
            -webkit-overflow-scrolling: touch;
            width: calc(100% - 2rem);
            max-width: 980px; /* limit width so tabs appear centered on large screens */
            padding: 0.5rem 1rem;
            scroll-snap-type: x mandatory; /* enable snap to center */
        }

        /* Games container that wraps the tournament tabs and games carousels */
        .games-container {
            max-width: 1200px; /* match other sections */
            margin: 2rem auto; /* same vertical spacing as other sections */
            padding: 0 2rem; /* same horizontal padding as header/sections */
            width: 100%;
        }

        /* Card-like styling for the games container */
        .card-like {
            background: white;
            border-radius: 12px;
            padding: 2rem; /* match other section padding */
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.04);
        }

        .card-like .section-header {
            padding-bottom: 0.75rem;
            margin-bottom: 0.75rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        /* Carousel for tournament tabs */
        .tournament-tabs-carousel .carousel-inner {
            padding: 0.5rem 0;
        }

        .tournament-tabs-carousel .carousel-item .tournament-tab {
            flex: 0 0 30%;
            max-width: 30%;
            min-width: 220px;
        }

        /* Custom arrow buttons for tournament tabs */
        .custom-arrow-btn {
            background: transparent;
            border: none;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 30px;
            font-weight: 800;
            text-shadow: none;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 30;
            cursor: pointer;
        }

        .custom-arrow-btn:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(125,44,191,0.12);
            border-radius: 50%;
        }

        .custom-arrow-btn .custom-arrow {
            display: inline-block;
            line-height: 1;
        }

        /* Position arrows outside the tab area on desktop to avoid overlap */
        .carousel-control-prev.custom-arrow-btn { left: -60px; }
        .carousel-control-next.custom-arrow-btn { right: -60px; }

        /* Hover/focus effect */
        .custom-arrow-btn:hover .custom-arrow,
        .custom-arrow-btn:focus .custom-arrow {
            color: var(--secondary-blue);
            transform: scale(1.05);
        }

        /* Medium screens: bring arrows slightly closer but still outside */
        @media (max-width: 992px) {
            .carousel-control-prev.custom-arrow-btn { left: -40px; }
            .carousel-control-next.custom-arrow-btn { right: -40px; }
        }

        /* Small screens: place arrows inside but offset from tabs and give white circular background */
        @media (max-width: 576px) {
            .carousel-control-prev.custom-arrow-btn { left: 8px; }
            .carousel-control-next.custom-arrow-btn { right: 8px; }
            .custom-arrow-btn {
                width: 40px;
                height: 40px;
                font-size: 22px;
                background: white;
                border-radius: 50%;
                box-shadow: 0 4px 10px rgba(0,0,0,0.08);
                color: var(--primary-blue);
            }
        }

        @media (max-width: 992px) {
            .tournament-tabs-carousel .carousel-item .tournament-tab {
                flex: 0 0 45%;
                max-width: 45%;
            }
        }

        @media (max-width: 576px) {
            .tournament-tabs-carousel .carousel-item .tournament-tab {
                flex: 0 0 90%;
                max-width: 90%;
                margin: 0 auto;
            }
        }

        .tournament-tab {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 0.85rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 260px; /* slightly smaller for better fit */
            max-width: 340px;
            width: 100%;
            position: relative;
            display: flex;
            justify-content: center; /* center content inside each tab */
            align-items: center;
            text-align: center;
            flex: 0 0 auto; /* prevent shrinking/growing */
            scroll-snap-align: center; /* snap this tab to center */
        }

        .tournament-tab:hover {
            border-color: var(--secondary-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(66, 133, 244, 0.1);
        }

        .tournament-tab.active {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            border-color: var(--primary-blue);
        }

        .tab-info {
            display: flex;
            flex-direction: column;
            align-items: center; /* center tab text */
        }

        .tab-name {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .tab-meta {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: #dc3545;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .tournament-tab.active .live-dot {
            background: white;
        }

        /* Tournament Games Content */
        .tournament-games-content {
            display: none;
        }

        .tournament-games-content.active {
            display: block;
        }

        .show-more-container {
            text-align: center;
            margin-top: 2rem;
        }

        .show-more-btn {
            background: white;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .show-more-btn:hover {
            background: var(--primary-blue);
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* Mobile responsive for tournament tabs */
        @media (max-width: 768px) {
            .tournament-tabs {
                /* keep horizontal scrolling on mobile for quick swipe between tournaments */
                flex-direction: row;
                gap: 0.75rem;
                padding: 0.5rem 0.25rem;
                width: 100%;
                max-width: 100%;
            }

            .tournament-tab {
                min-width: 220px;
                max-width: 300px;
                width: auto;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <a href="{{ route('landing') }}" class="logo">
                <i class="bi bi-trophy-fill"></i>
                DigiTally
            </a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('landing') }}" class="active">Home</a></li>
                    <li><a href="#games">Games</a></li>
                    <li><a href="#tournaments">Tournaments</a></li>
                    <li><a href="#results">Results</a></li>
                </ul>
            </nav>
            <button class="admin-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="bi bi-shield-lock me-2"></i>
                Admin Login
            </button>
            <div class="mobile-menu">
                <i class="bi bi-list"></i>
            </div>
        </div>
    </header>

    <!-- New Modern Modal Design -->
    <div class="modal fade login-modal" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <!-- Header with Sports Image -->
                    <div class="modal-header-image">
                        <img src="{{ asset('images/f05945b3f8021150c0a3403a1cd2a004.png') }}" alt="Sports" />
                        <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>

                    <!-- Tab Buttons -->
                    <div class="tab-buttons">
                        <button class="tab-button active" onclick="switchTab('login', event)">LOGIN</button>
                        <button class="tab-button" onclick="switchTab('register', event)">REGISTER</button>
                    </div>

                    <!-- Form Container -->
                    <div class="form-container">
                        <!-- Login Tab -->
                        <div id="loginTab" class="tab-content active">
                            @if ($errors->any() && session('form_type') === 'login')
                                <div class="error-list">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('login') }}" method="POST" id="loginForm">
                                @csrf
                                <input type="hidden" name="form_type" value="login">
                                <input type="email" name="email" class="form-input" placeholder="Email Address"
                                    required pattern="^[a-zA-Z0-9@.]+$" title="No special characters allowed"
                                    value="{{ old('email') }}">
                                <input type="password" name="password" class="form-input" placeholder="Password"
                                    required pattern="^[a-zA-Z0-9]+$" title="No special characters allowed">
                                <button type="submit" class="form-submit-button">Login</button>
                            </form>
                        </div>

                        <!-- Register Tab -->
                        <div id="registerTab" class="tab-content">
                            @if ($errors->any() && session('form_type') === 'register')
                                <div class="error-list">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('register') }}" method="POST" id="registerForm">
                                @csrf
                                <input type="hidden" name="form_type" value="register">
                                <input type="text" name="name" class="form-input" placeholder="Full Name" required
                                    pattern="^[a-zA-Z0-9 ]+$" title="No special characters allowed"
                                    value="{{ old('name') }}">
                                <input type="email" name="email" class="form-input" placeholder="Email Address"
                                    required pattern="^[a-zA-Z0-9@.]+$" title="No special characters allowed"
                                    value="{{ old('email') }}">
                                <input type="password" name="password" class="form-input" placeholder="Password"
                                    required pattern="^[a-zA-Z0-9]+$" title="No special characters allowed">
                                <input type="password" name="password_confirmation" class="form-input"
                                    placeholder="Confirm Password" required pattern="^[a-zA-Z0-9]+$"
                                    title="No special characters allowed">
                                <button type="submit" class="form-submit-button">Register</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-header">
            <h1 class="hero-title">DigiTally</h1>
            <p class="hero-subtitle">Digital Score And Stats For Local Leagues yeah</p>
        </div>
    </section>

    <!-- Live Games -->
    <section class="live-games" id="games">
        <!-- Tournament Tabs always at the top of games section -->
        @php
            $liveCount = $tournamentGames->sum(function ($t) {
                return $t->games->where('status', 'in-progress')->count();
            });
        @endphp

        <div class="games-container card-like">
            <div class="section-header">
                <h2 class="section-title" style="width: 100%; text-align: left;">
                    @if ($liveCount > 0)
                        <span class="live-indicator">LIVE</span>
                    @endif
                    Tournament Games
                </h2>
            </div>

            @if ($tournamentGames->count() > 0)
                <div style="display: flex; justify-content: center; width: 100%; margin-bottom: 2rem;">
                    <div id="tournamentTabsCarousel" class="carousel slide tournament-tabs-carousel" data-bs-interval="false">
                            <div class="carousel-inner">
                                @foreach ($tournamentGames->chunk(3) as $chunkIndex => $tournamentsChunk)
                                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                        <div class="d-flex justify-content-center gap-3">
                                            @foreach ($tournamentsChunk as $index => $tournament)
                                                <button class="tournament-tab {{ $chunkIndex === 0 && $index === 0 ? 'active' : '' }}"
                                                    onclick="switchTournament('{{ $tournament->id }}', this)"
                                                    data-tournament-id="{{ $tournament->id }}">
                                                    <div class="tab-info">
                                                        <span class="tab-name">{{ $tournament->name }}</span>
                                                        <span class="tab-meta">{{ $tournament->sport }} • {{ $tournament->games->count() }} Games</span>
                                                    </div>
                                                    @if ($tournament->games->where('status', 'in-progress')->count() > 0)
                                                        <span class="live-dot"></span>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button class="carousel-control-prev custom-arrow-btn" type="button" data-bs-target="#tournamentTabsCarousel" data-bs-slide="prev" aria-label="Previous tournaments">
                                <span class="custom-arrow">&lt;</span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next custom-arrow-btn" type="button" data-bs-target="#tournamentTabsCarousel" data-bs-slide="next" aria-label="Next tournaments">
                                <span class="custom-arrow">&gt;</span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
            </div>
            <hr style="margin-bottom:2rem;">
            <!-- Tournament Games Content -->
            @foreach ($tournamentGames as $index => $tournament)
                <div class="tournament-games-content {{ $index === 0 ? 'active' : '' }}"
                    data-tournament-id="{{ $tournament->id }}">
                    @if ($tournament->games->count() > 0)
                        <div id="tournamentGamesCarousel-{{ $tournament->id }}" class="carousel slide games-carousel" data-bs-interval="false">
                            <div class="carousel-inner">
                                @foreach ($tournament->games->chunk(3) as $chunkIndex => $gamesChunk)
                                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                        <div class="d-flex justify-content-center gap-3 align-items-stretch">
                                            @foreach ($gamesChunk as $game)
                                                <div class="game-card" data-game-id="{{ $game->id }}">
                                                    <div class="game-info">
                                                        <span class="sport-tag">{{ $tournament->sport }}</span>
                                                        <span class="game-status">
                                                            @if ($game->status === 'completed')
                                                                Final
                                                            @elseif($game->status === 'in-progress')
                                                                <span class="live-indicator">LIVE</span>
                                                                @if ($tournament->sport === 'Basketball')
                                                                    Q{{ $game->current_quarter ?? 1 }} -
                                                                    {{ $game->time_remaining ?? '12:00' }}
                                                                @else
                                                                    Set {{ $game->current_set ?? 1 }}
                                                                @endif
                                                            @elseif($game->scheduled_at)
                                                                {{ \Carbon\Carbon::parse($game->scheduled_at)->format('M j, g:i A') }}
                                                            @else
                                                                Round {{ $game->round }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="teams-matchup">
                                                        <div class="team">
                                                            <div class="team-name">{{ $game->team1->team_name ?? 'TBD' }}</div>
                                                            <div class="team-score">{{ $game->team1_score ?? '--' }}</div>
                                                        </div>
                                                        <div class="vs-divider">VS</div>
                                                        <div class="team">
                                                            <div class="team-name">{{ $game->team2->team_name ?? 'TBD' }}</div>
                                                            <div class="team-score">{{ $game->team2_score ?? '--' }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="game-details">
                                                        <span>Round {{ $game->round }} • Match
                                                            {{ $game->match_number ?? 'TBD' }}</span>
                                                        @if ($game->completed_at)
                                                            <span>{{ \Carbon\Carbon::parse($game->completed_at)->format('M j, g:i A') }}</span>
                                                        @elseif($game->scheduled_at)
                                                            <span>{{ \Carbon\Carbon::parse($game->scheduled_at)->format('M j, g:i A') }}</span>
                                                        @else
                                                            <span>Time: TBD</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#tournamentGamesCarousel-{{ $tournament->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#tournamentGamesCarousel-{{ $tournament->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-calendar-x"></i>
                            </div>
                            <p><strong>No games scheduled</strong></p>
                            <p>Games will appear here when the bracket is generated.</p>
                        </div>
                    @endif
                </div>
            @endforeach
                </div>
            @else
                <div class="empty-state" style="padding:2rem;">
                    <div class="empty-icon">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <p><strong>No tournaments with games</strong></p>
                    <p>Check back later for upcoming matches.</p>
                </div>
            @endif
        </div> {{-- end .games-container --}}
    </section>

    <!-- Active Tournaments -->
    <section class="tournaments-section" id="tournaments">
        <div class="section-header">
            <h2 class="section-title">
                <i class="bi bi-trophy"></i>
                Active Tournaments
            </h2>
        </div>
        <div class="tournaments-grid">
            @forelse($activeTournaments as $tournament)
                <div class="tournament-card">
                    <div class="tournament-header">
                        <div class="tournament-name">{{ $tournament->name }}</div>
                        <span
                            class="tournament-status 
                            @if ($tournament->brackets->where('status', 'active')->count() > 0) status-active
                            @elseif($tournament->start_date && \Carbon\Carbon::parse($tournament->start_date)->isFuture())
                                status-upcoming
                            @else
                                status-completed @endif
                        ">
                            @if ($tournament->brackets->where('status', 'active')->count() > 0)
                                Active
                            @elseif($tournament->start_date && \Carbon\Carbon::parse($tournament->start_date)->isFuture())
                                Upcoming
                            @else
                                Completed
                            @endif
                        </span>
                    </div>
                    <div class="tournament-details">
                        <p><i class="bi bi-calendar3"></i>
                            @if ($tournament->start_date)
                                Started: {{ \Carbon\Carbon::parse($tournament->start_date)->format('M j, Y') }}
                            @else
                                Date: TBD
                            @endif
                        </p>
                        <p><i class="bi bi-people"></i> {{ $tournament->teams->count() }} Teams •
                            {{ ucwords(str_replace('-', ' ', $tournament->bracket_type)) }}</p>
                        <p><i class="bi bi-geo-alt"></i> {{ $tournament->sport }} • {{ $tournament->division }}</p>
                    </div>
                    <a href="{{ route('tournaments.show', $tournament->id) }}" class="view-tournament-btn">
                        View Tournament
                    </a>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <p><strong>No active tournaments</strong></p>
                    <p>New tournaments will appear here when they are created.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Recent Results -->
    <section class="recent-results" id="results">
        <div class="section-header">
            <h2 class="section-title">
                <i class="bi bi-clock-history"></i>
                Recent Results
            </h2>
        </div>
        @if ($recentResults->count() > 0)
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sport</th>
                        <th>Teams</th>
                        <th>Score</th>
                        <th>Tournament</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentResults as $game)
                        <tr>
                            <td>{{ $game->completed_at ? \Carbon\Carbon::parse($game->completed_at)->format('M j') : 'N/A' }}
                            </td>
                            <td>{{ $game->bracket->tournament->sport }}</td>
                            <td>
                                @if ($game->winner_id === $game->team1_id)
                                    <span class="winner">{{ $game->team1->team_name }}</span> vs
                                    {{ $game->team2->team_name }}
                                @else
                                    {{ $game->team1->team_name }} vs <span
                                        class="winner">{{ $game->team2->team_name }}</span>
                                @endif
                            </td>
                            <td>{{ $game->team1_score ?? 0 }} - {{ $game->team2_score ?? 0 }}</td>
                            <td>{{ $game->bracket->tournament->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <p><strong>No recent results</strong></p>
                <p>Completed games will appear here.</p>
            </div>
        @endif
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-title">DigiTally</div>
                <p>Digital Scoresheet Management System - Capstone Project 2025</p>
                <p>Real-time Basketball & Volleyball Tournament Management</p>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 DigiTally Digital Scoresheet System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // Tab switching functionality - Your Original Logic
        function switchTab(tabName, event) {
            // Update tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Update tab content
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
            document.getElementById(tabName + 'Tab').classList.add('active');
        }

        // Your Original Login Form Validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = this.email.value;
            const password = this.password.value;
            const emailPattern = /^[a-zA-Z0-9@.]+$/;
            const passwordPattern = /^[a-zA-Z0-9]+$/;

            if (!emailPattern.test(email) || !passwordPattern.test(password)) {
                alert('No special characters allowed in email or password.');
                e.preventDefault();
                return false;
            }

            // Add loading state
            const submitBtn = this.querySelector('.form-submit-button');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'LOGGING IN...';

            // Reset button if needed (fallback)
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            }, 5000);
        });

        // Your Original Register Form Validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const name = this.name.value;
            const email = this.email.value;
            const password = this.password.value;
            const confirmPassword = this.password_confirmation.value;

            const namePattern = /^[a-zA-Z0-9 ]+$/;
            const emailPattern = /^[a-zA-Z0-9@.]+$/;
            const passwordPattern = /^[a-zA-Z0-9]+$/;

            if (!namePattern.test(name) || !emailPattern.test(email) ||
                !passwordPattern.test(password) || !passwordPattern.test(confirmPassword)) {
                alert('No special characters allowed in name, email, or password.');
                e.preventDefault();
                return false;
            }

            // Add loading state
            const submitBtn = this.querySelector('.form-submit-button');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'REGISTERING...';

            // Reset button if needed (fallback)
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            }, 5000);
        });

        // Auto-focus first input when modal opens
        document.getElementById('loginModal').addEventListener('shown.bs.modal', function() {
            const activeTab = document.querySelector('.tab-content.active');
            const firstInput = activeTab.querySelector('.form-input');
            if (firstInput) {
                firstInput.focus();
            }
        });

        // Open modal and switch to appropriate tab if there are validation errors
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();

                @if (session('form_type') === 'register')
                    // Switch to register tab if register form had errors
                    setTimeout(() => {
                        const registerTabBtn = document.querySelectorAll('.tab-button')[1];
                        const loginTabBtn = document.querySelectorAll('.tab-button')[0];

                        loginTabBtn.classList.remove('active');
                        registerTabBtn.classList.add('active');

                        document.getElementById('loginTab').classList.remove('active');
                        document.getElementById('registerTab').classList.add('active');
                    }, 100);
                @endif
            });
        @endif

        // Auto-refresh live scores every 30 seconds
        function refreshLiveScores() {
            fetch('{{ route('api.live-scores') }}')
                .then(response => response.json())
                .then(data => {
                    data.forEach(game => {
                        const gameCard = document.querySelector(`[data-game-id="${game.id}"]`);
                        if (gameCard) {
                            // Update scores
                            const team1Score = gameCard.querySelector('.team:first-child .team-score');
                            const team2Score = gameCard.querySelector('.team:last-child .team-score');
                            const gameStatus = gameCard.querySelector('.game-status');

                            if (team1Score) team1Score.textContent = game.team1.score;
                            if (team2Score) team2Score.textContent = game.team2.score;
                            if (gameStatus) gameStatus.textContent = game.status;
                        }
                    });
                })
                .catch(error => console.log('Error fetching live scores:', error));
        }

        // Start auto-refresh if there are live games
        @if ($liveGames->where('status', 'in-progress')->count() > 0)
            setInterval(refreshLiveScores, 30000);
        @endif

        // Smooth scrolling for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        function switchTournament(tournamentId, clickedTab) {
            // Update tab buttons
            const allTabs = document.querySelectorAll('.tournament-tab');
            allTabs.forEach(tab => tab.classList.remove('active'));
            clickedTab.classList.add('active');

            // Update tournament content
            const allContent = document.querySelectorAll('.tournament-games-content');
            allContent.forEach(content => content.classList.remove('active'));

            const targetContent = document.querySelector(`[data-tournament-id="${tournamentId}"].tournament-games-content`);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        }
    </script>
</body>

</html>
