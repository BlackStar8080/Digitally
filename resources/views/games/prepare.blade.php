@extends('layouts.app')

@section('title', 'Game Preparation - ' . $game->team1->team_name . ' vs ' . $game->team2->team_name)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
:root {
    --primary-purple: #9d4edd;
    --secondary-purple: #7c3aed;
    --accent-purple: #5f2da8;
    --light-purple: #ffffff;
    --border-color: #e5e7eb;
    --text-dark: #212529;
    --text-muted: #6c757d;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
    --background-light: #f8faff;
    --hover-purple: #ede9fe;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
    }
    50% {
        opacity: 0.7;
    }
}

body {
    padding-top: 0 !important;
    margin: 0 !important;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.game-preparation {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    min-height: 100vh;
    padding-top: 0;
}

/* Game Header */
.game-header {
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple), var(--accent-purple));
    color: white;
    padding: 2.5rem 1rem;
    box-shadow: 0 8px 32px rgba(157, 78, 221, 0.3);
    position: relative;
    overflow: hidden;
}

.game-header::before {
    position: absolute;
    right: 2rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 8rem;
    opacity: 0.1;
}

.header-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
    position: relative;
    z-index: 1;
}

.teams-display {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    gap: 2rem;
    animation: fadeInUp 0.6s ease-out;
}

.team-section {
    text-align: center;
    flex: 1;
}

.team-name {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.team-record {
    font-size: 1rem;
    opacity: 0.95;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    display: inline-block;
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
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
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

/* Main Content */
.main-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.content-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out 0.1s backwards;
}

/* Improved Tab Navigation */
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
    position: relative;
}

.nav-tab-custom::before {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.nav-tab-custom.active::before {
    transform: scaleX(1);
}

.nav-tab-custom.active {
    background: white;
    color: var(--primary-purple);
}

.nav-tab-custom:hover:not(.active) {
    background: rgba(157, 78, 221, 0.08);
    color: var(--secondary-purple);
}

.tab-content-custom {
    padding: 2.5rem;
    min-height: 600px;
}

.tab-pane-custom {
    display: none !important;
    animation: fadeInUp 0.4s ease-out;
}

.tab-pane-custom.active {
    display: block !important;
}

/* Selection Step - Enhanced */
.selection-step {
    background: linear-gradient(135deg, rgba(157, 78, 221, 0.08) 0%, rgba(124, 58, 237, 0.05) 100%);
    border: 2px solid var(--primary-purple);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.selection-step::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
}

.step-title {
    font-weight: 700;
    font-size: 1.3rem;
    color: var(--primary-purple);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.step-description {
    font-size: 1rem;
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
    border: 2px solid var(--primary-purple);
    background: white;
    color: var(--primary-purple);
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.95rem;
    min-width: 200px;
    position: relative;
    overflow: hidden;
}

.step-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: var(--primary-purple);
    transition: width 0.6s, height 0.6s, top 0.6s, left 0.6s;
    transform: translate(-50%, -50%);
    z-index: 0;
}

.step-btn.active::before {
    width: 300px;
    height: 300px;
}

.step-btn.active {
    color: white;
    box-shadow: 0 4px 12px rgba(157, 78, 221, 0.3);
    transform: translateY(-2px);
}

.step-btn span {
    position: relative;
    z-index: 1;
}

.step-btn:hover:not(.active) {
    background: rgba(157, 78, 221, 0.1);
    transform: translateY(-1px);
}

/* Team Cards - Enhanced */
.teams-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.team-card {
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    animation: slideIn 0.6s ease-out;
}

.team-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(157, 78, 221, 0.12);
}

.team-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid rgba(157, 78, 221, 0.1);
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
    transition: all 0.3s ease;
}

.roster-count {
    color: var(--primary-purple);
}

.starters-count {
    color: var(--success-color);
}

.team-selection-info small {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
}

