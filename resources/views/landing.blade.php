{{-- resources/views/landing.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiTally: Digital Score And Stats For Local Leagues</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
:root {
    --primary-purple: #9d4edd;
    --secondary-purple: #7c3aed;
    --accent-purple: #5f2da8;
    --light-purple: #ffffff;
    --border-color: #e5e7eb;
    --text-dark: #212529;
    --text-muted: #6c757d;
    --background-light: #f8faff;
    --table-header: #d1b3ff;
    --hover-purple: #ede9fe;
    --shadow-sm: 0 2px 8px rgba(157, 78, 221, 0.08);
    --shadow-md: 0 4px 16px rgba(157, 78, 221, 0.12);
    --shadow-lg: 0 8px 32px rgba(157, 78, 221, 0.16);
    --success-green: #10b981;
}

/* Smooth Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% { 
        opacity: 1; 
        transform: scale(1); 
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
    }
    50% { 
        opacity: 0.8; 
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
    }
}

@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: var(--text-dark);
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

html {
    scroll-behavior: smooth;
}

/* Enhanced Header - Match Dashboard Navbar */
.header {
    background: #9d4edd;
    backdrop-filter: blur(10px);
    color: white;
    padding: 0;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    animation: fadeIn 0.6s ease-out;
    transition: all 0.3s ease;
}

.header-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 16px 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 32px;
}

.logo {
    font-size: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #ffffff;
    letter-spacing: -0.025em;
    transition: all 0.3s ease;
}

.logo:hover {
    transform: scale(1.05);
    color: #ffffff;
}

.logo i {
    color: #ffffff;
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 8px;
    align-items: center;
    margin: 0;
    padding: 0;
}

.nav-menu a {
    display: flex;
    align-items: center;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 15px;
    position: relative;
    letter-spacing: 0.025em;
    white-space: nowrap;
}

.nav-menu a::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.nav-menu a:hover::before {
    opacity: 1;
}

.nav-menu a:hover {
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.nav-menu a.active {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-weight: 600;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.nav-menu a.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 50%;
    transform: translateX(-50%);
    width: 24px;
    height: 2px;
    background: #ffffff;
    border-radius: 1px;
}

.admin-btn {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 0.025em;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(157, 78, 221, 0.15);
    backdrop-filter: blur(8px) saturate(180%);
}

.admin-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.15));
    opacity: 0.7;
    pointer-events: none;
    transition: opacity 0.3s ease;
    border-radius: 12px;
}

.admin-btn:hover::before {
    opacity: 1;
}

.admin-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(157, 78, 221, 0.25);
    color: #ffffff;
}

.admin-btn:active {
    transform: translateY(0);
}

/* Scrolled state */
.header.scrolled {
    background: rgba(157, 78, 221, 0.95);
    backdrop-filter: blur(20px);
}

/* Enhanced Hero Section */
.hero-section {
    background: white;
    margin: 2rem auto;
    max-width: 1400px;
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    position: relative;
    animation: fadeInUp 0.6s ease-out;
}

.hero-header {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 50%, var(--accent-purple) 100%);
    color: white;
    padding: 4rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero-header::before {
    content: '🏆';
    position: absolute;
    right: 5%;
    top: 50%;
    transform: translateY(-50%) rotate(15deg);
    font-size: 8rem;
    opacity: 0.15;
    animation: fadeIn 1s ease-out;
}

.hero-header::after {
    content: '⚽';
    position: absolute;
    left: 5%;
    top: 50%;
    transform: translateY(-50%) rotate(-15deg);
    font-size: 6rem;
    opacity: 0.15;
    animation: fadeIn 1s ease-out 0.2s backwards;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 900;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
    letter-spacing: -2px;
    animation: fadeInUp 0.8s ease-out 0.2s backwards;
}

.hero-subtitle {
    font-size: 1.35rem;
    opacity: 0.95;
    position: relative;
    z-index: 1;
    font-weight: 500;
    animation: fadeInUp 0.8s ease-out 0.3s backwards;
}

