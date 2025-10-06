@extends('layouts.app')

@section('title', 'Dashboard')
@section('dashboard-active', 'active')

@push('styles')
    <style>
        :root {
            --primary-blue: #9d4edd;
            --secondary-blue: #7c3aed;
            --light-blue: #ffffff;
            --border-color: #e0c3fc;
            --text-dark: #2d0036;
            --text-muted: #a084ca;
            --background-light: #f8faff;
            --table-header: #e0c3fc;
            --hover-blue: #e9d8fd;
        }

        .dashboard-page {
            min-height: 100vh;
            background-color: var(--light-blue);
            padding: 2rem 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Tournament Selector */
        .tournament-selector {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
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
        }

        .tournament-tabs {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .tournament-tab {
            background: var(--light-blue);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 0.75rem 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 180px;
            white-space: nowrap;
            color: var(--text-dark);
        }

        .tournament-tab:hover {
            border-color: var(--primary-blue);
            transform: translateY(-2px);
            background: #e9d8fd;
        }

        .tournament-tab.active {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: #fff;
            border-color: var(--primary-blue);
        }

        .tab-name {
            font-weight: 600;
            font-size: 0.95rem;
            display: block;
        }

        .tab-sport {
            font-size: 0.75rem;
            opacity: 0.7;
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
        }

        /* Card Styles */
        .dashboard-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-blue);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
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
            padding: 1rem;
            background: var(--light-blue);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .ranking-item:hover {
            background: var(--hover-blue);
            transform: translateX(4px);
        }

        .rank-position {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            min-width: 50px;
            text-align: center;
        }

        .rank-position.gold {
            color: #FFD700;
        }

        .rank-position.silver {
            color: #C0C0C0;
        }

        .rank-position.bronze {
            color: #CD7F32;
        }

        .team-info {
            flex: 1;
            padding: 0 1rem;
        }

        .team-name {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .team-stats {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .team-record {
            display: flex;
            gap: 1rem;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .wins {
            color: #28a745;
        }

        .losses {
            color: #dc3545;
        }

        /* MVP Card */
        .mvp-card {
            text-align: center;
        }

        .mvp-avatar {
            width: 120px;
            height: 120px;
            margin: 1rem auto;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(157, 78, 221, 0.10);
        }

        .mvp-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .mvp-team {
            font-size: 0.95rem;
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
            background: var(--light-blue);
            padding: 1rem;
            border-radius: 10px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Recent Games */
        .recent-games {
            grid-column: 1 / -1;
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1rem;
        }

        .game-card {
            background: var(--light-blue);
            border-radius: 12px;
            padding: 1.25rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .game-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(157, 78, 221, 0.13);
            border-color: var(--primary-blue);
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
            color: var(--text-muted);
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
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .team-score {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .team-score.winner {
            color: #28a745;
        }

        .vs-divider {
            margin: 0 1rem;
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-muted);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-muted);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .recent-games {
                grid-column: 1;
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
        }

        /* MVP Carousel Styles */
        .mvp-carousel {
            position: relative;
            padding: 0 40px;
        }

        .mvp-carousel-container {
            position: relative;
            overflow: hidden;
            min-height: 400px;
        }

        .mvp-slide {
            display: none;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        .mvp-slide.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .mvp-game-info {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            font-style: italic;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary-blue);
            color: white;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(157, 78, 221, 0.3);
        }

        .carousel-btn:hover {
            background: var(--secondary-blue);
            transform: translateY(-50%) scale(1.1);
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
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--border-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator:hover {
            background: var(--text-muted);
            transform: scale(1.2);
        }

        .indicator.active {
            background: var(--primary-blue);
            width: 24px;
            border-radius: 4px;
        }

  
    </style>
@endpush

@section('content')
    <div class="dashboard-page">
        <div class="container">
            <!-- Tournament Selector -->
            <div class="tournament-selector">
                <div class="selector-header">
                    <h2 class="selector-title">
                        <i class="bi bi-trophy"></i> Select Tournament
                    </h2>
                </div>
                <div class="tournament-tabs">
                    @forelse($tournaments as $index => $tournament)
                        <button class="tournament-tab {{ $index === 0 ? 'active' : '' }}"
                            onclick="switchDashboard('{{ $tournament->id }}', this)"
                            data-tournament-id="{{ $tournament->id }}">
                            <span class="tab-name">{{ $tournament->name }}</span>
                            <span class="tab-sport">{{ $tournament->sport }} • {{ $tournament->division }}</span>
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
                                <i class="bi bi-bar-chart"></i> Team Rankings
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
                                    @foreach ($mvps as $index => $mvpStat)
                                        @php $mvpPlayer = $mvpStat->player; @endphp
                                        <div class="mvp-slide {{ $index === 0 ? 'active' : '' }}"
                                            data-index="{{ $index }}">
                                            <div class="mvp-avatar">
                                                {{ strtoupper(substr($mvpPlayer->name, 0, 2)) }}
                                            </div>
                                            <div class="mvp-name">{{ $mvpPlayer->name }}</div>
                                            <div class="mvp-team">{{ $mvpPlayer->team->team_name ?? 'N/A' }}</div>
                                            <div class="mvp-game-info">
                                                {{ $mvpStat->game->team1->team_name ?? 'TBD' }} vs
                                                {{ $mvpStat->game->team2->team_name ?? 'TBD' }}
                                            </div>

                                            <div class="mvp-stats">
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
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="carousel-btn next" onclick="nextMVP('{{ $tournament->id }}')">
                                    <i class="bi bi-chevron-right"></i>
                                </button>

                                <!-- Carousel Indicators -->
                                <div class="carousel-indicators">
                                    @foreach ($mvps as $index => $mvpStat)
                                        <span class="indicator {{ $index === 0 ? 'active' : '' }}"
                                            onclick="goToMVP('{{ $tournament->id }}', {{ $index }})"></span>
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
                                                    Final
                                                @elseif($game->status === 'in-progress')
                                                    Live
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

            // Hide current slide
            slides[currentMVPIndex[tournamentId]].classList.remove('active');
            indicators[currentMVPIndex[tournamentId]].classList.remove('active');

            // Move to next slide
            currentMVPIndex[tournamentId] = (currentMVPIndex[tournamentId] + 1) % slides.length;

            // Show next slide
            slides[currentMVPIndex[tournamentId]].classList.add('active');
            indicators[currentMVPIndex[tournamentId]].classList.add('active');
        }

        function prevMVP(tournamentId) {
            const carousel = document.querySelector(`.dashboard-grid[data-tournament-id="${tournamentId}"] .mvp-carousel`);
            if (!carousel) return;

            const slides = carousel.querySelectorAll('.mvp-slide');
            const indicators = carousel.querySelectorAll('.indicator');

            if (slides.length === 0) return;

            // Hide current slide
            slides[currentMVPIndex[tournamentId]].classList.remove('active');
            indicators[currentMVPIndex[tournamentId]].classList.remove('active');

            // Move to previous slide
            currentMVPIndex[tournamentId] = (currentMVPIndex[tournamentId] - 1 + slides.length) % slides.length;

            // Show previous slide
            slides[currentMVPIndex[tournamentId]].classList.add('active');
            indicators[currentMVPIndex[tournamentId]].classList.add('active');
        }

        function goToMVP(tournamentId, index) {
            const carousel = document.querySelector(`.dashboard-grid[data-tournament-id="${tournamentId}"] .mvp-carousel`);
            if (!carousel) return;

            const slides = carousel.querySelectorAll('.mvp-slide');
            const indicators = carousel.querySelectorAll('.indicator');

            if (slides.length === 0) return;

            // Hide current slide
            slides[currentMVPIndex[tournamentId]].classList.remove('active');
            indicators[currentMVPIndex[tournamentId]].classList.remove('active');

            // Go to specific slide
            currentMVPIndex[tournamentId] = index;

            // Show selected slide
            slides[currentMVPIndex[tournamentId]].classList.add('active');
            indicators[currentMVPIndex[tournamentId]].classList.add('active');
        }

        // Initialize carousel indices on page load
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
