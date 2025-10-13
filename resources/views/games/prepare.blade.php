@extends('layouts.app')

@section('title', 'Game Preparation - ' . $game->team1->team_name . ' vs ' . $game->team2->team_name)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
:root {
    --primary-blue: #2C7CF9;
    --secondary-blue: #4285f4;
    --light-blue: #E7F0FA;
    --border-color: #dee2e6;
    --text-dark: #212529;
    --text-muted: #6c757d;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
    --purple-gradient: linear-gradient(135deg, #8e2de2, #4a00e0);
}

/* Fix body to remove white space */
body {
    padding-top: 0 !important;
    margin: 0 !important;
}

.game-preparation {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f8f9fa;
    min-height: 100vh;
    margin-top: 0;
    padding-top: 0;
    padding-top: 2rem;
}

.game-header {
    background: var(--purple-gradient);
    color: white;
    padding: 2.5rem 1rem;
    box-shadow: 0 4px 20px rgba(142, 45, 226, 0.3);
    margin-top: 0;
}

.header-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

.teams-display {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    gap: 2rem;
}

.team-section {
    text-align: center;
    flex: 1;
}

.team-name {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.team-record {
    font-size: 1rem;
    opacity: 0.95;
}

.vs-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0 2rem;
    min-width: 150px;
}

.game-time {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 8px;
}

.game-status {
    font-size: 0.95rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.header-actions {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 1.5rem;
}

.back-btn {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    font-size: 0.95rem;
    font-weight: 600;
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.main-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
    padding-top: 2rem; /* Add explicit top padding */
}

.content-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.nav-tabs-custom {
    display: flex;
    background: #f8f9fa;
    border-bottom: 3px solid var(--border-color);
    margin: 0;
    padding: 0;
}

.nav-tab-custom {
    flex: 1;
    padding: 1.5rem 1rem;
    background: none;
    border: none;
    font-weight: 600;
    font-size: 1rem;
    color: var(--text-muted);
    transition: all 0.3s ease;
    cursor: pointer;
    border-bottom: 4px solid transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.nav-tab-custom.active {
    background: white;
    color: var(--primary-blue);
    border-bottom-color: var(--primary-blue);
}

.nav-tab-custom:hover:not(.active) {
    background: rgba(44, 124, 249, 0.08);
    color: var(--secondary-blue);
}

.tab-content-custom {
    padding: 2.5rem;
    min-height: 600px;
}

.tab-pane-custom {
    display: none !important;
}

.tab-pane-custom.active {
    display: block !important;
}

/* Enhanced Team Selection Styles */
.teams-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.team-card {
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    background: linear-gradient(135deg, #fafafa 0%, #ffffff 100%);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.team-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-color);
}

.team-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.team-selection-info {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    text-align: right;
    gap: 0.25rem;
}

.roster-count,
.starters-count {
    font-size: 1.1rem;
    font-weight: 700;
}

.roster-count {
    color: var(--primary-blue);
}

.starters-count {
    color: var(--success-color);
}

.team-selection-info small {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.selection-step {
    background: linear-gradient(135deg, rgba(44, 124, 249, 0.08) 0%, rgba(66, 133, 244, 0.05) 100%);
    border: 2px solid var(--primary-blue);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
}

.step-title {
    font-weight: 700;
    font-size: 1.2rem;
    color: var(--primary-blue);
    margin-bottom: 0.75rem;
}

.step-description {
    font-size: 0.95rem;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    line-height: 1.6;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
}

.step-toggle {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.step-btn {
    padding: 0.75rem 2rem;
    border: 2px solid var(--primary-blue);
    background: white;
    color: var(--primary-blue);
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.95rem;
    min-width: 200px;
}

.step-btn.active {
    background: var(--primary-blue);
    color: white;
    box-shadow: 0 4px 12px rgba(44, 124, 249, 0.3);
    transform: translateY(-2px);
}

.step-btn:hover:not(.active) {
    background: rgba(44, 124, 249, 0.1);
    transform: translateY(-1px);
}

.players-list {
    display: grid;
    gap: 0.75rem;
}

.player-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.player-item:hover {
    border-color: var(--secondary-blue);
    box-shadow: 0 2px 8px rgba(66, 133, 244, 0.1);
}

.player-checkbox {
    margin-right: 1rem;
    display: flex;
    align-items: center;
}

.player-select {
    width: 18px;
    height: 18px;
    accent-color: var(--primary-blue);
    cursor: pointer;
}

.starter-select {
    width: 18px;
    height: 18px;
    accent-color: var(--success-color);
    cursor: pointer;
}

.jersey-number {
    width: 32px;
    height: 32px;
    background: var(--primary-blue);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.player-info {
    flex: 1;
}

.player-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.player-position {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.player-badges {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.badge-captain {
    background: var(--warning-color);
    color: #212529;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-roster {
    background: var(--primary-blue);
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-starter {
    background: var(--success-color);
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
}

.player-item.in-roster {
    border-color: var(--primary-blue);
    background: rgba(44, 124, 249, 0.05);
}

.player-item.is-starter {
    border-color: var(--success-color);
    background: rgba(40, 167, 69, 0.05);
}

.player-item.in-roster .jersey-number {
    background: var(--primary-blue);
}

.player-item.is-starter .jersey-number {
    background: var(--success-color);
}

/* Game Actions */
.game-actions-section {
    background: #f8f9fa;
    padding: 2rem;
    border-top: 2px solid var(--border-color);
}

.actions-container {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.readiness-check {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.check-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.check-icon {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.check-icon.ready {
    background: var(--success-color);
    color: white;
}

.check-icon.not-ready {
    background: var(--warning-color);
    color: white;
}

.start-game-btn {
    background: linear-gradient(135deg, var(--success-color), #20c997);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 700;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.start-game-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.start-game-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.text-danger {
    color: var(--danger-color) !important;
}

/* Staff and Officials styles (keeping existing) */
.staff-overview {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%);
    border: 1px solid rgba(40, 167, 69, 0.2);
    border-left: 4px solid var(--success-color);
    padding: 2rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    position: relative;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.1);
}

.staff-assigned {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.staff-positions {
    display: grid;
    gap: 1.5rem;
}

.staff-position {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.staff-position::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--border-color);
    transition: all 0.3s ease;
}

.staff-position.assigned::before {
    background: linear-gradient(to bottom, var(--success-color), #20c997);
}

.staff-position:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: rgba(44, 124, 249, 0.3);
}

.staff-position.assigned {
    border-color: rgba(40, 167, 69, 0.3);
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.02) 0%, white 100%);
}

.position-info {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.position-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.position-title {
    font-weight: 700;
    font-size: 1rem;
    color: var(--text-dark);
    margin: 0;
}

.position-assigned {
    font-size: 0.9rem;
    color: var(--success-color);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.position-assigned::before {
    content: '✓';
    width: 18px;
    height: 18px;
    background: var(--success-color);
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
}

.position-unassigned {
    font-size: 0.9rem;
    color: var(--text-muted);
    font-style: italic;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.position-unassigned::before {
    content: '!';
    width: 18px;
    height: 18px;
    background: var(--warning-color);
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
}

.position-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 120px;
}

.btn-assign,
.btn-edit,
.btn-remove {
    border: none;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-assign {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
}

.btn-assign:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(44, 124, 249, 0.4);
}

.btn-assign::before {
    content: '➕';
}

.btn-edit {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
}

.btn-edit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
}

.btn-edit::before {
    content: '✏️';
}

.btn-remove {
    background: linear-gradient(135deg, var(--danger-color), #c82333);
    color: white;
}

.btn-remove:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
}

.btn-remove::before {
    content: '🗑️';
}

/* Officials Styles */
.officials-list {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.official-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: white;
    border: 2px solid rgba(40, 167, 69, 0.2);
    border-radius: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.1);
    position: relative;
    overflow: hidden;
}

.official-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, var(--success-color), #20c997);
}

.official-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.2);
}

.official-info {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.official-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.official-title {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-muted);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.official-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.official-status {
    display: flex;
    align-items: center;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.bg-success {
    background: linear-gradient(135deg, var(--success-color), #20c997);
    color: white;
}

.bg-success::before {
    content: '✓';
}

.bg-primary {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
}

.bg-primary::before {
    content: '👥';
}

.officials-grid {
    display: grid;
    gap: 2rem;
}

.official-input-group {
    position: relative;
}

.official-label {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.official-input {
    width: 100%;
    padding: 1rem 1.5rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.official-input:focus {
    outline: none;
    border-color: var(--primary-blue);
    box-shadow: 0 4px 20px rgba(44, 124, 249, 0.15);
    transform: translateY(-1px);
}

.official-input:valid {
    border-color: var(--success-color);
}

.btn.btn-outline-primary {
    background: transparent;
    border: 2px solid var(--primary-blue);
    color: var(--primary-blue);
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn.btn-outline-primary:hover {
    background: var(--primary-blue);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(44, 124, 249, 0.3);
}

.btn.btn-success {
    background: linear-gradient(135deg, var(--success-color), #20c997);
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: 10px;
    font-weight: 700;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.btn.btn-outline-secondary {
    background: transparent;
    border: 2px solid #6c757d;
    color: #6c757d;
    padding: 1rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-1px);
}

/* Responsive Design */
@media (max-width: 992px) {
    .teams-container {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .teams-display {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }

    .vs-section {
        padding: 1rem 0;
    }

    .nav-tabs-custom {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .nav-tab-custom {
        flex: 0 0 auto;
        min-width: 150px;
    }
}

@media (max-width: 768px) {
    .game-header {
        padding: 2rem 1rem;
    }

    .team-name {
        font-size: 1.4rem;
    }

    .game-time {
        font-size: 1.1rem;
    }

    .main-content {
        padding: 1.5rem 0.5rem;
    }

    .tab-content-custom {
        padding: 1.5rem;
    }

    .step-btn {
        min-width: auto;
        padding: 0.75rem 1.5rem;
    }

    .team-card {
        padding: 1rem;
    }

    .team-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .team-selection-info {
        align-items: flex-start;
        text-align: left;
    }

    .player-item {
        padding: 0.75rem 0.5rem;
    }

    .jersey-number {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
        margin-right: 0.75rem;
    }

    .player-name {
        font-size: 0.9rem;
    }

    .game-actions-section {
        padding: 1.5rem 1rem;
    }

    .actions-container {
        flex-direction: column;
        gap: 1.5rem;
    }

    .readiness-check {
        flex-direction: column;
        gap: 0.75rem;
        width: 100%;
    }

    .start-game-btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .nav-tabs-custom {
        flex-direction: column;
    }

    .nav-tab-custom {
        width: 100%;
        padding: 1rem;
    }

    .step-toggle {
        flex-direction: column;
    }

    .step-btn {
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="game-preparation">
    
    

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-card">
            <!-- Navigation Tabs -->
            <div class="nav-tabs-custom">
                <button class="nav-tab-custom active" data-tab="players" type="button">
                    <i class="bi bi-people"></i>
                    Players Selection
                </button>
                <button class="nav-tab-custom" data-tab="staff" type="button">
                    <i class="bi bi-person-gear"></i>
                    Team Staff
                </button>
                <button class="nav-tab-custom" data-tab="officials" type="button">
                    <i class="bi bi-person-badge"></i>
                    Game Officials
                </button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content-custom">
                <!-- Players Tab -->
                <div id="players-tab" class="tab-pane-custom active">
                    <!-- Selection Step Instructions -->
                    <!-- Selection Step Instructions -->
                    <div class="selection-step">
                        <div class="step-title">Player Selection Process</div>
                        <div class="step-description">
                            @if($game->isVolleyball())
                                First select all players for your game roster (minimum 6 required), then choose exactly 6
                                starters from the selected players. Remaining players will be available for substitution
                                during the game.
                            @else
                                First select all players for your game roster (minimum 5 required), then choose exactly 5
                                starters from the selected players. Remaining players will be available for substitution
                                during the game.
                            @endif
                        </div>
                        <div class="step-toggle">
                            <button class="step-btn active" id="roster-step-btn" data-step="roster">
                                Step 1: Select Roster
                            </button>
                            <button class="step-btn" id="starters-step-btn" data-step="starters">
                                Step 2: Choose Starters
                            </button>
                        </div>
                    </div>

                    <div class="teams-container">
                        <!-- Team 1 Players -->
                        <div class="team-card">
                            <div class="team-card-header">
                                <h3 class="team-title">{{ $game->team1->team_name }}</h3>
                                <div>
                                    <button type="button" id="selectAllBtnTeam1" class="btn btn-outline-primary btn-sm mt-2" onclick="toggleSelectAll('team1')">Select All</button>
                                </div>
                                <div class="team-selection-info">
                                    <span class="roster-count" id="team1-roster">0 selected</span>
                                    <small class="text-muted">roster players</small>
                                    <span class="starters-count" id="team1-starters">0/{{ $game->isVolleyball() ? 6 : 5 }}</span>
<small class="text-muted">starters chosen</small>
                                </div>
                            </div>

                            <div class="players-list">
                                @forelse($game->team1->players as $player)
                                    <div class="player-item" data-team="team1" data-player-id="{{ $player->id }}">
                                        <div class="player-checkbox">
                                            <!-- Roster Selection Checkbox -->
                                            <input type="checkbox" class="player-select roster-select"
                                                id="roster1_{{ $player->id }}" data-team="team1"
                                                onchange="handleRosterSelection(this)" style="display: block;">
                                            <!-- Starter Selection Checkbox -->
                                            <input type="checkbox" class="starter-select"
                                                id="starter1_{{ $player->id }}" data-team="team1"
                                                onchange="handleStarterSelection(this)" style="display: none;">
                                        </div>

                                        <div class="jersey-number">{{ $player->number ?? '00' }}</div>

                                        <div class="player-info">
                                            <div class="player-name">{{ $player->name }}</div>
                                            <div class="player-position">{{ $player->position ?? 'Player' }}</div>
                                        </div>

                                        <div class="player-badges">
                                            @if ($loop->first)
                                                
                                            @endif
                                            <span class="badge-roster" style="display: none;">✓ ROSTER</span>
                                            <span class="badge-starter" style="display: none;">✓ STARTER</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="bi bi-people" style="font-size: 2rem; opacity: 0.3;"></i>
                                        <p class="text-muted mt-2">No players assigned</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Team 2 Players -->
                        <div class="team-card">
                            <div class="team-card-header">
                                <h3 class="team-title">{{ $game->team2->team_name }}</h3>
                                <div>
                                    <button type="button" id="selectAllBtnTeam2" class="btn btn-outline-primary btn-sm mt-2" onclick="toggleSelectAll('team2')">Select All</button>
                                </div>
                                <div class="team-selection-info">
                                    <span class="roster-count" id="team2-roster">0 selected</span>
                                    <small class="text-muted">roster players</small>
                                    <span class="starters-count" id="team2-starters">0/{{ $game->isVolleyball() ? 6 : 5 }}</span>
<small class="text-muted">starters chosen</small>
                                </div>
                            </div>

                            <div class="players-list">
                                @forelse($game->team2->players as $player)
                                    <div class="player-item" data-team="team2" data-player-id="{{ $player->id }}">
                                        <div class="player-checkbox">
                                            <!-- Roster Selection Checkbox -->
                                            <input type="checkbox" class="player-select roster-select"
                                                id="roster2_{{ $player->id }}" data-team="team2"
                                                onchange="handleRosterSelection(this)" style="display: block;">
                                            <!-- Starter Selection Checkbox -->
                                            <input type="checkbox" class="starter-select"
                                                id="starter2_{{ $player->id }}" data-team="team2"
                                                onchange="handleStarterSelection(this)" style="display: none;">
                                        </div>

                                        <div class="jersey-number">{{ $player->number ?? '00' }}</div>

                                        <div class="player-info">
                                            <div class="player-name">{{ $player->name }}</div>
                                            <div class="player-position">{{ $player->position ?? 'Player' }}</div>
                                        </div>

                                        <div class="player-badges">
                                            @if ($loop->first)
                                                
                                            @endif
                                            <span class="badge-roster" style="display: none;">✓ ROSTER</span>
                                            <span class="badge-starter" style="display: none;">✓ STARTER</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="bi bi-people" style="font-size: 2rem; opacity: 0.3;"></i>
                                        <p class="text-muted mt-2">No players assigned</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
<script>
function toggleSelectAll(team) {
    var btn = document.getElementById(team === 'team1' ? 'selectAllBtnTeam1' : 'selectAllBtnTeam2');
    var checkboxes = document.querySelectorAll('.player-item[data-team="' + team + '"] .roster-select');
    var allChecked = Array.from(checkboxes).filter(cb => cb.style.display !== 'none').every(cb => cb.checked);
    if (!allChecked) {
        checkboxes.forEach(function(checkbox) {
            if (checkbox.style.display !== 'none') {
                checkbox.checked = true;
                if (typeof handleRosterSelection === 'function') {
                    handleRosterSelection(checkbox);
                }
            }
        });
        btn.textContent = 'Deselect All';
    } else {
        checkboxes.forEach(function(checkbox) {
            if (checkbox.style.display !== 'none') {
                checkbox.checked = false;
                if (typeof handleRosterSelection === 'function') {
                    handleRosterSelection(checkbox);
                }
            }
        });
        btn.textContent = 'Select All';
    }
}
</script>

                <!-- Team Staff Tab -->
                <div id="staff-tab" class="tab-pane-custom">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="teams-container">
                                <!-- Team 1 Coach -->
                                <div class="team-card">
                                    <div class="team-card-header">
                                        <h3 class="team-title">{{ $game->team1->team_name }} Coach</h3>
                                    </div>

                                    <div class="staff-overview">
                                        <p class="staff-assigned">Coach Status:
                                            <strong>{{ $game->team1->coach_name ? 'Assigned' : 'Not Assigned' }}</strong>
                                        </p>
                                    </div>

                                    <div class="staff-positions">
                                        <div class="staff-position {{ $game->team1->coach_name ? 'assigned' : '' }}">
                                            <div class="position-info">
                                                <div class="position-title">Head Coach</div>
                                                <div
                                                    class="{{ $game->team1->coach_name ? 'position-assigned' : 'position-unassigned' }}">
                                                    {{ $game->team1->coach_name ?? 'Not assigned' }}
                                                </div>
                                            </div>

                                            <div class="position-actions">
                                                @if ($game->team1->coach_name)
                                                    <button class="btn-edit">Edit</button>
                                                    <button class="btn-remove">Remove</button>
                                                @else
                                                    <button class="btn-assign">Assign</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Team 2 Coach -->
                                <div class="team-card">
                                    <div class="team-card-header">
                                        <h3 class="team-title">{{ $game->team2->team_name }} Coach</h3>
                                    </div>

                                    <div class="staff-overview">
                                        <p class="staff-assigned">Coach Status:
                                            <strong>{{ $game->team2->coach_name ? 'Assigned' : 'Not Assigned' }}</strong>
                                        </p>
                                    </div>

                                    <div class="staff-positions">
                                        <div class="staff-position {{ $game->team2->coach_name ? 'assigned' : '' }}">
                                            <div class="position-info">
                                                <div class="position-title">Head Coach</div>
                                                <div
                                                    class="{{ $game->team2->coach_name ? 'position-assigned' : 'position-unassigned' }}">
                                                    {{ $game->team2->coach_name ?? 'Not assigned' }}
                                                </div>
                                            </div>

                                            <div class="position-actions">
                                                @if ($game->team2->coach_name)
                                                    <button class="btn-edit">Edit</button>
                                                    <button class="btn-remove">Remove</button>
                                                @else
                                                    <button class="btn-assign">Assign</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Game Officials Tab -->
                <div id="officials-tab" class="tab-pane-custom">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="team-card">
                                <div class="team-card-header">
                                    <h3 class="team-title">
                                        <i class="bi bi-person-badge me-2"></i>
                                        Match Officials Assignment
                                    </h3>
                                </div>

                                <!-- Saved Officials Display -->
                                <div id="saved-officials-display"
                                    style="display: {{ $game->referee ? 'block' : 'none' }};">
                                    <div class="staff-overview">
                                        <p class="staff-assigned">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            Match Officials Assigned
                                        </p>
                                    </div>

                                    <div class="officials-list">
                                        <div class="official-item" id="main-referee-display">
                                            <div class="official-info">
                                                <div class="official-title">
                                                    <i class="bi bi-person-fill me-2"></i>
                                                    Main Referee
                                                </div>
                                                <div class="official-name" id="referee-name">{{ $game->referee }}
                                                </div>
                                            </div>

                                            <div class="official-status">
                                                <span class="badge bg-success">Assigned</span>
                                            </div>
                                        </div>

                                        <div class="official-item" id="assistant1-display"
                                            style="display: {{ $game->assistant_referee_1 ? 'flex' : 'none' }};">
                                            <div class="official-info">
                                                <div class="official-title">
                                                    <i class="bi bi-person me-2"></i>
                                                    Assistant Referee 1
                                                </div>
                                                <div class="official-name" id="assistant1-name">
                                                    {{ $game->assistant_referee_1 }}</div>
                                            </div>

                                            <div class="official-status">
                                                <span class="badge bg-primary">Assistant</span>
                                            </div>
                                        </div>

                                        <div class="official-item" id="assistant2-display"
                                            style="display: {{ $game->assistant_referee_2 ? 'flex' : 'none' }};">
                                            <div class="official-info">
                                                <div class="official-title">
                                                    <i class="bi bi-person me-2"></i>
                                                    Assistant Referee 2
                                                </div>
                                                <div class="official-name" id="assistant2-name">
                                                    {{ $game->assistant_referee_2 }}</div>
                                            </div>

                                            <div class="official-status">
                                                <span class="badge bg-primary">Assistant</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-outline-primary"
                                            id="edit-officials-btn">
                                            <i class="bi bi-pencil me-2"></i>
                                            Edit Officials
                                        </button>
                                    </div>
                                </div>

                                <!-- Officials Form -->
                                <div id="officials-form-container"
                                    style="display: {{ $game->referee ? 'none' : 'block' }};">
                                    <div class="staff-overview">
                                        <p class="staff-assigned">Assign referees who will officiate this match (At
                                            least 1 referee required)</p>
                                    </div>

                                    <form id="officialsForm">
                                        @csrf
                                        <div class="officials-grid">
                                            <div class="official-input-group">
                                                <label class="official-label">
                                                    <i class="bi bi-person-fill me-2"></i>
                                                    Main Referee <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="referee" class="official-input"
                                                    value="{{ $game->referee }}"
                                                    placeholder="Enter main referee name" required>
                                            </div>

                                            <div class="official-input-group">
                                                <label class="official-label">
                                                    <i class="bi bi-person me-2"></i>
                                                    Assistant Referee 1
                                                </label>
                                                <input type="text" name="assistant_referee_1"
                                                    class="official-input" value="{{ $game->assistant_referee_1 }}"
                                                    placeholder="Enter assistant referee name">
                                            </div>

                                            <div class="official-input-group">
                                                <label class="official-label">
                                                    <i class="bi bi-person me-2"></i>
                                                    Assistant Referee 2
                                                </label>
                                                <input type="text" name="assistant_referee_2"
                                                    class="official-input" value="{{ $game->assistant_referee_2 }}"
                                                    placeholder="Enter assistant referee name">
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-success btn-lg"
                                                id="save-officials-btn">
                                                <i class="bi bi-check2"></i>
                                                <span class="btn-text">Save Match Officials</span>
                                            </button>

                                            <button type="button" class="btn btn-outline-secondary btn-lg ms-2"
                                                id="cancel-edit-btn" style="display: none;">
                                                <i class="bi bi-x"></i>
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Game Actions Section -->
        <div class="game-actions-section">
            <div class="actions-container">
                <div class="readiness-check">
                    <div class="check-item">
                        <div class="check-icon not-ready" id="team1-roster-check">!</div>
                        <span>Team 1: Roster & Starters</span>
                    </div>
                    <div class="check-item">
                        <div class="check-icon not-ready" id="team2-roster-check">!</div>
                        <span>Team 2: Roster & Starters</span>
                    </div>
                    <div class="check-item">
                        <div class="check-icon not-ready" id="officials-check">!</div>
                        <span>Referee Assigned</span>
                    </div>
                </div>

                <form action="{{ route('games.start-live', $game) }}" method="POST" id="startGameForm">
                    @csrf
                    <input type="hidden" name="team1_roster" id="team1_roster_input">
                    <input type="hidden" name="team2_roster" id="team2_roster_input">
                    <input type="hidden" name="team1_starters" id="team1_starters_input">
                    <input type="hidden" name="team2_starters" id="team2_starters_input">

                    <button type="submit" class="start-game-btn" id="startGameBtn" disabled>
                        <i class="bi bi-play-circle-fill me-2"></i>
                        Start Live Game
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Game state tracking
    var gameState = {
        currentStep: 'roster',
        selectedRoster: {
            team1: [],
            team2: []
        },
        selectedStarters: {
            team1: [],
            team2: []
        }
    };

    

    // Officials state
    var officialsData = {
        referee: '{{ $game->referee }}',
        assistant_referee_1: '{{ $game->assistant_referee_1 }}',
        assistant_referee_2: '{{ $game->assistant_referee_2 }}'
    };

    var requiredStarters = {{ $game->isVolleyball() ? 6 : 5 }};
   var minRosterSize = {{ $game->isVolleyball() ? 6 : 5 }};

    // Tab functionality
    window.addEventListener('load', function() {
        console.log('Page loaded - initializing tabs');
        var tabButtons = document.querySelectorAll('.nav-tab-custom');
        console.log('Found tab buttons:', tabButtons.length);

        tabButtons.forEach(function(button, index) {
            console.log('Setting up button ' + (index + 1) + ':', button.getAttribute('data-tab'));
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var targetTab = this.getAttribute('data-tab');
                console.log('Tab clicked:', targetTab);

                // Remove active from all buttons
                tabButtons.forEach(function(btn) {
                    btn.classList.remove('active');
                });

                // Remove active from all panes
                var allPanes = document.querySelectorAll('.tab-pane-custom');
                allPanes.forEach(function(pane) {
                    pane.classList.remove('active');
                });

                // Add active to clicked button
                this.classList.add('active');

                // Show target pane
                var targetPane = document.getElementById(targetTab + '-tab');
                if (targetPane) {
                    targetPane.classList.add('active');
                    console.log('Successfully switched to:', targetTab);
                } else {
                    console.error('Tab pane not found:', targetTab + '-tab');
                }
            });
        });

        console.log('Tab initialization complete');

        // Setup step buttons
        setupStepButtons();

        // Setup officials form handler
        setupOfficialsForm();

        // Initial readiness check
        updateReadinessChecks();
    });

    // Step functionality
    function setupStepButtons() {
        var rosterStepBtn = document.getElementById('roster-step-btn');
        var startersStepBtn = document.getElementById('starters-step-btn');

        if (rosterStepBtn) {
            rosterStepBtn.addEventListener('click', function() {
                switchToStep('roster');
            });
        }

        if (startersStepBtn) {
            startersStepBtn.addEventListener('click', function() {
                switchToStep('starters');
            });
        }
    }

    function switchToStep(step) {
        gameState.currentStep = step;

        // Update step button states
        var rosterBtn = document.getElementById('roster-step-btn');
        var startersBtn = document.getElementById('starters-step-btn');

        rosterBtn.classList.remove('active');
        startersBtn.classList.remove('active');

        if (step === 'roster') {
            rosterBtn.classList.add('active');
            showRosterCheckboxes();
        } else {
            startersBtn.classList.add('active');
            showStarterCheckboxes();
        }
    }

    function showRosterCheckboxes() {
        document.querySelectorAll('.roster-select').forEach(function(checkbox) {
            checkbox.style.display = 'block';
        });
        document.querySelectorAll('.starter-select').forEach(function(checkbox) {
            checkbox.style.display = 'none';
        });
        // Show select/deselect all buttons
        var btn1 = document.getElementById('selectAllBtnTeam1');
        var btn2 = document.getElementById('selectAllBtnTeam2');
        if (btn1) btn1.style.display = '';
        if (btn2) btn2.style.display = '';
    }

    function showStarterCheckboxes() {
        document.querySelectorAll('.roster-select').forEach(function(checkbox) {
            checkbox.style.display = 'none';
        });

        document.querySelectorAll('.starter-select').forEach(function(checkbox) {
            var playerId = checkbox.id;
            var team = checkbox.getAttribute('data-team');

            if (gameState.selectedRoster[team].includes(playerId.replace('starter1_', '').replace('starter2_', ''))) {
                checkbox.style.display = 'block';
            } else {
                checkbox.style.display = 'none';
            }
        });
        // Hide select/deselect all buttons
        var btn1 = document.getElementById('selectAllBtnTeam1');
        var btn2 = document.getElementById('selectAllBtnTeam2');
        if (btn1) btn1.style.display = 'none';
        if (btn2) btn2.style.display = 'none';
    }

    function handleRosterSelection(checkbox) {
        var team = checkbox.getAttribute('data-team');
        var playerId = checkbox.id.replace('roster1_', '').replace('roster2_', '');
        var playerItem = checkbox.closest('.player-item');
        var rosterBadge = playerItem.querySelector('.badge-roster');

        if (checkbox.checked) {
            gameState.selectedRoster[team].push(playerId);
            playerItem.classList.add('in-roster');
            rosterBadge.style.display = 'inline-block';
        } else {
            var index = gameState.selectedRoster[team].indexOf(playerId);
            if (index > -1) {
                gameState.selectedRoster[team].splice(index, 1);
            }
            playerItem.classList.remove('in-roster');
            rosterBadge.style.display = 'none';

            var starterIndex = gameState.selectedStarters[team].indexOf(playerId);
            if (starterIndex > -1) {
                gameState.selectedStarters[team].splice(starterIndex, 1);
                playerItem.classList.remove('is-starter');
                playerItem.querySelector('.badge-starter').style.display = 'none';
                document.getElementById('starter' + (team === 'team1' ? '1' : '2') + '_' + playerId).checked = false;
            }
        }

        updateRosterCounter(team);
        updateReadinessChecks();
        // Removed auto-switch to staff tab from roster selection
    }

    function handleStarterSelection(checkbox) {
    var team = checkbox.getAttribute('data-team');
    var playerId = checkbox.id.replace('starter1_', '').replace('starter2_', '');
    var playerItem = checkbox.closest('.player-item');
    var starterBadge = playerItem.querySelector('.badge-starter');

    if (checkbox.checked) {
        // UPDATED: Check against dynamic requiredStarters
        if (gameState.selectedStarters[team].length >= requiredStarters) {
            checkbox.checked = false;
            alert('You can only select ' + requiredStarters + ' starters per team!');
            return;
        }

        gameState.selectedStarters[team].push(playerId);
        playerItem.classList.add('is-starter');
        starterBadge.style.display = 'inline-block';
    } else {
        var index = gameState.selectedStarters[team].indexOf(playerId);
        if (index > -1) {
            gameState.selectedStarters[team].splice(index, 1);
        }
        playerItem.classList.remove('is-starter');
        starterBadge.style.display = 'none';
    }

    updateStartersCounter(team);
    updateReadinessChecks();
    
    // UPDATED: Check if both teams have required starters
    setTimeout(function() {
        var team1Count = document.querySelectorAll('.player-item[data-team="team1"] .starter-select:checked').length;
        var team2Count = document.querySelectorAll('.player-item[data-team="team2"] .starter-select:checked').length;
        if (team1Count === requiredStarters && team2Count === requiredStarters) {
            animateTabSwitch('staff');
        }
    }, 200);
}

// Animation for tab switch
function animateTabSwitch(tabName) {
    var tabContent = document.querySelector('.tab-content-custom');
    if (tabContent) {
        tabContent.style.transition = 'opacity 0.4s';
        tabContent.style.opacity = '0';
        setTimeout(function() {
            switchToTab(tabName);
            tabContent.style.opacity = '1';
        }, 400);
    } else {
        switchToTab(tabName);
    }
}

function switchToTab(tabName) {
    // Remove active from all tab buttons
    var tabButtons = document.querySelectorAll('.nav-tab-custom');
    tabButtons.forEach(function(btn) {
        btn.classList.remove('active');
    });
    // Remove active from all tab panes
    var allPanes = document.querySelectorAll('.tab-pane-custom');
    allPanes.forEach(function(pane) {
        pane.classList.remove('active');
    });
    // Activate the target tab and pane
    var targetBtn = document.querySelector('.nav-tab-custom[data-tab="' + tabName + '"]');
    var targetPane = document.getElementById(tabName + '-tab');
    if (targetBtn) targetBtn.classList.add('active');
    if (targetPane) targetPane.classList.add('active');
}

    function updateRosterCounter(team) {
        var counter = document.getElementById(team + '-roster');
        if (counter) {
            var totalSelected = gameState.selectedRoster[team].length;
            counter.textContent = totalSelected + ' selected';

            if (totalSelected >= 5) {
                counter.style.color = 'var(--success-color)';
            } else {
                counter.style.color = 'var(--primary-blue)';
            }
        }
    }

    function updateStartersCounter(team) {
    var counter = document.getElementById(team + '-starters');
    if (counter) {
        // UPDATED: Show dynamic required starters count
        counter.textContent = gameState.selectedStarters[team].length + '/' + requiredStarters;

        if (gameState.selectedStarters[team].length === requiredStarters) {
            counter.style.color = 'var(--success-color)';
        } else {
            counter.style.color = 'var(--warning-color)';
        }
    }
}

    function setupOfficialsForm() {
        var officialsForm = document.getElementById('officialsForm');
        var editBtn = document.getElementById('edit-officials-btn');
        var cancelBtn = document.getElementById('cancel-edit-btn');

        if (officialsForm) {
            officialsForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveOfficials();
            });
        }

        if (editBtn) {
            editBtn.addEventListener('click', function() {
                showOfficialsForm();
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                hideOfficialsForm();
            });
        }
    }

    function saveOfficials() {
        var form = document.getElementById('officialsForm');
        var saveBtn = document.getElementById('save-officials-btn');
        var formData = new FormData(form);

        saveBtn.classList.add('btn-loading');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="bi bi-spinner-border spinner-border-sm"></i> Saving...';

        fetch(`/games/{{ $game->id }}/officials`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    officialsData.referee = data.data.referee;
                    officialsData.assistant_referee_1 = data.data.assistant_referee_1;
                    officialsData.assistant_referee_2 = data.data.assistant_referee_2;

                    updateOfficialsDisplay();
                    hideOfficialsForm();
                    updateReadinessChecks();

                    showNotification('Officials saved successfully!', 'success');
                } else {
                    showNotification('Error saving officials: ' + (data.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error saving officials:', error);
                showNotification('Error saving officials. Please try again.', 'error');
            })
            .finally(() => {
                saveBtn.classList.remove('btn-loading');
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="bi bi-check2"></i><span class="btn-text">Save Match Officials</span>';
            });
    }

    function updateOfficialsDisplay() {
        document.getElementById('referee-name').textContent = officialsData.referee || 'Not assigned';

        var assistant1Display = document.getElementById('assistant1-display');
        var assistant1Name = document.getElementById('assistant1-name');
        if (officialsData.assistant_referee_1) {
            assistant1Name.textContent = officialsData.assistant_referee_1;
            assistant1Display.style.display = 'flex';
        } else {
            assistant1Display.style.display = 'none';
        }

        var assistant2Display = document.getElementById('assistant2-display');
        var assistant2Name = document.getElementById('assistant2-name');
        if (officialsData.assistant_referee_2) {
            assistant2Name.textContent = officialsData.assistant_referee_2;
            assistant2Display.style.display = 'flex';
        } else {
            assistant2Display.style.display = 'none';
        }
    }

    function showOfficialsForm() {
        document.getElementById('officials-form-container').style.display = 'block';
        document.getElementById('saved-officials-display').style.display = 'none';
        document.getElementById('cancel-edit-btn').style.display = 'inline-block';
    }

    function hideOfficialsForm() {
        document.getElementById('officials-form-container').style.display = 'none';
        document.getElementById('saved-officials-display').style.display = 'block';
        document.getElementById('cancel-edit-btn').style.display = 'none';
    }

    function showNotification(message, type) {
        var notification = document.createElement('div');
        notification.className = 'alert alert-' + (type === 'success' ? 'success' : 'danger') +
            ' alert-dismissible fade show';
        notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = message +
            '<button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>';

        document.body.appendChild(notification);

        setTimeout(function() {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }

    // Add this to your existing JavaScript in prepare.blade.php

// Modify the form submission to route to correct sport
document.getElementById('startGameForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const gameId = {{ $game->id }};
    
    // Check sport type
    @if($game->isVolleyball())
        // For volleyball, redirect to volleyball live
        fetch(`/games/${gameId}/start-live`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = `/games/${gameId}/volleyball-live`;
            } else {
                alert(data.message || 'Failed to start game');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to start game. Please try again.');
        });
    @else
        // For basketball, use normal submission
        this.submit();
    @endif
});

    function updateReadinessChecks() {
    var team1Check = document.getElementById('team1-roster-check');
    var team2Check = document.getElementById('team2-roster-check');
    var officialsCheck = document.getElementById('officials-check');
    var startButton = document.getElementById('startGameBtn');

    var team1RosterInput = document.getElementById('team1_roster_input');
    var team2RosterInput = document.getElementById('team2_roster_input');
    var team1StartersInput = document.getElementById('team1_starters_input');
    var team2StartersInput = document.getElementById('team2_starters_input');

    // UPDATED: Check against dynamic minRosterSize and requiredStarters
    var team1Ready = gameState.selectedRoster.team1.length >= minRosterSize &&
        gameState.selectedStarters.team1.length === requiredStarters;

    if (team1Ready) {
        team1Check.classList.remove('not-ready');
        team1Check.classList.add('ready');
        team1Check.textContent = '✓';
    } else {
        team1Check.classList.remove('ready');
        team1Check.classList.add('not-ready');
        team1Check.textContent = '!';
    }

    // UPDATED: Check against dynamic minRosterSize and requiredStarters
    var team2Ready = gameState.selectedRoster.team2.length >= minRosterSize &&
        gameState.selectedStarters.team2.length === requiredStarters;

    if (team2Ready) {
        team2Check.classList.remove('not-ready');
        team2Check.classList.add('ready');
        team2Check.textContent = '✓';
    } else {
        team2Check.classList.remove('ready');
        team2Check.classList.add('not-ready');
        team2Check.textContent = '!';
    }

    var hasReferee = officialsData.referee && officialsData.referee.trim() !== '';

    if (hasReferee) {
        officialsCheck.classList.remove('not-ready');
        officialsCheck.classList.add('ready');
        officialsCheck.textContent = '✓';
    } else {
        officialsCheck.classList.remove('ready');
        officialsCheck.classList.add('not-ready');
        officialsCheck.textContent = '!';
    }

    var allReady = team1Ready && team2Ready && hasReferee;

    if (allReady) {
        startButton.disabled = false;
        startButton.style.opacity = '1';

        if (team1RosterInput) team1RosterInput.value = JSON.stringify(gameState.selectedRoster.team1);
        if (team2RosterInput) team2RosterInput.value = JSON.stringify(gameState.selectedRoster.team2);
        if (team1StartersInput) team1StartersInput.value = JSON.stringify(gameState.selectedStarters.team1);
        if (team2StartersInput) team2StartersInput.value = JSON.stringify(gameState.selectedStarters.team2);
    } else {
        startButton.disabled = true;
        startButton.style.opacity = '0.6';
    }
}
</script>
@endpush