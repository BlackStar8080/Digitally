@extends('layouts.app')

@section('title', 'Players')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* Core Variables */
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

        .players-page {
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

        .actions-group {
            display: flex;
            align-items: center;
            gap: 1rem;
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

        .players-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .players-table th {
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

        .players-table td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
        }

        .players-table tbody tr {
            transition: all 0.2s ease;
        }

        .players-table tbody tr:nth-child(even) {
            background: var(--light-purple);
        }

        .players-table tbody tr:hover {
            background: var(--hover-purple);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(157, 78, 221, 0.08);
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

        /* Fade in animation for table rows */
        .fade-in-row {
            animation: fadeIn 0.4s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="players-page">
        <div class="container">
            <div class="page-card">
                <div class="page-header">
                    <h1 class="page-title">Players</h1>
                </div>
                <div class="page-content">
                    <div class="controls-section">
                        <div class="filters-group">
                            <select id="teamFilter" class="filter-select">
                                <option value="">Filter by Team</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                @endforeach
                            </select>
                            <select id="sportFilter" class="filter-select">
                                <option value="">Filter by Sport</option>
                                @foreach ($sports as $sport)
                                    <option value="{{ $sport->sports_id }}">{{ $sport->sports_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="actions-group">
                            <div class="search-container">
                                <input type="text" id="searchInput" class="search-input" placeholder="Search players...">
                                <button class="search-btn" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="players-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Team</th>
                                    <th>Jersey #</th>
                                    <th>Sport</th>
                                    <th>Position</th>
                                    <th>Birthday</th>
                                </tr>
                            </thead>
                            <tbody id="playerTable">
                                @forelse($players as $player)
                                    <tr class="fade-in-row">
                                        <td>{{ $player->name }}</td>
                                        <td>{{ $player->team ? $player->team->team_name : 'No Team' }}</td>
                                        <td>{{ $player->number ?? 'N/A' }}</td>
                                        <td>{{ $player->sport ? $player->sport->sports_name : 'N/A' }}</td>
                                        <td>{{ $player->position ?? 'N/A' }}</td>
                                        <td>
                                            {{ $player->birthday ? \Carbon\Carbon::parse($player->birthday)->format('F j, Y') : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <div class="empty-icon">
                                                    <i class="bi bi-people"></i>
                                                </div>
                                                <p><strong>No players found</strong></p>
                                                <p>Check back later for more players.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if ($players->total() > 15)
            <div style="margin-top: 20px; text-align: center;">
                {{ $players->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const teamFilter = document.getElementById('teamFilter');
            const sportFilter = document.getElementById('sportFilter');

            function filterTable() {
                const search = searchInput.value.toLowerCase();
                const selectedTeam = teamFilter.value.toLowerCase();
                const selectedSport = sportFilter.value.toLowerCase();

                document.querySelectorAll('#playerTable tr').forEach(row => {
                    const name = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                    const team = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                    const sport = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';

                    const matchesSearch = name.includes(search) || team.includes(search) || sport.includes(search);
                    const matchesTeam = !selectedTeam || team.includes(selectedTeam);
                    const matchesSport = !selectedSport || sport.includes(selectedSport);

                    row.style.display = (matchesSearch && matchesTeam && matchesSport) ? '' : 'none';
                });
            }

            if (searchInput) searchInput.addEventListener('input', filterTable);
            if (teamFilter) teamFilter.addEventListener('change', filterTable);
            if (sportFilter) sportFilter.addEventListener('change', filterTable);

            // Add fade-in animation to existing table rows
            document.querySelectorAll('#playerTable tr').forEach((row, index) => {
                row.style.animationDelay = `${index * 0.05}s`;
                row.classList.add('fade-in-row');
            });
        });
    </script>
@endsection