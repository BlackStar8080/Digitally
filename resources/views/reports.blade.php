@extends('layouts.app')

@section('title', 'Scorekeeper Activity Logs - Reports')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="reports-card">
        <div class="reports-header">
            <h2 class="reports-title">Scorekeeper Activity Logs</h2>
        </div>

        {{-- Statistics Cards --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $totalLogs }}</div>
                <div class="stat-label">Total Logs</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $activeScorekeepers }}</div>
                <div class="stat-label">Active Scorekeeper</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $sportsCovered }}</div>
                <div class="stat-label">Sports Covered</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $tournamentsCount }}</div>
                <div class="stat-label">Tournaments</div>
            </div>
        </div>

        {{-- Filters and Search --}}
        <div class="filters-section">
            <div class="filters-left">
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
                    <option value="">All Scorekeeper</option>
                    @foreach($scorekeepers as $scorekeeper)
                        <option value="{{ $scorekeeper->id }}" {{ request('scorekeeper') == $scorekeeper->id ? 'selected' : '' }}>
                            {{ $scorekeeper->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search Logs..." value="{{ request('search') }}">
                <i class="bi bi-search"></i>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="table-responsive">
            <table class="reports-table">
                <thead>
                    <tr>
                        <th>Tally Sheet ID</th>
                        <th>Match ID</th>
                        <th>Scorekeeper Name</th>
                        <th>Email</th>
                        <th>Submitted Date</th>
                        <th>Tournament</th>
                        <th>Sport</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->tally_sheet_id }}</td>
                            <td>{{ $log->match_id }}</td>
                            <td>{{ $log->scorekeeper_name }}</td>
                            <td>{{ $log->email }}</td>
                            <td>{{ $log->submitted_date->format('m/d/Y') }}</td>
                            <td>{{ $log->tournament_name }}</td>
                            <td>{{ $log->sport }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem; color: #cbd5e1;"></i>
                                <p class="text-muted mt-2">No logs found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div class="pagination-wrapper">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .reports-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .reports-header {
        padding: 24px 32px;
        border-bottom: 1px solid #e2e8f0;
    }

    .reports-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        padding: 24px 32px;
    }

    .stat-card {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        border: 1px solid #93c5fd;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1e40af;
        line-height: 1;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1e40af;
        text-transform: capitalize;
    }

    .filters-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 32px;
        border-bottom: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 16px;
    }

    .filters-left {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 8px 32px 8px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: white;
        font-size: 0.875rem;
        color: #1e293b;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23475569' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        transition: all 0.2s;
    }

    .filter-select:hover {
        border-color: #2563eb;
    }

    .filter-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .search-box {
        position: relative;
        width: 280px;
    }

    .search-box input {
        width: 100%;
        padding: 8px 40px 8px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .search-box i {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        pointer-events: none;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .reports-table {
        width: 100%;
        border-collapse: collapse;
    }

    .reports-table thead {
        background: #f8fafc;
    }

    .reports-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .reports-table td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.875rem;
        color: #1e293b;
    }

    .reports-table tbody tr:hover {
        background: #f8fafc;
    }

    .pagination-wrapper {
        padding: 20px 32px;
        border-top: 1px solid #e2e8f0;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filters-section {
            flex-direction: column;
            align-items: stretch;
        }

        .filters-left {
            width: 100%;
        }

        .filter-select {
            flex: 1;
        }

        .search-box {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
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
@endpush
@endsection