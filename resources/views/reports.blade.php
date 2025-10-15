@extends('layouts.app')

@section('title', 'Scorekeeper Activity Logs - Reports')

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

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .reports-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 2rem 0;
    }

    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    /* Welcome Header */
    .reports-header-banner {
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

    .reports-header-banner::before {
        content: '📊';
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 5rem;
        opacity: 0.2;
    }

    .reports-header-banner h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        position: relative;
        z-index: 1;
    }

    .reports-header-banner p {
        margin: 0;
        opacity: 0.9;
        position: relative;
        z-index: 1;
        font-size: 1rem;
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease-out 0.1s backwards;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        text-align: center;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
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

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(157, 78, 221, 0.2);
        border-color: var(--primary-purple);
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
        box-shadow: 0 4px 12px rgba(157, 78, 221, 0.3);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Main Card */
    .reports-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.2s backwards;
    }

    .reports-header {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        padding: 1.75rem 2rem;
        border-bottom: none;
    }

    .reports-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .reports-title i {
        font-size: 1.75rem;
    }

    /* Filters Section */
    .filters-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.75rem 2rem;
        border-bottom: 2px solid rgba(157, 78, 221, 0.1);
        flex-wrap: wrap;
        gap: 1rem;
        background: linear-gradient(135deg, rgba(157, 78, 221, 0.02), rgba(124, 58, 237, 0.02));
    }

    .filters-left {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-label {
        font-weight: 600;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .filter-select {
        min-width: 180px;
        padding: 10px 36px 10px 16px;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        background: white;
        font-size: 0.9rem;
        color: var(--text-dark);
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%239d4edd' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .filter-select:hover {
        border-color: var(--primary-purple);
        box-shadow: 0 2px 8px rgba(157, 78, 221, 0.15);
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--secondary-purple);
        box-shadow: 0 0 0 3px rgba(157, 78, 221, 0.1);
    }

    .search-box {
        position: relative;
        min-width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 45px 10px 16px;
        border: 2px solid var(--border-color);
        border-radius: 22px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--secondary-purple);
        box-shadow: 0 0 0 3px rgba(157, 78, 221, 0.1);
    }

    .search-box i {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-purple);
        pointer-events: none;
        font-size: 1.1rem;
    }

    /* Table Styles */
    .table-responsive {
        overflow-x: auto;
    }

    .reports-table {
        width: 100%;
        border-collapse: collapse;
    }

    .reports-table thead {
        background: var(--table-header);
    }

    .reports-table th {
        padding: 1rem 1.25rem;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--accent-purple);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 3px solid white;
        white-space: nowrap;
    }

    .reports-table th i {
        margin-right: 0.35rem;
        opacity: 0.8;
    }

    .reports-table td {
        padding: 1.25rem;
        border-bottom: 1px solid #f0f2f5;
        font-size: 0.9rem;
        color: var(--text-dark);
        vertical-align: middle;
    }

    .reports-table tbody tr {
        transition: all 0.2s ease;
        animation: slideIn 0.4s ease-out;
    }

    .reports-table tbody tr:nth-child(even) {
        background: rgba(157, 78, 221, 0.02);
    }

    .reports-table tbody tr:hover {
        background: var(--hover-purple);
        transform: translateX(4px);
        box-shadow: 0 2px 12px rgba(157, 78, 221, 0.08);
    }

    /* Table Cell Badges */
    .badge-id {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
    }

    .badge-sport {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 0.35rem 0.85rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .scorekeeper-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .scorekeeper-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .scorekeeper-email {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-style: italic;
    }

    .date-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-dark);
        font-weight: 500;
    }

    .date-cell i {
        color: var(--primary-purple);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted);
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
        animation: pulse 2s infinite;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 1rem;
        margin: 0;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.75rem 2rem;
        border-top: 2px solid rgba(157, 78, 221, 0.1);
        display: flex;
        justify-content: center;
        background: linear-gradient(135deg, rgba(157, 78, 221, 0.02), rgba(124, 58, 237, 0.02));
    }

    .pagination {
        display: inline-flex;
        gap: 0.5rem;
    }

    .pagination .page-link {
        border-radius: 10px !important;
        border: 2px solid var(--border-color);
        color: var(--primary-purple);
        font-weight: 600;
        padding: 0.5rem 0.85rem;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background: var(--primary-purple);
        color: white;
        border-color: var(--primary-purple);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(157, 78, 221, 0.3);
    }

    .pagination .active .page-link {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        border-color: var(--primary-purple);
        box-shadow: 0 4px 12px rgba(157, 78, 221, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .reports-header-banner h2 {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .filters-section {
            flex-direction: column;
            align-items: stretch;
        }

        .filters-left {
            width: 100%;
            flex-direction: column;
        }

        .filter-select {
            width: 100%;
            min-width: auto;
        }

        .search-box {
            width: 100%;
            min-width: auto;
        }

        .reports-table th,
        .reports-table td {
            padding: 0.85rem;
            font-size: 0.85rem;
        }

        .stat-value {
            font-size: 2rem;
        }
    }

    @media (max-width: 480px) {
        .reports-header-banner {
            padding: 1.5rem;
        }

        .reports-header-banner::before {
            font-size: 3rem;
            right: 1rem;
        }

        .stat-card {
            padding: 1.25rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="reports-page">
    <div class="container-fluid px-4 py-4">
        <!-- Welcome Header -->
        <div class="reports-header-banner">
            <h2>📋 Scorekeeper Activity Logs</h2>
            <p>Track and monitor all scorekeeper activities across tournaments</p>
        </div>

        {{-- Statistics Cards --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="stat-value">{{ $totalLogs }}</div>
                <div class="stat-label">Total Logs</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-value">{{ $activeScorekeepers }}</div>
                <div class="stat-label">Active Scorekeepers</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-trophy"></i>
                </div>
                <div class="stat-value">{{ $sportsCovered }}</div>
                <div class="stat-label">Sports Covered</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div class="stat-value">{{ $tournamentsCount }}</div>
                <div class="stat-label">Tournaments</div>
            </div>
        </div>

        <div class="reports-card">
            <div class="reports-header">
                <h2 class="reports-title">
                    <i class="bi bi-table"></i>
                    Activity Records
                </h2>
            </div>

            {{-- Filters and Search --}}
            <div class="filters-section">
                <div class="filters-left">
                    <span class="filter-label">
                        <i class="bi bi-funnel"></i> Filters:
                    </span>
                    <select class="filter-select" id="tournamentFilter">
                        <option value="">All Tournaments</option>
                        @foreach($tournaments as $tournament)
                            <option value="{{ $tournament->id }}" {{ request('tournament') == $tournament->id ? 'selected' : '' }}>
                                {{ $tournament->name }}
                            </option>
                        @endforeach
                    </select>

                    <select class="filter-select" id="sportFilter">
                        <option value="">All Sports</option>
                        @foreach($sports as $sport)
                            <option value="{{ $sport }}" {{ request('sport') == $sport ? 'selected' : '' }}>
                                {{ $sport }}
                            </option>
                        @endforeach
                    </select>

                    <select class="filter-select" id="scorekeeperFilter">
                        <option value="">All Scorekeepers</option>
                        @foreach($scorekeepers as $scorekeeper)
                            <option value="{{ $scorekeeper->id }}" {{ request('scorekeeper') == $scorekeeper->id ? 'selected' : '' }}>
                                {{ $scorekeeper->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search logs..." value="{{ request('search') }}">
                    <i class="bi bi-search"></i>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="table-responsive">
                <table class="reports-table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash"></i> Tally Sheet ID</th>
                            <th><i class="bi bi-controller"></i> Match ID</th>
                            <th><i class="bi bi-person-badge"></i> Scorekeeper</th>
                            <th><i class="bi bi-calendar-check"></i> Submitted Date</th>
                            <th><i class="bi bi-trophy-fill"></i> Tournament</th>
                            <th><i class="bi bi-dribbble"></i> Sport</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td><span class="badge-id">#{{ $log->tally_sheet_id }}</span></td>
                                <td><strong>{{ $log->match_id }}</strong></td>
                                <td>
                                    <div class="scorekeeper-info">
                                        <span class="scorekeeper-name">{{ $log->scorekeeper_name ?? 'Unknown' }}</span>
                                        <span class="scorekeeper-email">{{ $log->email ?? 'No email' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="date-cell">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $log->submitted_date->format('M d, Y') }}
                                    </span>
                                </td>
                                <td>{{ $log->tournament_name }}</td>
                                <td><span class="badge-sport">{{ $log->sport }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <h3>No Logs Found</h3>
                                        <p>There are no scorekeeper activity logs to display.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
                <div class="pagination-wrapper">
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tournamentFilter = document.getElementById('tournamentFilter');
        const sportFilter = document.getElementById('sportFilter');
        const scorekeeperFilter = document.getElementById('scorekeeperFilter');
        const searchInput = document.getElementById('searchInput');

        function applyFilters() {
            const params = new URLSearchParams(window.location.search);
            
            if (tournamentFilter.value) {
                params.set('tournament', tournamentFilter.value);
            } else {
                params.delete('tournament');
            }
            
            if (sportFilter.value) {
                params.set('sport', sportFilter.value);
            } else {
                params.delete('sport');
            }
            
            if (scorekeeperFilter.value) {
                params.set('scorekeeper', scorekeeperFilter.value);
            } else {
                params.delete('scorekeeper');
            }
            
            if (searchInput.value) {
                params.set('search', searchInput.value);
            } else {
                params.delete('search');
            }
            
            window.location.search = params.toString();
        }

        tournamentFilter.addEventListener('change', applyFilters);
        sportFilter.addEventListener('change', applyFilters);
        scorekeeperFilter.addEventListener('change', applyFilters);
        
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });
    });
</script>
@endsection