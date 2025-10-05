@extends('layouts.app')

@section('title', 'Box Score - ' . $game->team1->team_name . ' vs ' . $game->team2->team_name)

@push('styles')
<style>
    .box-score-page {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f5f5;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .box-score-container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .game-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .game-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .final-score {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 3rem;
        margin-top: 1.5rem;
    }

    .team-score-box {
        text-align: center;
    }

    .team-score-name {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .team-score-value {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1;
    }

    .winner-badge {
        background: #ffd700;
        color: #000;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 0.5rem;
        display: inline-block;
    }

    .vs-separator {
        font-size: 1.5rem;
        opacity: 0.7;
    }

    .stats-section {
        padding: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e0e0e0;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
    }

    .team-stats-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
    }

    .team-stats-table thead {
        background: #f8f9fa;
    }

    .team-stats-table th {
        padding: 0.75rem;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
        color: #667eea;
    }

    .team-stats-table tbody tr:hover {
        background: #f8f9fa;
    }

    .player-name-col {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .player-number {
        background: #667eea;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .mvp-row {
        background: #fff9e6 !important;
        border-left: 4px solid #ffd700;
    }

    .mvp-badge {
        background: #ffd700;
        color: #000;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 0.5rem;
    }

    .mvp-selection-section {
        background: #f8f9fa;
        padding: 2rem;
        border-top: 2px solid #e0e0e0;
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
    }

    .mvp-selection-subtitle {
        color: #666;
        font-size: 0.95rem;
    }

    .mvp-candidates {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .mvp-candidate-card {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .mvp-candidate-card:hover {
        border-color: #667eea;
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.2);
    }

    .mvp-candidate-card.selected {
        border-color: #ffd700;
        background: #fff9e6;
        box-shadow: 0 6px 20px rgba(255, 215, 0, 0.3);
    }

    .candidate-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .candidate-number {
        background: #667eea;
        color: white;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
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
        background: #f8f9fa;
        border-radius: 8px;
    }

    .candidate-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
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
        margin: 2rem auto 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .select-mvp-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
    }

    .select-mvp-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .mvp-selected-banner {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #000;
        padding: 2rem;
        text-align: center;
        border-radius: 12px;
        margin: 2rem auto;
        max-width: 600px;
    }

    .mvp-selected-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .mvp-selected-player {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .back-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        padding: 2rem;
        border-top: 2px solid #e0e0e0;
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
        background: #667eea;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background: #5568d3;
        transform: translateY(-2px);
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

    .no-stats-icon {
        font-size: 3rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="box-score-page">
    <div class="box-score-container">
        <!-- Game Header -->
        <div class="game-header">
            <div class="game-title">
                <i class="bi bi-trophy"></i>
                {{ $game->bracket->tournament->name ?? 'Game' }}
                @if($game->round)
                    - {{ $game->getDisplayName() }}
                @endif
            </div>
            
            <div class="final-score">
                <div class="team-score-box">
                    <div class="team-score-name">{{ $game->team1->team_name }}</div>
                    <div class="team-score-value">{{ $game->team1_score ?? 0 }}</div>
                    @if($game->winner_id === $game->team1_id)
                        <div class="winner-badge">WINNER</div>
                    @endif
                </div>
                
                <div class="vs-separator">VS</div>
                
                <div class="team-score-box">
                    <div class="team-score-name">{{ $game->team2->team_name }}</div>
                    <div class="team-score-value">{{ $game->team2_score ?? 0 }}</div>
                    @if($game->winner_id === $game->team2_id)
                        <div class="winner-badge">WINNER</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Player Statistics -->
        <div class="stats-section">
            @if($team1Stats->count() > 0 || $team2Stats->count() > 0)
                <!-- Team 1 Stats -->
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="bi bi-people-fill"></i>
                        {{ $game->team1->team_name }} - Player Statistics
                    </h3>
                </div>

                @if($team1Stats->count() > 0)
                    <table class="team-stats-table">
    <thead>
        <tr>
            <th>Player</th>
            <th class="stat-col">PTS</th>
            <th class="stat-col">REB</th>
            <th class="stat-col">AST</th>
            <th class="stat-col">STL</th>
            <th class="stat-col">FOULS</th>
            <th class="stat-col">FG%</th>
        </tr>
    </thead>
    <tbody>
        @foreach($team1Stats as $stat)
            <tr class="{{ $stat->is_mvp ? 'mvp-row' : '' }}">
                <td>
                    <div class="player-name-col">
                        <div class="player-number">{{ $stat->player->number ?? '0' }}</div>
                        <span>{{ $stat->player->name }}</span>
                        @if($stat->is_mvp)
                            <span class="mvp-badge">MVP</span>
                        @endif
                    </div>
                </td>
                <td class="stat-col">{{ $stat->points }}</td>
                <td class="stat-col">{{ $stat->rebounds }}</td>
                <td class="stat-col">{{ $stat->assists }}</td>
                <td class="stat-col">{{ $stat->steals }}</td>
                <td class="stat-col">{{ $stat->fouls }}</td>
                <td class="stat-col">{{ $stat->getFieldGoalPercentage() }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
                @else
                    <p class="text-muted text-center py-3">No statistics recorded</p>
                @endif

                <!-- Team 2 Stats -->
                <div class="section-header" style="margin-top: 3rem;">
                    <h3 class="section-title">
                        <i class="bi bi-people-fill"></i>
                        {{ $game->team2->team_name }} - Player Statistics
                    </h3>
                </div>

                @if($team2Stats->count() > 0)
                    <table class="team-stats-table">
                        <thead>
                            <tr>
                                <th>Player</th>
                                <th class="stat-col">PTS</th>
                                <th class="stat-col">FOULS</th>
                                <th class="stat-col">FT%</th>
                                <th class="stat-col">FG%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($team2Stats as $stat)
                                <tr class="{{ $stat->is_mvp ? 'mvp-row' : '' }}">
                                    <td>
                                        <div class="player-name-col">
                                            <div class="player-number">{{ $stat->player->number ?? '0' }}</div>
                                            <span>{{ $stat->player->name }}</span>
                                            @if($stat->is_mvp)
                                                <span class="mvp-badge">MVP</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="stat-col">{{ $stat->points }}</td>
                                    <td class="stat-col">{{ $stat->fouls }}</td>
                                    <td class="stat-col">{{ $stat->getFreeThrowPercentage() }}%</td>
                                    <td class="stat-col">{{ $stat->getFieldGoalPercentage() }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted text-center py-3">No statistics recorded</p>
                @endif
            @else
                <div class="no-stats-message">
                    <div class="no-stats-icon">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                    <p>No player statistics available for this game.</p>
                </div>
            @endif
        </div>

        <!-- MVP Selection Section -->
        @if(!$mvpSelected && ($team1Stats->count() > 0 || $team2Stats->count() > 0))
            <div class="mvp-selection-section">
                <div class="mvp-selection-header">
                    <h2 class="mvp-selection-title">
                        <i class="bi bi-star-fill" style="color: #ffd700;"></i>
                        Select Match MVP
                    </h2>
                    <p class="mvp-selection-subtitle">Click on a player to select them as the Most Valuable Player of this match</p>
                </div>

                <div class="mvp-candidates" id="mvpCandidates">
                    @foreach($team1Stats->merge($team2Stats)->sortByDesc('points')->take(6) as $stat)
                        <div class="mvp-candidate-card" data-stat-id="{{ $stat->id }}" onclick="selectMVPCandidate({{ $stat->id }})">
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
                                    <div class="candidate-stat-value">{{ $stat->fouls }}</div>
                                    <div class="candidate-stat-label">Fouls</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="select-mvp-btn" id="confirmMVPBtn" onclick="confirmMVP()" disabled>
                    <i class="bi bi-star-fill"></i>
                    Confirm MVP Selection
                </button>
            </div>
        @elseif($mvpSelected)
            @php
                $mvp = $game->getMVP();
            @endphp
            @if($mvp)
                <div class="mvp-selection-section">
                    <div class="mvp-selected-banner">
                        <div class="mvp-selected-title">
                            <i class="bi bi-trophy-fill"></i>
                            Match MVP
                        </div>
                        <div class="mvp-selected-player">
                            #{{ $mvp->player->number ?? '0' }} {{ $mvp->player->name }}
                            <br>
                            <small style="opacity: 0.8;">{{ $mvp->team->team_name }} - {{ $mvp->points }} Points</small>
                        </div>
                    </div>
                </div>
            @endif
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
        </div>
    </div>
</div>

<script>
let selectedMVPStatId = null;

function selectMVPCandidate(statId) {
    // Remove selected class from all cards
    document.querySelectorAll('.mvp-candidate-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    const selectedCard = document.querySelector(`[data-stat-id="${statId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('selected');
    }

    // Store selection and enable confirm button
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

    // Get CSRF token - try multiple methods
    let csrfToken = '';
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        csrfToken = metaTag.getAttribute('content');
    } else {
        // Fallback: get from Laravel injected token
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
            // Show success message
            btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> MVP Selected!';
            btn.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            
            // Reload page after short delay
            setTimeout(() => {
                location.reload();
            }, 1000);
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