/* Live Games Section */
.live-games {
    padding: 2rem;
    animation: fadeInUp 0.6s ease-out 0.1s backwards;
}

.games-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 2rem;
    width: 100%;
}

.card-like {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(157, 78, 221, 0.08);
    transition: all 0.3s ease;
}

.card-like:hover {
    box-shadow: 0 12px 40px rgba(157, 78, 221, 0.15);
    transform: translateY(-2px);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid rgba(157, 78, 221, 0.1);
}

.section-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-title i {
    color: var(--primary-purple);
}

.live-indicator {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    animation: pulse 2s infinite;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* Tournament Tabs with Enhanced Carousel */
.tournament-tabs-carousel {
    margin-bottom: 2rem;
    position: relative;
    padding: 0 60px;
}

.tournament-tab {
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 280px;
    max-width: 380px;
    width: 100%;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    flex: 0 0 auto;
    overflow: hidden;
}

.tournament-tab::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.tournament-tab:hover::before {
    transform: scaleX(1);
}

.tournament-tab:hover {
    border-color: var(--secondary-purple);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    background: var(--hover-purple);
}

.tournament-tab.active {
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    color: white;
    border-color: var(--primary-purple);
    box-shadow: var(--shadow-md);
}

.tournament-tab.active::before {
    transform: scaleX(1);
    background: rgba(255, 255, 255, 0.3);
}

.tab-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 1;
}

.tab-name {
    font-weight: 800;
    font-size: 1.1rem;
    margin-bottom: 0.35rem;
}

.tab-meta {
    font-size: 0.85rem;
    opacity: 0.85;
    font-weight: 600;
}

.live-dot {
    width: 10px;
    height: 10px;
    background: #dc3545;
    border-radius: 50%;
    animation: pulse 2s infinite;
    margin-left: 8px;
    box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
}

.tournament-tab.active .live-dot {
    background: white;
}

/* Enhanced Custom Arrows */
.custom-arrow-btn {
    background: white;
    border: 2px solid var(--border-color);
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-purple);
    font-size: 20px;
    font-weight: 800;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 30;
    cursor: pointer;
    border-radius: 50%;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.custom-arrow-btn:hover {
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    color: white;
    border-color: var(--primary-purple);
    box-shadow: var(--shadow-md);
    transform: translateY(-50%) scale(1.1);
}

.carousel-control-prev.custom-arrow-btn { left: 0; }
.carousel-control-next.custom-arrow-btn { right: 0; }

/* Enhanced Game Cards */
.games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 1.5rem;
    justify-content: center;
    max-width: 1400px;
    margin: 0 auto;
}

.game-card {
    background: white;
    border-radius: 16px;
    padding: 1.75rem;
    transition: all 0.3s ease;
    border: 2px solid var(--border-color);
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out;
}

.game-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.game-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    border-color: var(--secondary-purple);
}

.game-card:hover::before {
    transform: scaleX(1);
}

.game-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
}

.sport-tag {
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    color: white;
    padding: 6px 14px;
    border-radius: 14px;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    box-shadow: 0 2px 8px rgba(157, 78, 221, 0.2);
}

.game-status {
    font-weight: 700;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.teams-matchup {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
    padding: 1.25rem;
    background: linear-gradient(135deg, rgba(157, 78, 221, 0.05), rgba(124, 58, 237, 0.05));
    border-radius: 12px;
    transition: all 0.3s ease;
}

.game-card:hover .teams-matchup {
    background: var(--hover-purple);
}

.team {
    text-align: center;
    flex: 1;
}

.team-name {
    font-weight: 800;
    font-size: 1.1rem;
    margin-bottom: 0.75rem;
    color: var(--text-dark);
}

.team-score {
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.vs-divider {
    margin: 0 1.5rem;
    font-weight: 800;
    font-size: 1.1rem;
    color: var(--text-muted);
    background: white;
    padding: 0.75rem;
    border-radius: 50%;
    min-width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-sm);
}

.game-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
    font-size: 0.85rem;
    color: var(--text-muted);
    font-weight: 600;
}

