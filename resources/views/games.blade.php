@extends('layouts.app')

@section('title', 'Games')
@section('games-active', 'active')

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

        .games-page {
            min-height: 100vh;
            background-color: var(--light-blue);
            padding: 2rem 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .page-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: #fff;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(157, 78, 221, 0.10);
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0.5rem 0 0 0;
        }

        /* Statistics Bar */
        .stats-bar {
            background: white;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        .filter-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-select {
            min-width: 160px;
            height: 44px;
            padding: 0 14px;
            border: 2px solid var(--border-color);
            background: white;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1);
        }

        /* Tournament Section */
        .tournament-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .tournament-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: #fff;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(157, 78, 221, 0.10);
        }

        .tournament-title {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .tournament-info {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .tournament-games {
            padding: 2rem;
        }

        /* Game Cards */
        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .game-card {
            background: var(--light-blue);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
        }

        .game-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(157, 78, 221, 0.13);
            border-color: var(--primary-blue);
        }

        .game-card.completed {
            border-left: 4px solid #28a745;
        }

        .game-card.in-progress {
            border-left: 4px solid #dc3545;
        }

        .game-card.scheduled {
            border-left: 4px solid #ffc107;
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

        .game-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .btn-primary {
            background: var(--primary-blue);
            color: #fff;
            box-shadow: 0 2px 8px rgba(157, 78, 221, 0.10);
        }

        .btn-secondary {
            background: var(--text-muted);
            color: #fff;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            opacity: 0.9;
            color: white;
            text-decoration: none;
        }

        /* Empty State */
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

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 0 0.5rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 24px;
            }

            .tournament-games {
                padding: 1.5rem;
            }

            .games-grid {
                grid-template-columns: 1fr;
            }

            .teams-matchup {
                flex-direction: column;
                gap: 1rem;
            }

            .vs-divider {
                margin: 0;
            }

            .filter-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                width: 100%;
            }

            .filter-select {
                width: 100%;
                min-width: auto;
            }

            .tournament-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .game-actions {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <div class="games-page">
        <div class="container">
            <!-- Page Header -->
            <div class="page-card">
                <div class="page-header">
                    <h1 class="page-title">All Games</h1>
                    <p class="page-subtitle">Complete tournament schedule and results</p>
                </div>

                <!-- Statistics Bar -->
                <div class="stats-bar">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number">{{ $totalGames }}</div>
                            <div class="stat-label">Total Games</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $liveGames }}</div>
                            <div class="stat-label">Live Games</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $completedGames }}</div>
                            <div class="stat-label">Completed</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $upcomingGames }}</div>
                            <div class="stat-label">Upcoming</div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="filter-controls">
                        <div class="filter-group">
                            <label for="sportFilter"><i class="bi bi-funnel"></i> Sport:</label>
                            <select id="sportFilter" class="filter-select">
                                <option value="">All Sports</option>
                                @foreach(\App\Models\Sport::all() as $sport)
                                    <option value="{{ $sport->sports_name }}">{{ $sport->sports_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="tournamentFilter">Tournament:</label>
                            <select id="tournamentFilter" class="filter-select">
                                <option value="">All Tournaments</option>
                                @foreach ($tournaments as $tournament)
                                    <option value="{{ $tournament->id }}">{{ $tournament->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tournament Sections -->
            @forelse($tournamentGames as $tournament)
                <div class="tournament-section" data-tournament-id="{{ $tournament->id }}">
                    <div class="tournament-header">
                        <div>
                            <h2 class="tournament-title">
                                <i class="bi bi-trophy"></i>
                                {{ $tournament->name }}
                            </h2>
                            <div class="tournament-info">
                            {{ $tournament->sport->sports_name ?? 'N/A' }} • {{ $tournament->division }} •                                {{ $tournament->games->count() }} Games
                            </div>
                        </div>
                        <div class="tournament-info">
                            @if ($tournament->start_date)
                                Started: {{ \Carbon\Carbon::parse($tournament->start_date)->format('M j, Y') }}
                            @else
                                Date: TBD
                            @endif
                        </div>
                    </div>

                    <div class="tournament-games">
                        @if ($tournament->games->count() > 0)
                            <div class="games-grid">
                                @foreach ($tournament->games as $game)
                                    <div class="game-card {{ $game->status }}" data-sport="{{ $tournament->sport->sports_name ?? '' }}"
                                        data-status="{{ $game->status }}" data-game-id="{{ $game->id }}">

                                        <div class="game-info">
                                            <span class="sport-tag">{{ $tournament->sport->sports_name ?? 'N/A' }}</span>
                                            <span class="game-status">
                                                @if ($game->status === 'completed')
                                                    Final
                                                @elseif($game->status === 'in-progress')
                                                    <span class="live-indicator">LIVE</span>
                                                    @if ($tournament->sport->sports_name === 'Basketball')
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

                                        @if ($game->status === 'completed')
                                            <div class="game-actions">
                                                <a href="{{ route('games.box-score', $game->id) }}"
                                                    class="btn-action btn-secondary">
                                                    <i class="bi bi-table"></i>
                                                    Box Score
                                                </a>
                                            </div>
                                        @elseif($game->status === 'in-progress')
                                            <div class="game-actions">
                                                <a href="{{ route('games.live', $game) }}" class="btn-action btn-primary">
                                                    <i class="bi bi-play-circle"></i> Live Scoresheet
                                                </a>
                                
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
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
                </div>
            @empty
                <div class="tournament-section">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <p><strong>No tournaments with games</strong></p>
                        <p>Games will appear here when tournaments are created and brackets are generated.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sportFilter = document.getElementById('sportFilter');
            const tournamentFilter = document.getElementById('tournamentFilter');

            function filterGames() {
                const selectedSport = sportFilter.value.toLowerCase();
                const selectedTournament = tournamentFilter.value;

                const gameCards = document.querySelectorAll('.game-card');
                const tournamentSections = document.querySelectorAll('.tournament-section');

                gameCards.forEach(card => {
                    const cardSport = card.dataset.sport.toLowerCase();
                    const tournamentSection = card.closest('.tournament-section');
                    const tournamentId = tournamentSection.dataset.tournamentId;

                    let showCard = true;

                    if (selectedSport && !cardSport.includes(selectedSport)) {
                        showCard = false;
                    }
            
                    if (selectedTournament && tournamentId !== selectedTournament) {
                        showCard = false;
                    }

                    card.style.display = showCard ? 'block' : 'none';
                });

                // Hide empty tournament sections
                tournamentSections.forEach(section => {
                    const hasVisibleCards = Array.from(section.querySelectorAll('.game-card')).some(card =>
                        card.style.display !== 'none'
                    );

                    section.style.display = hasVisibleCards ? 'block' : 'none';
                });
            }

            if (sportFilter) sportFilter.addEventListener('change', filterGames);
            if (tournamentFilter) tournamentFilter.addEventListener('change', filterGames);

            // Auto-refresh live scores
            function refreshLiveScores() {
                fetch('{{ route('api.live-scores') }}')
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(game => {
                            const gameCard = document.querySelector(`[data-game-id="${game.id}"]`);
                            if (gameCard) {
                                const team1Score = gameCard.querySelector(
                                    '.team:first-child .team-score');
                                const team2Score = gameCard.querySelector(
                                    '.team:last-child .team-score');
                                const gameStatus = gameCard.querySelector('.game-status');

                                if (team1Score) team1Score.textContent = game.team1.score;
                                if (team2Score) team2Score.textContent = game.team2.score;
                                if (gameStatus) gameStatus.innerHTML = game.status_display;
                            }
                        });
                    })
                    .catch(error => console.log('Error fetching live scores:', error));
            }

            // Start auto-refresh if there are live games
            @if ($liveGames > 0)
                setInterval(refreshLiveScores, 30000);
            @endif
        });

        function openTallySheet(gameId) {
    // Open the basketball scoresheet in a new window
    const url = `/games/${gameId}/basketball-scoresheet`;
    
    const tallysheeetWindow = window.open(
        url,
        'scoresheet',
        'width=1200,height=900,scrollbars=yes,resizable=yes'
    );

    if (tallysheeetWindow) {
        tallysheeetWindow.focus();
    } else {
        alert('Please allow popups for this site to view the tallysheet.');
    }
}
    </script>
@endsection