/* Select All Button */
.btn-outline-primary {
    background: transparent;
    border: 2px solid var(--primary-purple);
    color: var(--primary-purple);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.btn-outline-primary:hover {
    background: var(--primary-purple);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(157, 78, 221, 0.3);
}

/* Player Items - Enhanced */
.players-list {
    display: grid;
    gap: 0.75rem;
}

.player-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: linear-gradient(135deg, rgba(157, 78, 221, 0.02), rgba(124, 58, 237, 0.02));
    border: 2px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.player-item:hover {
    border-color: var(--secondary-purple);
    box-shadow: 0 4px 12px rgba(157, 78, 221, 0.15);
    transform: translateX(8px);
}

.player-item.in-roster {
    border-color: var(--primary-purple);
    background: rgba(157, 78, 221, 0.05);
}

.player-item.is-starter {
    border-color: var(--success-color);
    background: rgba(40, 167, 69, 0.05);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
}

.player-checkbox {
    margin-right: 1rem;
    display: flex;
    align-items: center;
}

.player-select,
.starter-select {
    width: 20px;
    height: 20px;
    accent-color: var(--primary-purple);
    cursor: pointer;
}

.starter-select {
    accent-color: var(--success-color);
}

.jersey-number {
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.95rem;
    margin-right: 1rem;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(157, 78, 221, 0.3);
    transition: all 0.3s ease;
}

.player-item.is-starter .jersey-number {
    background: linear-gradient(135deg, var(--success-color), #20c997);
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.player-item:hover .jersey-number {
    transform: scale(1.1) rotate(5deg);
}

.player-info {
    flex: 1;
}

.player-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    font-size: 1rem;
    color: var(--text-dark);
}

.player-position {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.player-badges {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.badge-captain,
.badge-roster,
.badge-starter {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-captain {
    background: var(--warning-color);
    color: #212529;
}

.badge-roster {
    background: var(--primary-purple);
    color: white;
}

.badge-starter {
    background: var(--success-color);
    color: white;
    animation: pulse 2s infinite;
}

/* Game Actions Section */
.game-actions-section {
    background: white;
    padding: 2rem;
    border-top: 2px solid var(--border-color);
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
}

.actions-container {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.readiness-check {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.check-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.95rem;
    font-weight: 500;
    padding: 0.75rem 1rem;
    background: rgba(157, 78, 221, 0.05);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.check-item:hover {
    transform: translateY(-2px);
}

.check-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    font-weight: 700;
    transition: all 0.3s ease;
}

.check-icon.ready {
    background: var(--success-color);
    color: white;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.check-icon.not-ready {
    background: var(--warning-color);
    color: white;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}

.start-game-btn {
    background: linear-gradient(135deg, var(--success-color), #20c997);
    color: white;
    border: none;
    padding: 1.25rem 2.5rem;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 700;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.start-game-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.start-game-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
    opacity: 0.6;
}

/* Officials Section */
.staff-overview {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%);
    border: 1px solid rgba(40, 167, 69, 0.2);
    border-left: 4px solid var(--success-color);
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
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

.official-input-group {
    margin-bottom: 1.5rem;
}

.official-label {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.official-input {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.official-input:focus {
    outline: none;
    border-color: var(--primary-purple);
    box-shadow: 0 4px 12px rgba(157, 78, 221, 0.15);
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

    .game-header::before {
        font-size: 5rem;
    }

    .team-name {
        font-size: 1.5rem;
    }

    .main-content {
        padding: 1.5rem 0.5rem;
    }

    .tab-content-custom {
        padding: 1.5rem;
    }

    .actions-container {
        flex-direction: column;
        gap: 1.5rem;
    }

    .readiness-check {
        flex-direction: column;
        width: 100%;
    }

    .start-game-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="game-preparation">
    <!-- Game Header -->
    <div class="game-header">
        <div class="header-content">
            <div class="teams-display">
                <div class="team-section">
                    <div class="team-name">{{ $game->team1->team_name }}</div>
                    <div class="team-record">Home Team</div>
                </div>

                <div class="vs-section">
                    <div class="game-time">VS</div>
                    <div class="game-status">{{ ucfirst($game->status) }}</div>
                </div>

                <div class="team-section">
                    <div class="team-name">{{ $game->team2->team_name }}</div>
                    <div class="team-record">Away Team</div>
                </div>
            </div>

            <div class="header-actions">
                <a href="{{ route('dashboard') }}" class="back-btn">
                    <i class="bi bi-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

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
                    <div class="selection-step">
                        <div class="step-title">
                            <i class="bi bi-clipboard-check"></i>
                            Player Selection Process
                        </div>
                        <div class="step-description">
                            @if($game->isVolleyball())
                                First select all players for your game roster (minimum 6 required), then choose exactly 6
                                starters from the selected players.
                            @else
                                First select all players for your game roster (minimum 5 required), then choose exactly 5
                                starters from the selected players.
                            @endif
                        </div>
                        <div class="step-toggle">
                            <button class="step-btn active" id="roster-step-btn" data-step="roster">
                                <span>Step 1: Select Roster</span>
                            </button>
                            <button class="step-btn" id="starters-step-btn" data-step="starters">
                                <span>Step 2: Choose Starters</span>
                            </button>
                        </div>
                    </div>

                    <div class="teams-container">
                        <!-- Team 1 Players -->
                        <div class="team-card">
                            <div class="team-card-header">
                                <h3 class="team-title">{{ $game->team1->team_name }}</h3>
                                <div>
                                    <button type="button" id="selectAllBtnTeam1" class="btn btn-outline-primary btn-sm" onclick="toggleSelectAll('team1')">
                                        Select All
                                    </button>
                                </div>
                                <div class="team-selection-info">
                                    <span class="roster-count" id="team1-roster">0 selected</span>
                                    <small>roster players</small>
                                    <span class="starters-count" id="team1-starters">0/{{ $game->isVolleyball() ? 6 : 5 }}</span>
                                    <small>starters chosen</small>
                                </div>
                            </div>

                            <div class="players-list">
                                @forelse($game->team1->players as $player)
                                    <div class="player-item" data-team="team1" data-player-id="{{ $player->id }}">
                                        <div class="player-checkbox">
                                            <input type="checkbox" class="player-select roster-select"
                                                id="roster1_{{ $player->id }}" data-team="team1"
                                                onchange="handleRosterSelection(this)" style="display: block;">
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
                                            <span class="badge-roster" style="display: none;">✓ ROSTER</span>
                                            <span class="badge-starter" style="display: none;">⭐ STARTER</span>
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
                                    <button type="button" id="selectAllBtnTeam2" class="btn btn-outline-primary btn-sm" onclick="toggleSelectAll('team2')">
                                        Select All
                                    </button>
                                </div>
                                <div class="team-selection-info">
                                    <span class="roster-count" id="team2-roster">0 selected</span>
                                    <small>roster players</small>
                                    <span class="starters-count" id="team2-starters">0/{{ $game->isVolleyball() ? 6 : 5 }}</span>
                                    <small>starters chosen</small>
                                </div>
                            </div>

                            <div class="players-list">
                                @forelse($game->team2->players as $player)
                                    <div class="player-item" data-team="team2" data-player-id="{{ $player->id }}">
                                        <div class="player-checkbox">
                                            <input type="checkbox" class="player-select roster-select"
                                                id="roster2_{{ $player->id }}" data-team="team2"
                                                onchange="handleRosterSelection(this)" style="display: block;">
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
                                            <span class="badge-roster" style="display: none;">✓ ROSTER</span>
                                            <span class="badge-starter" style="display: none;">⭐ STARTER</span>
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
                                        <p class="staff-assigned">
                                            <i class="bi bi-{{ $game->team1->coach_name ? 'check-circle text-success' : 'exclamation-circle text-warning' }}"></i>
                                            Coach Status: <strong>{{ $game->team1->coach_name ? 'Assigned' : 'Not Assigned' }}</strong>
                                        </p>
                                    </div>

                                    @if($game->team1->coach_name)
                                        <div class="alert alert-success d-flex align-items-center" role="alert">
                                            <i class="bi bi-person-check-fill me-2" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <strong>Head Coach:</strong> {{ $game->team1->coach_name }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning" role="alert">
                                            <i class="bi bi-info-circle me-2"></i>
                                            No coach assigned for this team
                                        </div>
                                    @endif
                                </div>

                                <!-- Team 2 Coach -->
                                <div class="team-card">
                                    <div class="team-card-header">
                                        <h3 class="team-title">{{ $game->team2->team_name }} Coach</h3>
                                    </div>

                                    <div class="staff-overview">
                                        <p class="staff-assigned">
                                            <i class="bi bi-{{ $game->team2->coach_name ? 'check-circle text-success' : 'exclamation-circle text-warning' }}"></i>
                                            Coach Status: <strong>{{ $game->team2->coach_name ? 'Assigned' : 'Not Assigned' }}</strong>
                                        </p>
                                    </div>

                                    @if($game->team2->coach_name)
                                        <div class="alert alert-success d-flex align-items-center" role="alert">
                                            <i class="bi bi-person-check-fill me-2" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <strong>Head Coach:</strong> {{ $game->team2->coach_name }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning" role="alert">
                                            <i class="bi bi-info-circle me-2"></i>
                                            No coach assigned for this team
                                        </div>
                                    @endif
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
                                <div id="saved-officials-display" style="display: {{ $game->referee ? 'block' : 'none' }};">
                                    <div class="staff-overview">
                                        <p class="staff-assigned">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            Match Officials Assigned
                                        </p>
                                    </div>

                                    <div class="alert alert-success mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-person-fill me-2" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <strong>Main Referee:</strong>
                                                <span id="referee-name">{{ $game->referee }}</span>
                                            </div>
                                        </div>

                                        @if($game->assistant_referee_1)
                                            <div class="d-flex align-items-center mb-2" id="assistant1-display">
                                                <i class="bi bi-person me-2"></i>
                                                <div>
                                                    <strong>Assistant Referee 1:</strong>
                                                    <span id="assistant1-name">{{ $game->assistant_referee_1 }}</span>
                                                </div>
                                            </div>
                                        @endif

                                        @if($game->assistant_referee_2)
                                            <div class="d-flex align-items-center" id="assistant2-display">
                                                <i class="bi bi-person me-2"></i>
                                                <div>
                                                    <strong>Assistant Referee 2:</strong>
                                                    <span id="assistant2-name">{{ $game->assistant_referee_2 }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-outline-primary" id="edit-officials-btn">
                                            <i class="bi bi-pencil me-2"></i>
                                            Edit Officials
                                        </button>
                                    </div>
                                </div>

                                <!-- Officials Form -->
                                <div id="officials-form-container" style="display: {{ $game->referee ? 'none' : 'block' }};">
                                    <div class="staff-overview">
                                        <p class="staff-assigned">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Assign referees who will officiate this match (At least 1 referee required)
                                        </p>
                                    </div>

                                    <form id="officialsForm">
                                        @csrf
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
                                            <input type="text" name="assistant_referee_1" class="official-input"
                                                value="{{ $game->assistant_referee_1 }}"
                                                placeholder="Enter assistant referee name (optional)">
                                        </div>

                                        <div class="official-input-group">
                                            <label class="official-label">
                                                <i class="bi bi-person me-2"></i>
                                                Assistant Referee 2
                                            </label>
                                            <input type="text" name="assistant_referee_2" class="official-input"
                                                value="{{ $game->assistant_referee_2 }}"
                                                placeholder="Enter assistant referee name (optional)">
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-success btn-lg" id="save-officials-btn">
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
                        <i class="bi bi-play-circle-fill"></i>
                        Start Live Game
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

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

var officialsData = {
    referee: '{{ $game->referee }}',
    assistant_referee_1: '{{ $game->assistant_referee_1 }}',
    assistant_referee_2: '{{ $game->assistant_referee_2 }}'
};

var requiredStarters = {{ $game->isVolleyball() ? 6 : 5 }};
var minRosterSize = {{ $game->isVolleyball() ? 6 : 5 }};

// Tab functionality
window.addEventListener('load', function() {
    var tabButtons = document.querySelectorAll('.nav-tab-custom');

    tabButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var targetTab = this.getAttribute('data-tab');

            tabButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });

            var allPanes = document.querySelectorAll('.tab-pane-custom');
            allPanes.forEach(function(pane) {
                pane.classList.remove('active');
            });

            this.classList.add('active');

            var targetPane = document.getElementById(targetTab + '-tab');
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });

    setupStepButtons();
    setupOfficialsForm();
    updateReadinessChecks();
});

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
    var btn1 = document.getElementById('selectAllBtnTeam1');
    var btn2 = document.getElementById('selectAllBtnTeam2');
    if (btn1) btn1.style.display = 'none';
    if (btn2) btn2.style.display = 'none';
}

function toggleSelectAll(team) {
    var btn = document.getElementById(team === 'team1' ? 'selectAllBtnTeam1' : 'selectAllBtnTeam2');
    var checkboxes = document.querySelectorAll('.player-item[data-team="' + team + '"] .roster-select');
    var allChecked = Array.from(checkboxes).filter(cb => cb.style.display !== 'none').every(cb => cb.checked);
    
    if (!allChecked) {
        checkboxes.forEach(function(checkbox) {
            if (checkbox.style.display !== 'none') {
                checkbox.checked = true;
                handleRosterSelection(checkbox);
            }
        });
        btn.textContent = 'Deselect All';
    } else {
        checkboxes.forEach(function(checkbox) {
            if (checkbox.style.display !== 'none') {
                checkbox.checked = false;
                handleRosterSelection(checkbox);
            }
        });
        btn.textContent = 'Select All';
    }
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
}

function handleStarterSelection(checkbox) {
    var team = checkbox.getAttribute('data-team');
    var playerId = checkbox.id.replace('starter1_', '').replace('starter2_', '');
    var playerItem = checkbox.closest('.player-item');
    var starterBadge = playerItem.querySelector('.badge-starter');

    if (checkbox.checked) {
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
    
    setTimeout(function() {
        var team1Count = gameState.selectedStarters.team1.length;
        var team2Count = gameState.selectedStarters.team2.length;
        if (team1Count === requiredStarters && team2Count === requiredStarters) {
            animateTabSwitch('staff');
        }
    }, 200);
}

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
    var tabButtons = document.querySelectorAll('.nav-tab-custom');
    tabButtons.forEach(function(btn) {
        btn.classList.remove('active');
    });
    var allPanes = document.querySelectorAll('.tab-pane-custom');
    allPanes.forEach(function(pane) {
        pane.classList.remove('active');
    });
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

        if (totalSelected >= minRosterSize) {
            counter.style.color = 'var(--success-color)';
        } else {
            counter.style.color = 'var(--primary-purple)';
        }
    }
}

function updateStartersCounter(team) {
    var counter = document.getElementById(team + '-starters');
    if (counter) {
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

    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';

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
        if (assistant1Display) assistant1Display.style.display = 'flex';
    } else {
        if (assistant1Display) assistant1Display.style.display = 'none';
    }

    var assistant2Display = document.getElementById('assistant2-display');
    var assistant2Name = document.getElementById('assistant2-name');
    if (officialsData.assistant_referee_2) {
        assistant2Name.textContent = officialsData.assistant_referee_2;
        if (assistant2Display) assistant2Display.style.display = 'flex';
    } else {
        if (assistant2Display) assistant2Display.style.display = 'none';
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
    notification.className = 'alert alert-' + (type === 'success' ? 'success' : 'danger') + ' alert-dismissible fade show';
    notification.style.cssText = 'position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    notification.innerHTML = '<i class="bi bi-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' me-2"></i>' + message +
        '<button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>';

    document.body.appendChild(notification);

    setTimeout(function() {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}

document.getElementById('startGameForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const gameId = {{ $game->id }};
    
    @if($game->isVolleyball())
        // For volleyball, use AJAX
        fetch(`/games/${gameId}/start-live`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect_url;
            } else {
                alert(data.message || 'Failed to start game');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to start game. Please try again.');
        });
    @else
        // For basketball, submit normally
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

        if (team1RosterInput) team1RosterInput.value = JSON.stringify(gameState.selectedRoster.team1);
        if (team2RosterInput) team2RosterInput.value = JSON.stringify(gameState.selectedRoster.team2);
        if (team1StartersInput) team1StartersInput.value = JSON.stringify(gameState.selectedStarters.team1);
        if (team2StartersInput) team2StartersInput.value = JSON.stringify(gameState.selectedStarters.team2);
    } else {
        startButton.disabled = true;
    }
}
</script>
@endsection