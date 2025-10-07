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
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .game-title-header {
        text-align: center;
        margin-bottom: 2rem;
        color: #333;
    }

    .game-title-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
        border-bottom: 3px solid #667eea;
    }

    .team-stats-table {
        width: 100%;
        border-collapse: collapse;
    }

    .team-stats-table thead {
        background: #f8f9fa;
    }

    .team-stats-table th {
        padding: 0.75rem;
        text-align: left;
        font-size: 0.8rem;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e0e0e0;
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
        min-width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 0 0.5rem;
    }

    .position-badge {
        background: #e0e0e0;
        color: #666;
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .league-badge {
        text-align: center;
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .score-display {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-bottom: 1rem;
    }

    .team-score {
        text-align: center;
    }

    .team-logo {
        width: 60px;
        height: 60px;
        margin: 0 auto 0.5rem;
        background: #f0f0f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
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
        color: #667eea;
    }

    .vs-text {
        font-size: 1.2rem;
        color: #999;
        font-weight: 600;
    }

    .final-badge {
        text-align: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem;
        border-radius: 8px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    /* MVP CARD */
    .mvp-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .mvp-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
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
    }

    .mvp-player-photo {
        width: 120px;
        height: 120px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
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
        margin-bottom: 2rem;
    }

    .mvp-candidate-card {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
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
        background: white;
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
        margin: 0 auto;
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

    /* BACK ACTIONS */
    .back-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        padding: 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
</style>
@endpush

@section('content')
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

                <button class="select-mvp-btn" id="confirmMVPBtn" onclick="confirmMVP()" disabled>
                    <i class="bi bi-star-fill"></i>
                    Confirm MVP Selection
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
        </div>
    </div>
</div>

<script>
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
            btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> MVP Selected!';
            btn.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            
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