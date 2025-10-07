@extends('layouts.app')

@section('title', 'Player Statistics')

@push('styles')
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
        --gold: #ffd700;
        --silver: #c0c0c0;
        --bronze: #cd7f32;
    }

    .stats-page {
        min-height: 100vh;
        background-color: var(--light-purple);
        padding: 2rem 0;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .page-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple), var(--accent-purple));
        color: white;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '📊';
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: 0.2;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        position: relative;
        z-index: 1;
    }

    .page-content {
        padding: 2rem;
    }

    /* Top Performers Section */
    .top-performers-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title::before {
        content: '🏆';
        font-size: 24px;
    }

    .top-performers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .performer-card {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .performer-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(157, 78, 221, 0.3);
    }

    .performer-card.gold {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #000;
    }

    .performer-card.silver {
        background: linear-gradient(135deg, #c0c0c0, #e8e8e8);
        color: #000;
    }

    .performer-card.bronze {
        background: linear-gradient(135deg, #cd7f32, #e89a5d);
        color: #fff;
    }

    .performer-rank {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 2rem;
        font-weight: 700;
        opacity: 0.3;
    }

    .performer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .performer-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .performer-details h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .performer-details p {
        margin: 0;
        font-size: 13px;
        opacity: 0.8;
    }

    .performer-stats {
        display: flex;
        justify-content: space-around;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .performer-stat {
        text-align: center;
    }

    .performer-stat-value {
        font-size: 22px;
        font-weight: 700;
        display: block;
    }

    .performer-stat-label {
        font-size: 11px;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Controls Section */
    .controls-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .filters-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
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
        border-color: var(--secondary-purple);
        box-shadow: 0 0 0 3px rgba(157, 78, 221, 0.1);
    }

    .search-container {
        position: relative;
        width: 280px;
    }

    .search-input {
        width: 100%;
        height: 44px;
        padding: 0 50px 0 16px;
        border: 2px solid var(--border-color);
        border-radius: 22px;
        font-size: 14px;
        background: white;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--secondary-purple);
        box-shadow: 0 0 0 3px rgba(157, 78, 221, 0.1);
    }

    .search-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--primary-purple);
        color: white;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        background: var(--secondary-purple);
        transform: translateY(-50%) scale(1.05);
    }

    /* Table Section */
    .table-container {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .stats-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .stats-table th {
        background: var(--table-header);
        color: var(--accent-purple);
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px 12px;
        text-align: left;
        border-bottom: 2px solid white;
        cursor: pointer;
        user-select: none;
        position: relative;
    }

    .stats-table th:hover {
        background: rgba(157, 78, 221, 0.15);
    }

    .stats-table th.sortable::after {
        content: '⇅';
        margin-left: 0.5rem;
        opacity: 0.3;
    }

    .stats-table th.sorted-asc::after {
        content: '↑';
        opacity: 1;
    }

    .stats-table th.sorted-desc::after {
        content: '↓';
        opacity: 1;
    }

    .stats-table td {
        padding: 14px 12px;
        text-align: left;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
    }

    .stats-table tbody tr {
        transition: all 0.2s ease;
    }

    .stats-table tbody tr:nth-child(even) {
        background: var(--light-purple);
    }

    .stats-table tbody tr:hover {
        background: var(--hover-purple);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(157, 78, 221, 0.08);
    }

    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        font-weight: 700;
        font-size: 14px;
    }

    .rank-badge.gold {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #000;
    }

    .rank-badge.silver {
        background: linear-gradient(135deg, #c0c0c0, #e8e8e8);
        color: #000;
    }

    .rank-badge.bronze {
        background: linear-gradient(135deg, #cd7f32, #e89a5d);
        color: #fff;
    }

    .rank-badge.default {
        background: var(--primary-purple);
        color: white;
    }

    .stat-value {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(157, 78, 221, 0.08);
        color: var(--primary-purple);
        padding: 4px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .stat-icon {
        font-size: 14px;
    }

    .player-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .player-number {
        background: var(--primary-purple);
        color: white;
        min-width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        padding: 0 0.5rem;
    }

    .player-details {
        display: flex;
        flex-direction: column;
    }

    .player-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .player-team {
        font-size: 12px;
        color: var(--text-muted);
    }

    .games-played {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .games-played::before {
        content: '🎮 ';
    }

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

    /* Stat comparison bars */
    .stat-bar-container {
        width: 60px;
        height: 6px;
        background: #e0e0e0;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 4px;
    }

    .stat-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    /* Responsive Design */
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

        .page-content {
            padding: 1.5rem;
        }

        .controls-section {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .filters-group {
            justify-content: center;
        }

        .search-container {
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }

        .top-performers-grid {
            grid-template-columns: 1fr;
        }

        .stats-table th,
        .stats-table td {
            padding: 10px 8px;
            font-size: 13px;
        }

        .table-container {
            overflow-x: auto;
        }
    }

    @media (max-width: 480px) {
        .filters-group {
            flex-direction: column;
            width: 100%;
        }

        .filter-select {
            width: 100%;
            min-width: auto;
        }
    }
</style>
@endpush

@section('content')
<div class="stats-page">
    <div class="container">
        <div class="page-card">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Player Statistics</h1>
            </div>

            <div class="page-content">
                <!-- Top Performers Section -->
                @if($playerStats->isNotEmpty())
                    <div class="top-performers-section">
                        <h2 class="section-title">Top Performers</h2>
                        <div class="top-performers-grid">
                            @foreach($playerStats->take(3) as $index => $player)
                                @php
                                    $cardClass = '';
                                    if ($index === 0) $cardClass = 'gold';
                                    elseif ($index === 1) $cardClass = 'silver';
                                    elseif ($index === 2) $cardClass = 'bronze';
                                @endphp
                                <div class="performer-card {{ $cardClass }}">
                                    <div class="performer-rank">{{ $index + 1 }}</div>
                                    <div class="performer-info">
                                        <div class="performer-avatar">
                                            {{ $player->number ?? '?' }}
                                        </div>
                                        <div class="performer-details">
                                            <h3>{{ $player->name }}</h3>
                                            <p>{{ $player->team->team_name ?? 'No Team' }}</p>
                                            <small style="opacity: 0.7;">{{ $player->games_played ?? 0 }} Games Played</small>
                                        </div>
                                    </div>
                                    <div class="performer-stats">
                                        <div class="performer-stat">
                                            <span class="performer-stat-value">{{ $player->avg_points ?? '0.0' }}</span>
                                            <span class="performer-stat-label">PPG</span>
                                        </div>
                                        <div class="performer-stat">
                                            <span class="performer-stat-value">{{ $player->avg_assists ?? '0.0' }}</span>
                                            <span class="performer-stat-label">APG</span>
                                        </div>
                                        <div class="performer-stat">
                                            <span class="performer-stat-value">{{ $player->avg_rebounds ?? '0.0' }}</span>
                                            <span class="performer-stat-label">RPG</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Controls Section -->
                <div class="controls-section">
                    <form method="GET" action="{{ route('players.stats') }}" class="filters-group">
                        <select name="team_id" class="filter-select" onchange="this.form.submit()">
                            <option value="all">All Teams</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ request('team_id') == $team->id ? 'selected' : '' }}>
                                    {{ $team->team_name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="sport" class="filter-select" onchange="this.form.submit()">
                            <option value="all">All Sports</option>
                            @foreach($sports as $sport)
                                <option value="{{ $sport }}" {{ request('sport') == $sport ? 'selected' : '' }}>
                                    {{ $sport }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <div class="search-container">
                        <form method="GET" action="{{ route('players.stats') }}">
                            <input type="hidden" name="team_id" value="{{ request('team_id', 'all') }}">
                            <input type="hidden" name="sport" value="{{ request('sport', 'all') }}">
                            <input type="text" name="search" class="search-input" 
                                   placeholder="Search Players..." 
                                   value="{{ request('search') }}">
                            <button class="search-btn" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Statistics Table -->
                <div class="table-container">
                    <table class="stats-table" id="statsTable">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Rank</th>
                                <th>Player</th>
                                <th class="sortable" data-column="points">
                                    <span class="stat-icon">⛹️</span> PPG
                                </th>
                                <th class="sortable" data-column="assists">
                                    <span class="stat-icon">🤝</span> APG
                                </th>
                                <th class="sortable" data-column="rebounds">
                                    <span class="stat-icon">🏀</span> RPG
                                </th>
                                <th class="sortable" data-column="blocks">
                                    <span class="stat-icon">🚫</span> BPG
                                </th>
                                <th class="sortable" data-column="fouls">
                                    <span class="stat-icon">🟨</span> FPG
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($playerStats as $index => $player)
                                <tr>
                                    <td>
                                        @php
                                            $rankClass = 'default';
                                            $actualRank = (($playerStats->currentPage() - 1) * $playerStats->perPage()) + $index + 1;
                                            if ($actualRank === 1) $rankClass = 'gold';
                                            elseif ($actualRank === 2) $rankClass = 'silver';
                                            elseif ($actualRank === 3) $rankClass = 'bronze';
                                        @endphp
                                        <div class="rank-badge {{ $rankClass }}">
                                            {{ $actualRank }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="player-info">
                                            <div class="player-number">
                                                {{ $player->number ?? '?' }}
                                            </div>
                                            <div class="player-details">
                                                <span class="player-name">{{ $player->name }}</span>
                                                <span class="player-team">{{ $player->team->team_name ?? 'No Team' }}</span>
                                                <span class="games-played">{{ $player->games_played ?? 0 }} games</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="stat-value">
                                            {{ number_format($player->avg_points ?? 0, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="stat-value">
                                            {{ number_format($player->avg_assists ?? 0, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="stat-value">
                                            {{ number_format($player->avg_rebounds ?? 0, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="stat-value">
                                            {{ number_format($player->avg_blocks ?? 0, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="stat-value">
                                            {{ number_format($player->avg_fouls ?? 0, 1) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="bi bi-bar-chart"></i>
                                            </div>
                                            <p><strong>No statistics available</strong></p>
                                            <p>Player statistics will appear here after games are completed.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($playerStats->total() > 15)
                    <div style="margin-top: 20px; text-align: center;">
                        {{ $playerStats->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('statsTable');
    const headers = table.querySelectorAll('th.sortable');
    
    headers.forEach(header => {
        header.addEventListener('click', function() {
            const column = this.dataset.column;
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => !row.querySelector('.empty-state'));
            
            // Determine sort direction
            const isAsc = this.classList.contains('sorted-asc');
            
            // Remove all sort classes
            headers.forEach(h => h.classList.remove('sorted-asc', 'sorted-desc'));
            
            // Add appropriate class
            if (isAsc) {
                this.classList.add('sorted-desc');
            } else {
                this.classList.add('sorted-asc');
            }
            
            // Get column index based on the stat type
            const columnMap = {
                'points': 2,
                'assists': 3,
                'rebounds': 4,
                'blocks': 5,
                'fouls': 6
            };
            const columnIndex = columnMap[column];
            
            // Sort rows
            rows.sort((a, b) => {
                const aValue = parseFloat(a.cells[columnIndex].textContent.trim()) || 0;
                const bValue = parseFloat(b.cells[columnIndex].textContent.trim()) || 0;
                
                return isAsc ? bValue - aValue : aValue - bValue;
            });
            
            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
            
            // Update ranks
            rows.forEach((row, index) => {
                const rankBadge = row.querySelector('.rank-badge');
                if (rankBadge) {
                    rankBadge.textContent = index + 1;
                    rankBadge.className = 'rank-badge';
                    if (index === 0) rankBadge.classList.add('gold');
                    else if (index === 1) rankBadge.classList.add('silver');
                    else if (index === 2) rankBadge.classList.add('bronze');
                    else rankBadge.classList.add('default');
                }
            });
        });
    });
});
</script>
@endpush