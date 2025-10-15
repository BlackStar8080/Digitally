@extends('layouts.app')

@section('title', 'Dashboard')
@section('dashboard-active', 'active')

@push('styles')
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

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .dashboard-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 2rem 0;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Welcome Header */
        .welcome-header {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple), var(--accent-purple));
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 8px 32px rgba(157, 78, 221, 0.3);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }

        .welcome-header::before {
            content: '📊';
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 5rem;
            opacity: 0.2;
        }

        .welcome-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            position: relative;
            z-index: 1;
        }

        .welcome-header p {
            margin: 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        /* Tournament Selector */
        .tournament-selector {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            animation: fadeInUp 0.6s ease-out 0.1s backwards;
        }

        .selector-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .selector-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tournament-tabs {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .tournament-tab {
            background: var(--light-purple);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 200px;
            white-space: nowrap;
            color: var(--text-dark);
            position: relative;
            overflow: hidden;
        }

        .tournament-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .tournament-tab:hover::before {
            transform: scaleX(1);
        }

        .tournament-tab:hover {
            border-color: var(--primary-purple);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(157, 78, 221, 0.2);
        }

        .tournament-tab.active {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            color: #fff;
            border-color: var(--primary-purple);
            box-shadow: 0 4px 16px rgba(157, 78, 221, 0.3);
        }

        .tournament-tab.active::before {
            transform: scaleX(1);
            background: rgba(255, 255, 255, 0.3);
        }

        .tab-name {
            font-weight: 600;
            font-size: 1rem;
            display: block;
        }

        .tab-sport {
            font-size: 0.8rem;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: none;
        }

        .dashboard-grid.active {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            animation: fadeIn 0.4s ease-in;
        }

        /* Card Styles */
        .dashboard-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
        }

        .dashboard-card:hover {
            box-shadow: 0 8px 24px rgba(157, 78, 221, 0.12);
            transform: translateY(-4px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(157, 78, 221, 0.1);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title i {
            color: var(--primary-purple);
        }

        .card-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
            background: rgba(157, 78, 221, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
        }

        /* Team Rankings */
        .rankings-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .ranking-item {
            display: flex;
            align-items: center;
            padding: 1.25rem;
            background: linear-gradient(135deg, rgba(157, 78, 221, 0.05), rgba(124, 58, 237, 0.05));
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            animation: slideIn 0.4s ease-out;
        }

        .ranking-item:hover {
            background: var(--hover-purple);
            transform: translateX(8px);
            border-color: var(--primary-purple);
            box-shadow: 0 4px 12px rgba(157, 78, 221, 0.15);
        }

        .rank-position {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-purple);
            min-width: 60px;
            text-align: center;
            background: rgba(157, 78, 221, 0.1);
            padding: 0.5rem;
            border-radius: 12px;
        }

        .rank-position.gold {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #000;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
        }

        .rank-position.silver {
            background: linear-gradient(135deg, #c0c0c0, #e8e8e8);
            color: #000;
            box-shadow: 0 4px 12px rgba(192, 192, 192, 0.3);
        }

        .rank-position.bronze {
            background: linear-gradient(135deg, #cd7f32, #e89a5d);
            color: #fff;
            box-shadow: 0 4px 12px rgba(205, 127, 50, 0.3);
        }

        .team-info {
            flex: 1;
            padding: 0 1rem;
        }

        .team-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            color: var(--text-dark);
        }

        .team-stats {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .team-record {
            display: flex;
            gap: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
        }

        .wins {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
        }

        .losses {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
        }

        /* MVP Card */
        .mvp-card {
            text-align: center;
            animation: fadeInUp 0.6s ease-out 0.1s backwards;
        }

        .mvp-avatar {
            width: 120px;
            height: 120px;
            margin: 1rem auto;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 8px 24px rgba(157, 78, 221, 0.3);
            border: 4px solid rgba(157, 78, 221, 0.2);
            transition: all 0.3s ease;
        }

        .mvp-avatar:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 32px rgba(157, 78, 221, 0.4);
        }

        .mvp-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .mvp-team {
            font-size: 1rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .mvp-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .mvp-stat {
            background: linear-gradient(135deg, rgba(157, 78, 221, 0.1), rgba(124, 58, 237, 0.1));
            padding: 1.25rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .mvp-stat:hover {
            background: var(--hover-purple);
            transform: translateY(-4px);
            border-color: var(--primary-purple);
            box-shadow: 0 4px 12px rgba(157, 78, 221, 0.2);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.25rem;
        }

        /* Recent Games */
        .recent-games {
            grid-column: 1 / -1;
            animation: fadeInUp 0.6s ease-out 0.2s backwards;
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .game-card {
            background: linear-gradient(135deg, rgba(157, 78, 221, 0.05), rgba(124, 58, 237, 0.05));
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .game-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .game-card:hover::before {
            transform: scaleX(1);
        }

        .game-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(157, 78, 221, 0.15);
            border-color: var(--primary-purple);
        }

        .game-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .game-status {
            font-size: 0.85rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
        }

        .game-date {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .teams-matchup {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .team {
            text-align: center;
            flex: 1;
        }

        .team-name-small {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .team-score {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .team-score.winner {
            background: linear-gradient(135deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .vs-divider {
            margin: 0 1rem;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-muted);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-muted);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* MVP Carousel Styles */
        .mvp-carousel {
            position: relative;
            padding: 0 40px;
        }

        .mvp-carousel-container {
            position: relative;
            overflow: hidden;
            min-height: 450px;
        }

        .mvp-slide {
            display: none;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        .mvp-slide.active {
            display: block;
        }

        .mvp-game-info {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            font-style: italic;
            background: rgba(157, 78, 221, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(157, 78, 221, 0.3);
        }

        .carousel-btn:hover {
            background: linear-gradient(135deg, var(--secondary-purple), var(--accent-purple));
            transform: translateY(-50%) scale(1.15);
            box-shadow: 0 6px 16px rgba(157, 78, 221, 0.4);
        }

        .carousel-btn.prev {
            left: 0;
        }

        .carousel-btn.next {
            right: 0;
        }

        .carousel-indicators {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 1.5rem;
        }

        .indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(157, 78, 221, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator:hover {
            background: var(--text-muted);
            transform: scale(1.3);
        }

        .indicator.active {
            background: var(--primary-purple);
            width: 28px;
            border-radius: 5px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .dashboard-grid.active {
                grid-template-columns: 1fr;
            }

            .recent-games {
                grid-column: 1;
            }
        }

        @media (max-width: 768px) {
            .welcome-header h1 {
                font-size: 1.5rem;
            }

            .games-grid {
                grid-template-columns: 1fr;
            }

            .tournament-tabs {
                flex-direction: column;
            }

            .tournament-tab {
                width: 100%;
                min-width: auto;
            }

            .mvp-avatar {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }

            .mvp-stats {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .team-record {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-page">
        <div class="container">
            <!-- Welcome Header -->
            <div class="welcome-header">
                <h1>🏆 Dashboard</h1>
                <p>Monitor your tournaments, track team rankings, and celebrate top performers</p>
            </div>

            <!-- Tournament Selector -->
            <div class="tournament-selector">
                <div class="selector-header">
                    <h2 class="selector-title">
                        <i class="bi bi-trophy-fill"></i> Select Tournament
                    </h2>
                </div>
                <div class="tournament-tabs">
                    @forelse($tournaments as $index => $tournament)
                        <button class="tournament-tab {{ $index === 0 ? 'active' : '' }}"
                            onclick="switchDashboard('{{ $tournament->id }}', this)"
                            data-tournament-id="{{ $tournament->id }}">
                            <span class="tab-name">{{ $tournament->name }}</span>
                            <span class="tab-sport">{{ $tournament->sport_name }} • {{ $tournament->division }}</span>
                        </button>
                    @empty
                        <p style="color: var(--text-muted); padding: 1rem;">No tournaments available</p>
                    @endforelse
                </div>
            </div>

            <!-- Dashboard Content for Each Tournament -->
            @foreach ($tournaments as $index => $tournament)
                <div class="dashboard-grid {{ $index === 0 ? 'active' : '' }}" data-tournament-id="{{ $tournament->id }}">

                    <!-- Team Rankings -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-bar-chart-fill"></i> Team Rankings
                            </h3>
                        </div>

                        @if (isset($tournamentData[$tournament->id]['rankings']) && $tournamentData[$tournament->id]['rankings']->count() > 0)
                            <div class="rankings-list">
                                @foreach ($tournamentData[$tournament->id]['rankings'] as $ranking)
                                    <div class="ranking-item">
                                        <div
                                            class="rank-position {{ $ranking['position'] <= 3 ? ['gold', 'silver', 'bronze'][$ranking['position'] - 1] : '' }}">
                                            {{ $ranking['position'] }}
                                        </div>
                                        <div class="team-info">
                                            <div class="team-name">{{ $ranking['team']->team_name }}</div>
                                            <div class="team-stats">
                                                {{ $ranking['wins'] + $ranking['losses'] }} games played
                                            </div>
                                        </div>
                                        <div class="team-record">
                                            <span class="wins">{{ $ranking['wins'] }}W</span>
                                            <span class="losses">{{ $ranking['losses'] }}L</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon"><i class="bi bi-trophy"></i></div>
                                <p><strong>No rankings yet</strong></p>
                                <p>Rankings will appear when games are completed</p>
                            </div>
                        @endif
                    </div>

                    <!-- Recent MVP Carousel -->
                    <div class="dashboard-card mvp-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-star-fill"></i> Recent MVPs
                            </h3>
                        </div>

                        @if (isset($tournamentData[$tournament->id]['mvp']) && $tournamentData[$tournament->id]['mvp']->count() > 0)
                            @php $mvps = $tournamentData[$tournament->id]['mvp']; @endphp

                            <div class="mvp-carousel" data-tournament-id="{{ $tournament->id }}">
                                <!-- Carousel Navigation -->
                                <button class="carousel-btn prev" onclick="prevMVP('{{ $tournament->id }}')">
                                    <i class="bi bi-chevron-left"></i>
                                </button>

                                <div class="mvp-carousel-container">
                                    @foreach ($mvps as $mvpIndex => $mvpData)
    @php 
        $mvpPlayer = $mvpData['player'];
        $mvpStat = $mvpData['stats'];
        $isVolleyball = $mvpData['type'] === 'volleyball';
    @endphp
    <div class="mvp-slide {{ $mvpIndex === 0 ? 'active' : '' }}"
        data-index="{{ $mvpIndex }}">
        <div class="mvp-avatar">
            {{ strtoupper(substr($mvpPlayer->name, 0, 2)) }}
        </div>
        <div class="mvp-name">{{ $mvpPlayer->name }}</div>
        <div class="mvp-team">{{ $mvpPlayer->team->team_name ?? 'N/A' }}</div>
        <div class="mvp-game-info">
            {{ $mvpData['game']->team1->team_name ?? 'TBD' }} vs
            {{ $mvpData['game']->team2->team_name ?? 'TBD' }}
        </div>

        <div class="mvp-stats">
            @if($isVolleyball)
                <div class="mvp-stat">
                    <div class="stat-value">{{ $mvpStat->kills }}</div>
                    <div class="stat-label">Kills</div>
                </div>
                <div class="mvp-stat">
                    <div class="stat-value">{{ $mvpStat->aces }}</div>
                    <div class="stat-label">Aces</div>
                </div>
                <div class="mvp-stat">
                    <div class="stat-value">{{ $mvpStat->blocks }}</div>
                    <div class="stat-label">Blocks</div>
                </div>
                <div class="mvp-stat">
                    <div class="stat-value">{{ $mvpStat->assists }}</div>
                    <div class="stat-label">Assists</div>
                </div>
            @else
                <div class="mvp-stat">
                    <div class="stat-value">{{ $mvpStat->points }}</div>
                    <div class="stat-label">Points</div>
                </div>
                <div class="mvp-stat">
                    <div class="stat-value">{{ $mvpStat->rebounds }}</div>
                    <div class="stat-label">Rebounds</div>
                </div>
                <div class="mvp-stat">
                    <div class="stat-value">{{ $mvpStat->assists }}</div>
                    <div class="stat-label">Assists</div>
                </div>
                <div class="mvp-stat">
                    <div class="stat-value">{{ $mvpStat->steals }}</div>
                    <div class="stat-label">Steals</div>
                </div>
            @endif
        </div>
    </div>
@endforeach
                                </div>

                                <button class="carousel-btn next" onclick="nextMVP('{{ $tournament->id }}')">
                                    <i class="bi bi-chevron-right"></i>
                                </button>

                                <!-- Carousel Indicators -->
                                <div class="carousel-indicators">
                                    @foreach ($mvps as $mvpIndex => $mvpStat)
                                        <span class="indicator {{ $mvpIndex === 0 ? 'active' : '' }}"
                                            onclick="goToMVP('{{ $tournament->id }}', {{ $mvpIndex }})"></span>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon"><i class="bi bi-star"></i></div>
                                <p><strong>No MVP yet</strong></p>
                                <p>MVP will be shown after games</p>
                            </div>
                        @endif
                    </div>

                    <!-- Recent Games -->
                    <div class="dashboard-card recent-games">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-clock-history"></i> Recent Games
                            </h3>
                            <span class="card-subtitle">Last 6 games</span>
                        </div>

                        @if (isset($tournamentData[$tournament->id]['recentGames']) &&
                                $tournamentData[$tournament->id]['recentGames']->count() > 0)
                            <div class="games-grid">
                                @foreach ($tournamentData[$tournament->id]['recentGames'] as $game)
                                    <div class="game-card">
                                        <div class="game-header">
                                            <span class="game-status">
                                                @if ($game->status === 'completed')
                                                    ✓ Final
                                                @elseif($game->status === 'in-progress')
                                                    🔴 Live
                                                @else
                                                    Round {{ $game->round }}
                                                @endif
                                            </span>
                                            <span class="game-date">
                                                @if ($game->completed_at)
                                                    {{ \Carbon\Carbon::parse($game->completed_at)->format('M j') }}
                                                @elseif($game->scheduled_at)
                                                    {{ \Carbon\Carbon::parse($game->scheduled_at)->format('M j') }}
                                                @else
                                                    TBD
                                                @endif
                                            </span>
                                        </div>
                                        <div class="teams-matchup">
                                            <div class="team">
                                                <div class="team-name-small">{{ $game->team1->team_name ?? 'TBD' }}</div>
                                                <div
                                                    class="team-score {{ $game->winner_id === $game->team1_id ? 'winner' : '' }}">
                                                    {{ $game->team1_score ?? '--' }}
                                                </div>
                                            </div>
                                            <div class="vs-divider">VS</div>
                                            <div class="team">
                                                <div class="team-name-small">{{ $game->team2->team_name ?? 'TBD' }}</div>
                                                <div
                                                    class="team-score {{ $game->winner_id === $game->team2_id ? 'winner' : '' }}">
                                                    {{ $game->team2_score ?? '--' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon"><i class="bi bi-controller"></i></div>
                                <p><strong>No games yet</strong></p>
                                <p>Recent games will appear here</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let currentMVPIndex = {};

        function switchDashboard(tournamentId, clickedTab) {
            // Update tab buttons
            const allTabs = document.querySelectorAll('.tournament-tab');
            allTabs.forEach(tab => tab.classList.remove('active'));
            clickedTab.classList.add('active');

            // Update dashboard grids
            const allGrids = document.querySelectorAll('.dashboard-grid');
            allGrids.forEach(grid => grid.classList.remove('active'));

            const targetGrid = document.querySelector(`.dashboard-grid[data-tournament-id="${tournamentId}"]`);
            if (targetGrid) {
                targetGrid.classList.add('active');
            }

            // Initialize carousel index for this tournament
            if (!(tournamentId in currentMVPIndex)) {
                currentMVPIndex[tournamentId] = 0;
            }
        }

        function nextMVP(tournamentId) {
            const carousel = document.querySelector(`.dashboard-grid[data-tournament-id="${tournamentId}"] .mvp-carousel`);
            if (!carousel) return;

            const slides = carousel.querySelectorAll('.mvp-slide');
            const indicators = carousel.querySelectorAll('.indicator');

            if (slides.length === 0) return;

            slides[currentMVPIndex[tournamentId]].classList.remove('active');
            indicators[currentMVPIndex[tournamentId]].classList.remove('active');

            currentMVPIndex[tournamentId] = (currentMVPIndex[tournamentId] + 1) % slides.length;

            slides[currentMVPIndex[tournamentId]].classList.add('active');
            indicators[currentMVPIndex[tournamentId]].classList.add('active');
        }

        function prevMVP(tournamentId) {
            const carousel = document.querySelector(`.dashboard-grid[data-tournament-id="${tournamentId}"] .mvp-carousel`);
            if (!carousel) return;

            const slides = carousel.querySelectorAll('.mvp-slide');
            const indicators = carousel.querySelectorAll('.indicator');

            if (slides.length === 0) return;

            slides[currentMVPIndex[tournamentId]].classList.remove('active');
            indicators[currentMVPIndex[tournamentId]].classList.remove('active');

            currentMVPIndex[tournamentId] = (currentMVPIndex[tournamentId] - 1 + slides.length) % slides.length;

            slides[currentMVPIndex[tournamentId]].classList.add('active');
            indicators[currentMVPIndex[tournamentId]].classList.add('active');
        }

        function goToMVP(tournamentId, index) {
            const carousel = document.querySelector(`.dashboard-grid[data-tournament-id="${tournamentId}"] .mvp-carousel`);
            if (!carousel) return;

            const slides = carousel.querySelectorAll('.mvp-slide');
            const indicators = carousel.querySelectorAll('.indicator');

            if (slides.length === 0) return;

            slides[currentMVPIndex[tournamentId]].classList.remove('active');
            indicators[currentMVPIndex[tournamentId]].classList.remove('active');

            currentMVPIndex[tournamentId] = index;

            slides[currentMVPIndex[tournamentId]].classList.add('active');
            indicators[currentMVPIndex[tournamentId]].classList.add('active');
        }

        // Initialize carousel indices
        document.addEventListener('DOMContentLoaded', function() {
            const carousels = document.querySelectorAll('.mvp-carousel');
            carousels.forEach(carousel => {
                const tournamentId = carousel.getAttribute('data-tournament-id');
                currentMVPIndex[tournamentId] = 0;
            });
        });

        // Optional: Auto-advance carousel every 5 seconds
        setInterval(() => {
            const activeGrid = document.querySelector('.dashboard-grid.active');
            if (activeGrid) {
                const carousel = activeGrid.querySelector('.mvp-carousel');
                if (carousel) {
                    const tournamentId = carousel.getAttribute('data-tournament-id');
                    nextMVP(tournamentId);
                }
            }
        }, 5000);
    </script>
@endsection