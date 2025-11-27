@extends('layouts.app')

@section('title', 'Box Score - ' . $game->team1->team_name . ' vs ' . $game->team2->team_name)

@push('styles')
<style>
    /* Toast Notification Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast-notification {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 320px;
        max-width: 400px;
        opacity: 0;
        transform: translateX(400px);
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border-left: 4px solid #28a745;
    }

    .toast-notification.show {
        opacity: 1;
        transform: translateX(0);
    }

    .toast-notification.hide {
        opacity: 0;
        transform: translateX(400px);
    }

    .toast-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745, #20c997);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.25rem;
        font-size: 14px;
    }

    .toast-message {
        color: #6c757d;
        font-size: 13px;
        margin: 0;
    }

    .toast-close {
        background: none;
        border: none;
        color: #6c757d;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .toast-close:hover {
        background: #f0f0f0;
        color: #212529;
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

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .fade-in {
        animation: fadeIn 0.4s ease-in;
    }

    .box-score-page {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f5f5;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .box-score-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .game-title-header {
        text-align: center;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #9d4edd, #7c3aed, #5f2da8);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(157, 78, 221, 0.2);
        position: relative;
        overflow: hidden;
    }

    .game-title-header::before {
        content: '🏀';
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: 0.2;
    }

    .game-title-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .main-content {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1200px) {
        .main-content {
            grid-template-columns: 1fr;
        }
    }

    /* LEFT COLUMN - STATS */
    .stats-column {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        animation: fadeInUp 0.6s ease-out;
    }

    .team-section {
        margin-bottom: 2rem;
    }

    .team-header {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid #9d4edd;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .team-header::before {
        content: '👥';
        font-size: 1.5rem;
    }

    .team-stats-table {
        width: 100%;
        border-collapse: collapse;
    }

    .team-stats-table thead {
        background: linear-gradient(135deg, rgba(157, 78, 221, 0.1), rgba(124, 58, 237, 0.1));
    }

    .team-stats-table th {
        padding: 0.75rem;
        text-align: left;
        font-size: 0.8rem;
        font-weight: 700;
        color: #5f2da8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #9d4edd;
    }

    .team-stats-table th.stat-col {
        text-align: center;
        width: 60px;
    }

    .team-stats-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .team-stats-table td.stat-col {
        text-align: center;
        font-weight: 600;
        color: #333;
    }

    .team-stats-table tbody tr {
        transition: all 0.2s ease;
    }

    .team-stats-table tbody tr:hover {
        background: rgba(157, 78, 221, 0.05);
        transform: translateX(4px);
    }

    .player-name-col {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .player-number {
        background: linear-gradient(135deg, #9d4edd, #7c3aed);
        color: white;
        min-width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 0 0.5rem;
        box-shadow: 0 2px 8px rgba(157, 78, 221, 0.3);
    }

    .position-badge {
        background: rgba(157, 78, 221, 0.1);
        color: #7c3aed;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* RIGHT COLUMN - SCORE & MVP */
    .score-mvp-column {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .score-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        animation: fadeInUp 0.6s ease-out 0.1s backwards;
    }

    .league-badge {
        text-align: center;
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .league-badge::before {
        content: '🏆';
        font-size: 1.2rem;
    }

    .score-display {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-bottom: 1rem;
    }

    .team-score {
        text-align: center;
        transition: all 0.3s ease;
    }

    .team-score:hover {
        transform: scale(1.05);
    }

    .team-logo {
        width: 60px;
        height: 60px;
        margin: 0 auto 0.5rem;
        background: linear-gradient(135deg, rgba(157, 78, 221, 0.1), rgba(124, 58, 237, 0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(157, 78, 221, 0.2);
    }

    .team-name {
        font-size: 1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .score-number {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #9d4edd, #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .vs-text {
        font-size: 1.2rem;
        color: #999;
        font-weight: 600;
    }

    .final-badge {
        text-align: center;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 0.75rem;
        border-radius: 8px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    /* MVP CARD */
    .mvp-card {
        background: linear-gradient(135deg, #9d4edd 0%, #7c3aed 50%, #5f2da8 100%);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(157, 78, 221, 0.4);
        color: white;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.2s backwards;
    }

    .mvp-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .mvp-header {
        text-align: center;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .mvp-title {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        opacity: 0.9;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .mvp-title::before,
    .mvp-title::after {
        content: '⭐';
        font-size: 1rem;
    }

    .mvp-player-photo {
        width: 120px;
        height: 120px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
    }

    .mvp-player-name {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .mvp-player-team {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .mvp-stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .mvp-stat-item {
        text-align: center;
        background: rgba(255,255,255,0.15);
        padding: 1rem;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .mvp-stat-item:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-4px);
    }

    .mvp-stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .mvp-stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
    }

    /* MVP SELECTION SECTION */
    .mvp-selection-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease-out 0.3s backwards;
    }

    .mvp-selection-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .mvp-selection-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .mvp-selection-subtitle {
        color: #666;
        font-size: 0.95rem;
    }

    .mvp-candidates {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .mvp-candidate-card {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .mvp-candidate-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #9d4edd, #7c3aed);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .mvp-candidate-card:hover::before {
        transform: scaleX(1);
    }

    .mvp-candidate-card:hover {
        border-color: #9d4edd;
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(157, 78, 221, 0.2);
    }

    .mvp-candidate-card.selected {
        border-color: #ffd700;
        background: #fff9e6;
        box-shadow: 0 8px 24px rgba(255, 215, 0, 0.3);
    }

    .mvp-candidate-card.selected::before {
        background: linear-gradient(90deg, #ffd700, #ffed4e);
        transform: scaleX(1);
    }

    .candidate-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .candidate-number {
        background: linear-gradient(135deg, #9d4edd, #7c3aed);
        color: white;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        box-shadow: 0 4px 12px rgba(157, 78, 221, 0.3);
    }

    .candidate-info {
        flex: 1;
    }

    .candidate-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.25rem;
    }

    .candidate-team {
        font-size: 0.85rem;
        color: #666;
    }

    .candidate-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .candidate-stat {
        text-align: center;
        padding: 0.5rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .candidate-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #9d4edd, #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .candidate-stat-label {
        font-size: 0.75rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.25rem;
    }

    .select-mvp-btn {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #000;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: block;
        margin: 0 auto;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 16px rgba(255, 215, 0, 0.3);
    }

    .select-mvp-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(255, 215, 0, 0.5);
    }

    .select-mvp-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    /* BACK ACTIONS */
    .back-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        padding: 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        animation: fadeInUp 0.6s ease-out 0.4s backwards;
    }

    .action-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #9d4edd, #7c3aed);
        color: white;
        border: none;
        box-shadow: 0 2px 8px rgba(157, 78, 221, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #7c3aed, #5f2da8);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(157, 78, 221, 0.4);
        color: white;
    }

    .btn-secondary {
        background: #e0e0e0;
        color: #333;
        border: none;
    }

    .btn-secondary:hover {
        background: #d0d0d0;
        transform: translateY(-2px);
        color: #333;
    }

    .no-stats-message {
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .game-title-header h1 {
            font-size: 1.5rem;
        }

        .score-mvp-column {
            animation-delay: 0s;
        }

        .mvp-selection-section {
            animation-delay: 0s;
        }

        .back-actions {
            animation-delay: 0s;
            flex-wrap: wrap;
        }

        .action-btn {
            flex: 1;
            min-width: 150px;
        }

        .toast-container {
            left: 10px;
            right: 10px;
        }

        .toast-notification {
            min-width: auto;
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<!-- Toast Notification -->
<div class="toast-container">
    <div class="toast-notification" id="successToast">
        <div class="toast-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">Success!</div>
            <p class="toast-message" id="toastMessage">MVP has been selected successfully!</p>
        </div>
        <button class="toast-close" onclick="hideToast()">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>

<div class="box-score-page">
    <div class="box-score-container">
        <!-- Game Title -->
        <div class="game-title-header">
            <h1>
                {{ $game->bracket->tournament->name ?? 'Game' }}
                @if($game->round)
                    - {{ $game->getDisplayName() }}
                @endif
            </h1>
        </div>

        <!-- Main Two Column Layout -->
        <div class="main-content">
            <!-- LEFT COLUMN - STATISTICS -->
            <div class="stats-column">
                @if($team1Stats->count() > 0 || $team2Stats->count() > 0)
                    <!-- Team 1 Stats -->
                    <div class="team-section">
                        <h2 class="team-header">{{ strtoupper($game->team1->team_name) }}</h2>
                        @if($team1Stats->count() > 0)
                            <table class="team-stats-table">
                                <thead>
                                    <tr>
                                        <th>PLAYER</th>
                                        <th class="stat-col">POSITION</th>
                                        <th class="stat-col">POINTS</th>
                                        <th class="stat-col">ASSIST</th>
                                        <th class="stat-col">STEAL</th>
                                        <th class="stat-col">REBOUND</th>
                                        <th class="stat-col">FOUL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($team1Stats as $stat)
                                        <tr>
                                            <td>
                                                <div class="player-name-col">
                                                    <div class="player-number">{{ $stat->player->number ?? '0' }}</div>
                                                    <span>{{ $stat->player->name }}</span>
                                                </div>
                                            </td>
                                            <td class="stat-col">
                                                <span class="position-badge">{{ $stat->player->position ?? 'N/A' }}</span>
                                            </td>
                                            <td class="stat-col">{{ $stat->points }}</td>
                                            <td class="stat-col">{{ $stat->assists }}</td>
                                            <td class="stat-col">{{ $stat->steals }}</td>
                                            <td class="stat-col">{{ $stat->rebounds }}</td>
                                            <td class="stat-col">{{ $stat->fouls }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted text-center py-3">No statistics recorded</p>
                        @endif
                    </div>

                    <!-- Team 2 Stats -->
                    <div class="team-section">
                        <h2 class="team-header">{{ strtoupper($game->team2->team_name) }}</h2>
                        @if($team2Stats->count() > 0)
                            <table class="team-stats-table">
                                <thead>
                                    <tr>
                                        <th>PLAYER</th>
                                        <th class="stat-col">POSITION</th>
                                        <th class="stat-col">POINTS</th>
                                        <th class="stat-col">ASSIST</th>
                                        <th class="stat-col">STEAL</th>
                                        <th class="stat-col">REBOUND</th>
                                        <th class="stat-col">FOUL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($team2Stats as $stat)
                                        <tr>
                                            <td>
                                                <div class="player-name-col">
                                                    <div class="player-number">{{ $stat->player->number ?? '0' }}</div>
                                                    <span>{{ $stat->player->name }}</span>
                                                </div>
                                            </td>
                                            <td class="stat-col">
                                                <span class="position-badge">{{ $stat->player->position ?? 'N/A' }}</span>
                                            </td>
                                            <td class="stat-col">{{ $stat->points }}</td>
                                            <td class="stat-col">{{ $stat->assists }}</td>
                                            <td class="stat-col">{{ $stat->steals }}</td>
                                            <td class="stat-col">{{ $stat->rebounds }}</td>
                                            <td class="stat-col">{{ $stat->fouls }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted text-center py-3">No statistics recorded</p>
                        @endif
                    </div>
                @else
                    <div class="no-stats-message">
                        <p>No player statistics available for this game.</p>
                    </div>
                @endif
            </div>

            <!-- RIGHT COLUMN - SCORE & MVP -->
            <div class="score-mvp-column">
                <!-- Final Score Card -->
                <div class="score-card">
                    <div class="league-badge">
                        {{ $game->bracket->tournament->name ?? 'Summer League 2k25' }}
                    </div>
                    
                    <div class="score-display">
                        <div class="team-score">
                            <div class="team-logo">🏀</div>
                            <div class="team-name">{{ $game->team1->team_name }}</div>
                            <div class="score-number">{{ $game->team1_score ?? 0 }}</div>
                        </div>
                        
                        <div class="vs-text">VS</div>
                        
                        <div class="team-score">
                            <div class="team-logo">🏀</div>
                            <div class="team-name">{{ $game->team2->team_name }}</div>
                            <div class="score-number">{{ $game->team2_score ?? 0 }}</div>
                        </div>
                    </div>
                    
                    <div class="final-badge">FINAL</div>
                </div>

                <!-- MVP Card -->
                @php
                    $mvp = $game->getMVP();
                @endphp
                @if($mvp)
                <div class="mvp-card">
                    <div class="mvp-header">
                        <div class="mvp-title">Player of the Game</div>
                        <div class="mvp-player-photo">#{{ $mvp->player->number ?? '1' }}</div>
                        <div class="mvp-player-name">{{ strtoupper($mvp->player->name) }}</div>
                        <div class="mvp-player-team">{{ $mvp->team->team_name }}</div>
                    </div>
                    
                    <div class="mvp-stats-grid">
                        <div class="mvp-stat-item">
                            <div class="mvp-stat-value">{{ $mvp->points }}</div>
                            <div class="mvp-stat-label">Points</div>
                        </div>
                        <div class="mvp-stat-item">
                            <div class="mvp-stat-value">{{ $mvp->assists }}</div>
                            <div class="mvp-stat-label">Assists</div>
                        </div>
                        <div class="mvp-stat-item">
                            <div class="mvp-stat-value">{{ $mvp->rebounds }}</div>
                            <div class="mvp-stat-label">Rebounds</div>
                        </div>
                        <div class="mvp-stat-item">
                            <div class="mvp-stat-value">{{ $mvp->steals }}</div>
                            <div class="mvp-stat-label">Steals</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- MVP Selection Section (if not selected) -->
@if($team1Stats->count() > 0 || $team2Stats->count() > 0)
    <div class="mvp-selection-section" id="mvpSelectionSection" style="display: {{ $mvpSelected ? 'none' : 'block' }};">
                <div class="mvp-selection-header">
    <h2 class="mvp-selection-title">
        <i class="bi bi-star-fill" style="color: #ffd700;"></i>
        @if($mvpSelected)
            Change Match MVP
        @else
            Select Match MVP
        @endif
    </h2>
    <p class="mvp-selection-subtitle">
        @if($mvpSelected)
            Click on a different player to change the Most Valuable Player selection
        @else
            Click on a player to select them as the Most Valuable Player of this match
        @endif
    </p>
</div>

                <div class="mvp-candidates" id="mvpCandidates">
    @php
        // ✅ Get winning team ID
        $winningTeamId = null;
        if ($game->team1_score > $game->team2_score) {
            $winningTeamId = $game->team1_id;
        } elseif ($game->team2_score > $game->team1_score) {
            $winningTeamId = $game->team2_id;
        }
        
        // ✅ Filter candidates to only winning team
        $mvpCandidates = $winningTeamId 
            ? $team1Stats->merge($team2Stats)->where('team_id', $winningTeamId)->sortByDesc('points')->take(6)
            : $team1Stats->merge($team2Stats)->sortByDesc('points')->take(6);
    @endphp
                    @foreach($team1Stats->merge($team2Stats)->sortByDesc('points')->take(6) as $stat)
    <div class="mvp-candidate-card {{ $stat->is_mvp ? 'selected' : '' }}" data-stat-id="{{ $stat->id }}" onclick="selectMVPCandidate({{ $stat->id }})">
                            <div class="candidate-header">
                                <div class="candidate-number">{{ $stat->player->number ?? '0' }}</div>
                                <div class="candidate-info">
                                    <div class="candidate-name">{{ $stat->player->name }}</div>
                                    <div class="candidate-team">{{ $stat->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="candidate-stats">
                                <div class="candidate-stat">
                                    <div class="candidate-stat-value">{{ $stat->points }}</div>
                                    <div class="candidate-stat-label">Points</div>
                                </div>
                                <div class="candidate-stat">
                                    <div class="candidate-stat-value">{{ $stat->assists }}</div>
                                    <div class="candidate-stat-label">Assists</div>
                                </div>
                                <div class="candidate-stat">
                                    <div class="candidate-stat-value">{{ $stat->rebounds }}</div>
                                    <div class="candidate-stat-label">Rebounds</div>
                                </div>
                                <div class="candidate-stat">
                                    <div class="candidate-stat-value">{{ $stat->steals }}</div>
                                    <div class="candidate-stat-label">Steals</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="select-mvp-btn" id="confirmMVPBtn" onclick="confirmMVP()" {{ $mvpSelected ? '' : 'disabled' }}>
    <i class="bi bi-star-fill"></i>
    @if($mvpSelected)
        Update MVP Selection
    @else
        Confirm MVP Selection
    @endif
</button>
            </div>
        @endif

        <!-- Back Actions -->
<div class="back-actions">
    @if($game->bracket && $game->bracket->tournament)
        <a href="{{ route('tournaments.show', $game->bracket->tournament->id) }}" class="action-btn btn-primary">
            <i class="bi bi-trophy"></i>
            Back to Tournament
        </a>
    @endif
    <a href="{{ route('games.index') }}" class="action-btn btn-secondary">
        <i class="bi bi-list"></i>
        All Games
    </a>
    <a href="{{ route('games.tallysheet', $game->id) }}" class="action-btn btn-secondary">
        <i class="bi bi-clipboard-data"></i>
        View Tallysheet
    </a>
    @if($mvpSelected && ($team1Stats->count() > 0 || $team2Stats->count() > 0))
        <button onclick="showMVPSelection()" class="action-btn btn-secondary">
            <i class="bi bi-star"></i>
            Update MVP
        </button>
    @endif
</div>
    </div>
</div>

<script>
// Toast Notification Functions
function showToast(message) {
    const toast = document.getElementById('successToast');
    const toastMessage = document.getElementById('toastMessage');
    
    toastMessage.textContent = message;
    toast.classList.add('show');
    
    setTimeout(() => {
        hideToast();
    }, 4000);
}

function hideToast() {
    const toast = document.getElementById('successToast');
    toast.classList.remove('show');
    toast.classList.add('hide');
    
    setTimeout(() => {
        toast.classList.remove('hide');
    }, 400);
}

// ✅ NEW: Show MVP selection section
function showMVPSelection() {
    const mvpSection = document.getElementById('mvpSelectionSection');
    if (mvpSection) {
        mvpSection.style.display = 'block';
        mvpSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

let selectedMVPStatId = null;

function selectMVPCandidate(statId) {
    document.querySelectorAll('.mvp-candidate-card').forEach(card => {
        card.classList.remove('selected');
    });

    const selectedCard = document.querySelector(`[data-stat-id="${statId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('selected');
    }

    selectedMVPStatId = statId;
    document.getElementById('confirmMVPBtn').disabled = false;
}

function confirmMVP() {
    if (!selectedMVPStatId) {
        alert('Please select a player first');
        return;
    }

    const btn = document.getElementById('confirmMVPBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';

    let csrfToken = '';
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        csrfToken = metaTag.getAttribute('content');
    } else {
        csrfToken = '{{ csrf_token() }}';
    }

    fetch('/games/{{ $game->id }}/select-mvp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            player_stat_id: selectedMVPStatId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('🏆 MVP has been selected successfully!');
            btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> MVP Selected!';
            btn.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to select MVP');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while selecting MVP: ' + error.message);
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-star-fill"></i> Confirm MVP Selection';
        btn.style.background = 'linear-gradient(135deg, #ffd700, #ffed4e)';
    });
}
</script>
@endsection