/* Enhanced Tournament Cards */
.tournaments-section {
    background: white;
    margin: 2rem auto;
    max-width: 1400px;
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
    padding: 2.5rem;
    border: 1px solid rgba(157, 78, 221, 0.08);
    animation: fadeInUp 0.6s ease-out 0.2s backwards;
}

.tournaments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.tournament-card {
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 1.75rem;
    background: white;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out;
}

.tournament-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.tournament-card:hover {
    border-color: var(--secondary-purple);
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.tournament-card:hover::before {
    transform: scaleX(1);
}

.tournament-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1.25rem;
}

.tournament-name {
    font-weight: 800;
    font-size: 1.25rem;
    color: var(--text-dark);
}

.tournament-status {
    padding: 6px 12px;
    border-radius: 14px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.3px;
}

.status-active {
    background: linear-gradient(135deg, #d1b3ff, #e0aaff);
    color: var(--primary-purple);
}

.status-upcoming {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.status-completed {
    background: linear-gradient(135deg, #e5e7eb, #d1d5db);
    color: var(--text-muted);
}

.tournament-details {
    margin-bottom: 1.25rem;
}

.tournament-details p {
    margin: 0.5rem 0;
    color: var(--text-muted);
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tournament-details i {
    color: var(--primary-purple);
}

.view-tournament-btn {
    width: 100%;
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    color: white;
    border: none;
    padding: 12px;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: block;
    text-align: center;
    font-size: 0.95rem;
    letter-spacing: 0.3px;
}

.view-tournament-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: white;
    text-decoration: none;
}

/* Enhanced Recent Results */
.recent-results {
    background: white;
    margin: 2rem auto;
    max-width: 1400px;
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
    padding: 2.5rem;
    border: 1px solid rgba(157, 78, 221, 0.08);
    animation: fadeInUp 0.6s ease-out 0.3s backwards;
}

.results-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 1rem;
    overflow: hidden;
    border-radius: 12px;
}

.results-table th {
    background: var(--table-header);
    color: var(--primary-purple);
    font-weight: 800;
    padding: 14px 16px;
    text-align: left;
    font-size: 0.9rem;
    letter-spacing: 0.3px;
}

.results-table th:first-child {
    border-top-left-radius: 12px;
}

.results-table th:last-child {
    border-top-right-radius: 12px;
}

.results-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    font-size: 0.9rem;
}

.results-table tr {
    transition: all 0.3s ease;
}

.results-table tr:hover {
    background: var(--hover-purple);
    transform: translateX(4px);
}

.results-table tr:last-child td {
    border-bottom: none;
}

.winner {
    font-weight: 800;
    color: var(--primary-purple);
}

/* Enhanced Empty States */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-muted);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    opacity: 0.2;
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.empty-state strong {
    display: block;
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

/* Enhanced Footer */
.footer {
    background: linear-gradient(135deg, var(--text-dark) 0%, #1a1a1a 100%);
    color: white;
    padding: 3rem 0 1.5rem;
    margin-top: 4rem;
}

.footer-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
}

.footer-title {
    font-size: 1.75rem;
    font-weight: 900;
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1rem;
}

.footer-content p {
    opacity: 0.8;
    margin: 0.5rem 0;
    font-weight: 500;
}

.footer-bottom {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-bottom p {
    opacity: 0.6;
    font-size: 0.9rem;
}

/* Enhanced Modal */
.login-modal .modal-dialog {
    max-width: 450px;
    margin: 2rem auto;
}

.login-modal .modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
    background: white;
    overflow: hidden;
    animation: fadeInUp 0.4s ease-out;
}

.modal-header-image {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple), var(--accent-purple));
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

.modal-close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: white;
    border: none;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 20px;
    color: var(--text-dark);
    transition: all 0.3s ease;
    z-index: 10;
    box-shadow: var(--shadow-sm);
}

