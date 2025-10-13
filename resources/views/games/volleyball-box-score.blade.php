@extends('layouts.app')

@section('title', 'Volleyball Box Score - ' . $game->team1->team_name . ' vs ' . $game->team2->team_name)

@push('styles')
<style>
    :root {
        --primary-blue: #2C7CF9;
        --success-color: #28a745;
        --danger-color: #dc3545;
    }

    .box-score-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .game-header-card {
        background: linear-gradient(135deg, #4E56C0, #696FC7);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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
        gap: 2rem;
        margin: 1.5rem 0;
    }

    .team-score-section {
        text-align: center;
    }

    .team-name-large {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .sets-won {
        font-size: 3rem;
        font-weight: 700;
        font-family: 'Courier New', monospace;
    }

    .winner-badge {
        background: var(--success-color);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 0.5rem;
    }

    .set-scores-display {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .set-score-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .stats-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .stats-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d2d2d;
    }

    .stats-table {
        width: 100%;
        border-collapse: collapse;
    }

    .stats-table thead {
        background: #f8f9fa;
    }

    .stats-table th {
        padding: 1rem 0.75rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-table td {
        padding: 1rem 0.75rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .stats-table tbody tr:hover {
        background: #f8f9fa;
    }

    .player-name-cell {
        font-weight: 600;
    }

    .player-number {
        display: inline-block;
        background: var(--primary-blue);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        margin-right: 0.75rem;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .stat-highlight {
        font-weight: 700;
        color: var(--primary-blue);
    }

    .mvp-row {
        background: rgba(40, 167, 69, 0.08);
        border-left: 4px solid var(--success-color);
    }

    .mvp-badge {
        background: var(--success-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    .mvp-select-btn {
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .mvp-select-btn:hover {
        background: #1565C0;
        transform: translateY(-1px);
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-primary, .btn-secondary {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
    }

    .btn-primary {
        background: var(--primary-blue);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background: #1565C0;
        color: white;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
        border: none;
    }

    .btn-secondary:hover {
        background: #5a6268;
        color: white;
    }

    .team-totals-row {
        background: #f8f9fa;
        font-weight: 700;
        border-top: 2px solid #dee2e6;
    }
</style>
@endpush

@section('content')
<div class="box-score-container">
    <!-- Game Header -->
    <div class="game-header-card">
        <div class="text-center">
            <div class="game-title">
                @if($game->bracket && $game->bracket->tournament)
                    {{ $game->bracket->tournament->name }} - Volleyball
                @else
                    Volleyball Match
                @endif
            </div>
            
            <div class="final-score">
                <div class="team-score-section">
                    <div class="team-name-large">{{ $game->team1->team_name }}</div>
                    <div class="sets-won">{{ $game->team1_score }}</div>
                    @if($game->winner_id === $game->team1_id)
                        <div class="winner-badge">🏆 WINNER</div>
                    @endif
                </div>
                
                <div style="font-size: 2rem; opacity: 0.7;">-</div>
                
                <div class="team-score-section">
                    <div class="team-name-large">{{ $game->team2->team_name }}</div>
                    <div class="sets-won">{{ $game->team2_score }}</div>
                    @if($game->winner_id === $game->team2_id)
                        <div class="winner-badge">🏆 WINNER</div>
                    @endif
                </div>
            </div>

            @if($game->volleyballTallysheet && $game->volleyballTallysheet->set_scores)
                <div class="set-scores-display">
                    @foreach($game->volleyballTallysheet->set_scores as $setScore)
                        <div class="set-score-badge">
                            Set {{ $setScore['set'] }}: {{ $setScore['team1'] }}-{{ $setScore['team2'] }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Team 1 Stats -->
    <div class="stats-card">
        <div class="stats-header">
            <h3 class="stats-title">{{ $game->team1->team_name }} - Player Statistics</h3>
        </div>

        <div class="table-responsive">
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th>K</th>
                        <th>A</th>
                        <th>B</th>
                        <th>D</th>
                        <th>AS</th>
                        <th>E</th>
                        <th>Total Pts</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($team1Stats as $stat)
                        <tr class="{{ $stat->is_mvp ? 'mvp-row' : '' }}">
                            <td class="player-name-cell">
                                <span class="player-number">{{ $stat->player->number ?? '00' }}</span>
                                {{ $stat->player->name }}
                                @if($stat->is_mvp)
                                    <span class="mvp-badge">MVP</span>
                                @endif
                            </td>
                            <td class="stat-highlight">{{ $stat->kills }}</td>
                            <td>{{ $stat->aces }}</td>
                            <td>{{ $stat->blocks }}</td>
                            <td>{{ $stat->digs }}</td>
                            <td>{{ $stat->assists }}</td>
                            <td style="color: var(--danger-color);">{{ $stat->errors }}</td>
                            <td class="stat-highlight">{{ $stat->getTotalPoints() }}</td>
                            <td>
                                @if(!$mvpSelected && !$stat->is_mvp)
                                    <button class="mvp-select-btn" onclick="selectMVP({{ $stat->id }})">
                                        Select as MVP
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding: 2rem;">
                                No statistics available
                            </td>
                        </tr>
                    @endforelse
                    
                    @if($team1Stats->count() > 0)
                        <tr class="team-totals-row">
                            <td>TEAM TOTALS</td>
                            <td class="stat-highlight">{{ $team1Stats->sum('kills') }}</td>
                            <td>{{ $team1Stats->sum('aces') }}</td>
                            <td>{{ $team1Stats->sum('blocks') }}</td>
                            <td>{{ $team1Stats->sum('digs') }}</td>
                            <td>{{ $team1Stats->sum('assists') }}</td>
                            <td style="color: var(--danger-color);">{{ $team1Stats->sum('errors') }}</td>
                            <td class="stat-highlight">{{ $team1Stats->sum(function($stat) { return $stat->getTotalPoints(); }) }}</td>
                            <td></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
            <small style="color: #666;">
                <strong>Legend:</strong> K = Kills, A = Aces, B = Blocks, D = Digs, AS = Assists, E = Errors, Total Pts = Kills + Aces + Blocks
            </small>
        </div>
    </div>

    <!-- Team 2 Stats -->
    <div class="stats-card">
        <div class="stats-header">
            <h3 class="stats-title">{{ $game->team2->team_name }} - Player Statistics</h3>
        </div>

        <div class="table-responsive">
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th>K</th>
                        <th>A</th>
                        <th>B</th>
                        <th>D</th>
                        <th>AS</th>
                        <th>E</th>
                        <th>Total Pts</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($team2Stats as $stat)
                        <tr class="{{ $stat->is_mvp ? 'mvp-row' : '' }}">
                            <td class="player-name-cell">
                                <span class="player-number">{{ $stat->player->number ?? '00' }}</span>
                                {{ $stat->player->name }}
                                @if($stat->is_mvp)
                                    <span class="mvp-badge">MVP</span>
                                @endif
                            </td>
                            <td class="stat-highlight">{{ $stat->kills }}</td>
                            <td>{{ $stat->aces }}</td>
                            <td>{{ $stat->blocks }}</td>
                            <td>{{ $stat->digs }}</td>
                            <td>{{ $stat->assists }}</td>
                            <td style="color: var(--danger-color);">{{ $stat->errors }}</td>
                            <td class="stat-highlight">{{ $stat->getTotalPoints() }}</td>
                            <td>
                                @if(!$mvpSelected && !$stat->is_mvp)
                                    <button class="mvp-select-btn" onclick="selectMVP({{ $stat->id }})">
                                        Select as MVP
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding: 2rem;">
                                No statistics available
                            </td>
                        </tr>
                    @endforelse
                    
                    @if($team2Stats->count() > 0)
                        <tr class="team-totals-row">
                            <td>TEAM TOTALS</td>
                            <td class="stat-highlight">{{ $team2Stats->sum('kills') }}</td>
                            <td>{{ $team2Stats->sum('aces') }}</td>
                            <td>{{ $team2Stats->sum('blocks') }}</td>
                            <td>{{ $team2Stats->sum('digs') }}</td>
                            <td>{{ $team2Stats->sum('assists') }}</td>
                            <td style="color: var(--danger-color);">{{ $team2Stats->sum('errors') }}</td>
                            <td class="stat-highlight">{{ $team2Stats->sum(function($stat) { return $stat->getTotalPoints(); }) }}</td>
                            <td></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
            <small style="color: #666;">
                <strong>Legend:</strong> K = Kills, A = Aces, B = Blocks, D = Digs, AS = Assists, E = Errors, Total Pts = Kills + Aces + Blocks
            </small>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('games.index') }}" class="btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Games
        </a>
        <a href="{{ route('games.volleyball-scoresheet', $game) }}" class="btn-primary" target="_blank">
            <i class="bi bi-file-earmark-text"></i>
            View Scoresheet
        </a>
        @if($game->bracket && $game->bracket->tournament)
            <a href="{{ route('tournaments.show', $game->bracket->tournament->id) }}" class="btn-primary">
                <i class="bi bi-trophy"></i>
                View Tournament
            </a>
        @endif
    </div>
</div>

<script>
function selectMVP(statId) {
    if (!confirm('Select this player as the game MVP?')) {
        return;
    }

    fetch(`/games/{{ $game->id }}/select-volleyball-mvp`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            player_stat_id: statId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('MVP selected successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to select MVP. Please try again.');
    });
}
</script>
@endsection