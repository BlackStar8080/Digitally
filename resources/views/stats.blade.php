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
    }

    .stats-page {
        min-height: 100vh;
        background-color: var(--light-purple);
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
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple), var(--accent-purple));
        color: white;
        padding: 2rem;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .page-content {
        padding: 2rem;
    }

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

    .stat-badge {
        background: var(--primary-purple);
        color: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        display: inline-block;
    }

    .player-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .player-number {
        background: var(--primary-purple);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
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
        }

        .stats-table th,
        .stats-table td {
            padding: 10px 8px;
            font-size: 13px;
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
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Player</th>
                                <th>Average Points</th>
                                <th>Average Assists</th>
                                <th>Average Rebounds</th>
                                <th>Average Blocks</th>
                                <th>Average Fouls</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($playerStats as $player)
                                <tr>
                                    <td>
                                        <div class="player-info">
                                            <div class="player-number">
                                                {{ $player->number ?? '?' }}
                                            </div>
                                            <div class="player-details">
                                                <span class="player-name">{{ $player->name }}</span>
                                                <span class="player-team">{{ $player->team->team_name ?? 'No Team' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="stat-badge">{{ $player->avg_points ?? '0.0' }}</span></td>
                                    <td><span class="stat-badge">{{ $player->avg_assists ?? '0.0' }}</span></td>
                                    <td><span class="stat-badge">{{ $player->avg_rebounds ?? '0.0' }}</span></td>
                                    <td><span class="stat-badge">{{ $player->avg_blocks ?? '0.0' }}</span></td>
                                    <td><span class="stat-badge">{{ $player->avg_fouls ?? '0.0' }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
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