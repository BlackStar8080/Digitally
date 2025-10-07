@extends('layouts.app')

@section('title', 'Teams')

@push('styles')
<style>
/* Root Variables - matching players blade */
:root {
    --primary-purple: #9d4edd;
    --secondary-purple: #7c3aed;
    --accent-purple: #5f2da8;
    --light-purple: #ffffff;
    --border-color: #e5e7eb;
    --text-dark: #212529;
    --text-muted: #6c757d;
    --background-light: #f8faff;
    --hover-purple: #ede9fe;
}

body {
    background-color: #ffffff !important;
}

/* Page Structure - matching players blade */
.teams-page {
    min-height: 100vh;
    background-color: var(--light-purple);
    padding: 2rem 0;
}

/* Change Add Team button color in modal footer */
.modal-footer .btn.btn-primary {
  background: var(--primary-purple);
  border: none;
  color: #fff;
  transition: 0.3s ease;
}

.modal-footer .btn.btn-primary:hover {
  background: var(--secondary-purple);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(157, 78, 221, 0.3);
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

/* Header Styling - matching players blade */
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

/* Content Section */
.page-content {
    padding: 2rem;
}

/* Controls Section - matching players blade */
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

.add-btn {
    background: var(--primary-purple);
    color: white;
    padding: 0 24px;
    height: 44px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.add-btn:hover {
    background: var(--secondary-purple);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(157, 78, 221, 0.3);
}

/* Teams Grid */
.teams-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.team-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.team-card:hover {
    border-color: var(--primary-purple);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(157, 78, 221, 0.15);
}

.team-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.team-logo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
    color: white;
    flex-shrink: 0;
    background: var(--primary-purple);
}

.team-info h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
}

.team-info .team-location {
    color: var(--text-muted);
    font-size: 14px;
    margin: 0;
}

.team-stats {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}

.stat-number {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary-purple);
    display: block;
}

.stat-label {
    font-size: 12px;
    color: var(--text-muted);
    text-transform: uppercase;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 64px;
    color: #dee2e6;
    margin-bottom: 20px;
}

/* Modal Styling */
.modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    color: white;
    border: none;
    padding: 1.5rem 2rem;
}

.modal-title {
    font-weight: 700;
    font-size: 20px;
}

.btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.btn-close:hover {
    opacity: 1;
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
    
    .filters-group,
    .actions-group {
        justify-content: center;
    }
    
    .search-container {
        width: 100%;
        max-width: 300px;
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
<div class="teams-page">
    <div class="container">
        <div class="page-card">
            <!-- Page Header with Purple Gradient -->
            <div class="page-header">
                <h1 class="page-title">Teams Management</h1>
            </div>
            
            <!-- Page Content -->
            <div class="page-content">
                <!-- Controls Section -->
                <div class="controls-section">
                    <div class="filters-group">
                        <select class="filter-select" id="sportFilter">
                            <option value="all">All Sports</option>
                            <option value="basketball">Basketball</option>
                            <option value="volleyball">Volleyball</option>
                        </select>
                    </div>
                    
                    <div class="actions-group">
                        <div class="search-container">
                            <input type="text" class="search-input" id="searchTeams" placeholder="Search teams...">
                            <button class="search-btn" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                            <i class="bi bi-plus-circle"></i>
                            Add Team
                        </button>
                    </div>
                </div>
                
                <!-- Teams Grid -->
                <div class="teams-grid" id="teamsGrid"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add Team Modal -->
<div class="modal fade" id="addTeamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Add New Team
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>There were some errors:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <form id="addTeamForm" action="{{ route('teams.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="team_name" class="form-label">Team Name</label>
                        <input type="text" class="form-control @error('team_name') is-invalid @enderror" 
                               id="team_name" name="team_name" value="{{ old('team_name') }}" required>
                        @error('team_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="coach_name" class="form-label">Coach Name</label>
                        <input type="text" class="form-control @error('coach_name') is-invalid @enderror" 
                               id="coach_name" name="coach_name" value="{{ old('coach_name') }}">
                        @error('coach_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="number" class="form-control @error('contact') is-invalid @enderror" 
                               id="contact" name="contact" value="{{ old('contact') }}">
                        @error('contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address / Location</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" 
                               id="address" name="address" value="{{ old('address') }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="sport" class="form-label">Sport</label>
                        <select class="form-control @error('sport') is-invalid @enderror" 
                                id="sport" name="sport" required>
                            <option value="">Select Sport</option>
                            <option value="basketball" {{ old('sport')=='basketball'?'selected':'' }}>Basketball</option>
                            <option value="volleyball" {{ old('sport')=='volleyball'?'selected':'' }}>Volleyball</option>
                        </select>
                        @error('sport')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" form="addTeamForm">
                    <i class="bi bi-check-lg me-2"></i>Add Team
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    function escapeHtml(str = '') {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    const teamsGrid = document.getElementById('teamsGrid');
    const searchInput = document.getElementById('searchTeams');
    const sportFilter = document.getElementById('sportFilter');
    const teams = @json($teams);

    function renderTeams(list) {
        if (!Array.isArray(list) || list.length === 0) {
            teamsGrid.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-people"></i>
                    <h3>No teams found</h3>
                    <p>Try adjusting your search criteria or add a new team to get started.</p>
                </div>
            `;
            return;
        }

        teamsGrid.innerHTML = list.map(team => {
            const name = escapeHtml(team.team_name ?? '');
            const address = escapeHtml(team.address ?? team.location ?? '');
            const playersCount = Number(team.players_count ?? 0);
            const wins = Number(team.wins ?? 0);
            const losses = Number(team.losses ?? 0);

            return `
                <a class="team-card" href="/teams/${team.id}">
                    <div class="team-header">
                        <div class="team-logo">
                            ${escapeHtml(String((team.team_name || '').charAt(0) || '').toUpperCase())}
                        </div>
                        <div class="team-info">
                            <h3>${name}</h3>
                            <p class="team-location">${address}</p>
                        </div>
                    </div>
                    <div class="team-stats">
                        <div class="stat-item">
                            <span class="stat-number">${playersCount}</span>
                            <div class="stat-label">Players</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">${wins}</span>
                            <div class="stat-label">Wins</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">${losses}</span>
                            <div class="stat-label">Losses</div>
                        </div>
                    </div>
                </a>
            `;
        }).join('');
    }

    function filterTeams() {
        const q = (searchInput.value || '').trim().toLowerCase();
        const selected = (sportFilter.value || 'all').toLowerCase();

        const filtered = teams.filter(team => {
            const name = (team.team_name || '').toString().toLowerCase();
            const address = (team.address || team.location || '').toString().toLowerCase();
            const sport = (team.sport || '').toString().toLowerCase();

            const matchSearch = !q || name.includes(q) || address.includes(q) || 
                              (team.coach_name || '').toString().toLowerCase().includes(q);
            const matchSport = selected === 'all' || (sport && sport === selected);

            return matchSearch && matchSport;
        });

        renderTeams(filtered);
    }

    searchInput.addEventListener('input', filterTeams);
    sportFilter.addEventListener('change', filterTeams);

    renderTeams(teams);
})();

document.addEventListener('DOMContentLoaded', function () {
    @if ($errors->any())
        var teamModal = new bootstrap.Modal(document.getElementById('addTeamModal'));
        teamModal.show();
    @endif
});
</script>
@endsection