.modal-close-btn:hover {
    transform: scale(1.1) rotate(90deg);
    box-shadow: var(--shadow-md);
    background: var(--primary-purple);
    color: white;
}

.tab-buttons {
    display: flex;
    background: var(--background-light);
}

.tab-button {
    flex: 1;
    padding: 18px 24px;
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    text-align: center;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
}

.tab-button.active {
    background: white;
    color: var(--primary-purple);
    border-bottom: 3px solid var(--primary-purple);
}

.tab-button:hover:not(.active) {
    background: rgba(157, 78, 221, 0.05);
    color: var(--primary-purple);
}

.form-container {
    padding: 2rem;
    background: white;
}

.form-input {
    width: 100%;
    padding: 14px 16px;
    margin-bottom: 18px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.3s ease;
    background: white;
    font-weight: 600;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 4px rgba(157, 78, 221, 0.1);
    background: var(--hover-purple);
}

.form-submit-button {
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
}

.form-submit-button:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.form-submit-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.guest-login-section {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--background-light);
    text-align: center;
}

.guest-login-text {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.guest-login-btn {
    width: 100%;
    padding: 14px 24px;
    background: white;
    color: var(--primary-purple);
    border: 2px solid var(--primary-purple);
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.guest-login-btn:hover {
    background: var(--primary-purple);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.guest-login-btn i {
    font-size: 1.2rem;
}

.error-list ul {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
    border: 2px solid #fca5a5;
    border-radius: 12px;
    padding: 14px 18px;
    margin: 0 0 20px 0;
    list-style: none;
}

.error-list li {
    margin: 8px 0;
    font-size: 13px;
    font-weight: 600;
    position: relative;
    padding-left: 24px;
}

.error-list li::before {
    content: "⚠";
    position: absolute;
    left: 0;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .games-grid {
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    }
}

@media (max-width: 992px) {
    .header-container {
        padding: 0 1.5rem;
    }

    .carousel-control-prev.custom-arrow-btn { 
        left: -50px; 
    }
    
    .carousel-control-next.custom-arrow-btn { 
        right: -50px; 
    }

    .tournament-tabs-carousel {
        padding: 0 50px;
    }
}

@media (max-width: 768px) {
    .nav-menu {
        display: none;
    }

    .header-container {
        padding: 0 1rem;
    }

    .logo {
        font-size: 24px;
    }

    .hero-section,
    .tournaments-section,
    .recent-results,
    .card-like {
        margin: 1rem;
        padding: 1.5rem;
    }

    .hero-title {
        font-size: 2.5rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }

    .hero-header {
        padding: 3rem 1.5rem;
    }

    .hero-header::before,
    .hero-header::after {
        font-size: 4rem;
    }

    .games-grid,
    .tournaments-grid {
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }

    .section-title {
        font-size: 1.5rem;
    }

    .results-table {
        font-size: 0.85rem;
    }

    .results-table th,
    .results-table td {
        padding: 10px 8px;
    }

    .tournament-tabs-carousel {
        padding: 0 40px;
    }

    .tournament-tab {
        min-width: 240px;
        padding: 1rem;
    }

    .tab-name {
        font-size: 1rem;
    }

    .tab-meta {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 2rem;
        letter-spacing: -1px;
    }

    .hero-subtitle {
        font-size: 1rem;
    }

    .hero-header {
        padding: 2.5rem 1rem;
    }

    .hero-header::before,
    .hero-header::after {
        font-size: 3rem;
    }

    .carousel-control-prev.custom-arrow-btn { 
        left: 8px;
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    
    .carousel-control-next.custom-arrow-btn { 
        right: 8px;
        width: 40px;
        height: 40px;
        font-size: 18px;
    }

    .tournament-tabs-carousel {
        padding: 0 50px;
    }

    .tournament-tab {
        min-width: 200px;
        padding: 0.875rem 1rem;
    }

    .game-card {
        padding: 1.5rem;
    }

    .team-score {
        font-size: 2rem;
    }

    .team-name {
        font-size: 1rem;
    }

    .vs-divider {
        margin: 0 1rem;
        font-size: 1rem;
        min-width: 40px;
        height: 40px;
    }

    .section-title {
        font-size: 1.35rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .admin-btn {
        padding: 10px 18px;
        font-size: 13px;
    }

    .admin-btn i {
        display: none;
    }

    .results-table th,
    .results-table td {
        padding: 8px 6px;
        font-size: 0.8rem;
    }

    .tournament-name {
        font-size: 1.1rem;
    }

    .tournament-card {
        padding: 1.5rem;
    }
}

@media (max-width: 400px) {
    .hero-title {
        font-size: 1.75rem;
    }

    .hero-subtitle {
        font-size: 0.9rem;
    }

    .games-grid {
        grid-template-columns: 1fr;
    }

    .game-card {
        padding: 1.25rem;
    }

    .team-score {
        font-size: 1.75rem;
    }

    .teams-matchup {
        padding: 1rem;
    }
}

/* Loading States */
.loading {
    animation: shimmer 2s infinite;
    background: linear-gradient(to right, #f0f0f0 4%, #e0e0e0 25%, #f0f0f0 36%);
    background-size: 1000px 100%;
}

/* Utility Classes */
.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.fade-in {
    animation: fadeIn 0.6s ease-out;
}

.slide-in {
    animation: slideIn 0.6s ease-out;
}

/* Print Styles */
@media print {
    .header,
    .footer,
    .admin-btn,
    .custom-arrow-btn,
    .carousel-control-prev,
    .carousel-control-next {
        display: none;
    }

    body {
        background: white;
    }

    .hero-section,
    .tournaments-section,
    .recent-results,
    .card-like {
        box-shadow: none;
        border: 1px solid var(--border-color);
        page-break-inside: avoid;
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
        </div>
    </header>

    <!-- Modal -->
    <div class="modal fade login-modal" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-header-image">
                        <img src="{{ asset('images/f05945b3f8021150c0a3403a1cd2a004.png') }}" alt="Sports" />
                        <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="tab-buttons">
                        <button class="tab-button active" onclick="switchTab('login', event)">LOGIN</button>
                        <button class="tab-button" onclick="switchTab('register', event)">REGISTER</button>
                    </div>
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
                            <div class="guest-login-section">
                                <p class="guest-login-text">Don't have an account?</p>
                                <button type="button" class="guest-login-btn" onclick="continueAsGuest()">
                                    <i class="bi bi-person"></i>
                                    Continue as Guest
                                </button>
                            </div>
                        </div>
                        <!-- Register Tab -->
                        <div id="registerTab" class="tab-content" style="display: none;">
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
            <p class="hero-subtitle">Digital Score And Stats For Local Leagues</p>
        </div>
    </section>

    <!-- Live Games -->
    <section class="live-games" id="games">
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
                                                    <span class="tab-meta">{{ $tournament->sport_name }} • {{ $tournament->games->count() }} Games</span>
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
                        </button>
                        <button class="carousel-control-next custom-arrow-btn" type="button" data-bs-target="#tournamentTabsCarousel" data-bs-slide="next" aria-label="Next tournaments">
                            <span class="custom-arrow">&gt;</span>
                        </button>
                    </div>
                </div>

                <hr style="margin-bottom:2rem;">

                <!-- Tournament Games Content -->
                @foreach ($tournamentGames as $index => $tournament)
                    <div class="tournament-games-content {{ $index === 0 ? 'active' : '' }}" style="display: {{ $index === 0 ? 'block' : 'none' }};"
                        data-tournament-id="{{ $tournament->id }}">
                        @if ($tournament->games->count() > 0)
                            <div id="tournamentGamesCarousel-{{ $tournament->id }}" class="carousel slide games-carousel" data-bs-interval="false">
                                <div class="carousel-inner">
                                    @foreach ($tournament->games->chunk(3) as $chunkIndex => $gamesChunk)
                                        <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                            <div class="games-grid">
                                                @foreach ($gamesChunk as $game)
                                                    <div class="game-card" data-game-id="{{ $game->id }}">
                                                        <div class="game-info">
                                                            <span class="sport-tag">{{ $tournament->sport_name }}</span>
                                                            <span class="game-status">
                                                                @if ($game->status === 'completed')
                                                                    Final
                                                                @elseif($game->status === 'in-progress')
                                                                    <span class="live-indicator">LIVE</span>
                                                                    @if ($tournament->sport_name === 'Basketball')
                                                                        Q{{ $game->current_quarter ?? 1 }} - {{ $game->time_remaining ?? '12:00' }}
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
                                                            <span>Round {{ $game->round }} • Match {{ $game->match_number ?? 'TBD' }}</span>
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
                                @if ($tournament->games->count() > 3)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#tournamentGamesCarousel-{{ $tournament->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#tournamentGamesCarousel-{{ $tournament->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    </button>
                                @endif
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
            @else
                <div class="empty-state" style="padding:2rem;">
                    <div class="empty-icon">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <p><strong>No tournaments with games</strong></p>
                    <p>Check back later for upcoming matches.</p>
                </div>
            @endif
        </div>
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
                        <span class="tournament-status
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
                        <p><i class="bi bi-geo-alt"></i> {{ $tournament->sport_name }} • {{ $tournament->division }}</p>
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
                            <td>{{ $game->completed_at ? \Carbon\Carbon::parse($game->completed_at)->format('M j') : 'N/A' }}</td>
                            <td>{{ $game->bracket->tournament->sport_name }}</td>
                            <td>
                                @if ($game->winner_id === $game->team1_id)
                                    <span class="winner">{{ $game->team1->team_name }}</span> vs {{ $game->team2->team_name }}
                                @else
                                    {{ $game->team1->team_name }} vs <span class="winner">{{ $game->team2->team_name }}</span>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function switchTab(tabName, event) {
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.style.display = 'none');
            document.getElementById(tabName + 'Tab').style.display = 'block';
        }

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

            const submitBtn = this.querySelector('.form-submit-button');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'LOGGING IN...';

            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            }, 5000);
        });

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

            const submitBtn = this.querySelector('.form-submit-button');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'REGISTERING...';

            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            }, 5000);
        });

        document.getElementById('loginModal').addEventListener('shown.bs.modal', function() {
            const activeTab = document.querySelector('.tab-content[style*="block"]') || document.querySelector('.tab-content');
            const firstInput = activeTab.querySelector('.form-input');
            if (firstInput) firstInput.focus();
        });

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
                @if (session('form_type') === 'register')
                    setTimeout(() => {
                        const registerTabBtn = document.querySelectorAll('.tab-button')[1];
                        const loginTabBtn = document.querySelectorAll('.tab-button')[0];
                        loginTabBtn.classList.remove('active');
                        registerTabBtn.classList.add('active');
                        document.getElementById('loginTab').style.display = 'none';
                        document.getElementById('registerTab').style.display = 'block';
                    }, 100);
                @endif
            });
        @endif

        function switchTournament(tournamentId, clickedTab) {
            const allTabs = document.querySelectorAll('.tournament-tab');
            allTabs.forEach(tab => tab.classList.remove('active'));
            clickedTab.classList.add('active');

            const allContent = document.querySelectorAll('.tournament-games-content');
            allContent.forEach(content => content.style.display = 'none');

            const targetContent = document.querySelector(`[data-tournament-id="${tournamentId}"].tournament-games-content`);
            if (targetContent) {
                targetContent.style.display = 'block';
            }
        }

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        function continueAsGuest() {
    // Create a form and submit it
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("guest.login") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    document.body.appendChild(form);
    form.submit();
}
    </script>
</body>
</html>