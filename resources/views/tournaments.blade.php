@extends('layouts.app')

@section('title', 'Tournaments')

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

/* Page Structure - matching players blade */
.tournaments-page {
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

.actions-group {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-left: auto;
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

/* Tournaments Grid */
.tournaments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
}

.tournament-card {
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    display: block;
}

.tournament-card:hover {
    border-color: var(--primary-purple);
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(157, 78, 221, 0.13);
}

.tournament-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.tournament-info {
    flex: 1;
}

.tournament-name {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 0.5rem 0;
}

.tournament-date {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
}

.sport-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    flex-shrink: 0;
}

.sport-icon.basketball {
    background: linear-gradient(135deg, #ff6b35, #ff8e53);
}

.sport-icon.volleyball {
    background: linear-gradient(135deg, #4ecdc4, #44a08d);
}



.tournament-type {
    display: inline-block;
    background: rgba(157, 78, 221, 0.08);
    color: var(--primary-purple);
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 1rem;
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

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 4rem;
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
    
    .actions-group {
        justify-content: center;
        margin-left: 0;
    }
    
    .search-container {
        width: 100%;
        max-width: 300px;
    }
    
    .tournaments-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

/* Animation */
.tournament-card {
    animation: fadeInUp 0.6s ease-out;
}

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
</style>
@endpush

@section('content')
<div class="tournaments-page">
    <div class="container">
        <div class="page-card">
            <!-- Page Header with Purple Gradient -->
            <div class="page-header">
                <h1 class="page-title">Tournaments Management</h1>
            </div>
            
            <!-- Page Content -->
            <div class="page-content">
                <!-- Controls Section -->
                <div class="controls-section">
                    <div style="flex: 1;"></div>
                    <div class="actions-group">
                        <div class="search-container">
                            <input type="text" class="search-input" id="searchInput" placeholder="Search tournaments...">
                            <button class="search-btn" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#addTournamentModal">
                            <i class="bi bi-plus-circle"></i>
                            Add Tournament
                        </button>
                    </div>
                </div>
                
                <!-- Tournaments Grid -->
                <div class="tournaments-grid" id="tournamentsGrid">
                    @forelse($tournaments as $tournament)
                        <a href="{{ route('tournaments.show', $tournament->id) }}" class="tournament-card"
                           data-name="{{ strtolower($tournament->name) }}"
                           data-sport="{{ strtolower($tournament->sport ?? '') }}"
                           data-bracket="{{ strtolower($tournament->bracket_type ?? $tournament->bracketType ?? '') }}">
                            <div class="tournament-header">
                                <div class="tournament-info">
                                    <h3 class="tournament-name">{{ $tournament->name }}</h3>
                                    <p class="tournament-date">
                                        {{ \Carbon\Carbon::parse($tournament->start_date ?? $tournament->date ?? now())->format('F d, Y') }}
                                    </p>
                                </div>
                                <div class="sport-icon {{ strtolower($tournament->sport ?? '') }}">
                                    @php $s = strtolower($tournament->sport ?? ''); @endphp
                                    @if($s === 'basketball') 🏀
                                    @elseif($s === 'volleyball') 🏐
                                    @else 🏆
                                    @endif
                                </div>
                            </div>
                            <div class="tournament-type">
                                {{ $tournament->bracket_type ?? $tournament->bracketType ?? '---' }}
                            </div>
                        </a>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-trophy"></i>
                            <h3>No tournaments found</h3>
                            <p>Create your first tournament to get started!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Tournament Modal -->
<div class="modal fade" id="addTournamentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('tournaments.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-trophy me-2"></i> Add New Tournament
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Tournament Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                        required pattern="^[a-zA-Z0-9\s]+$"
                        title="Only letters, numbers, and spaces are allowed.">
                </div>
                <div class="mb-3">
                    <label for="division" class="form-label">Division</label>
                    <input type="text" class="form-control" id="division" name="division"
                        required pattern="^[a-zA-Z0-9\s]+$"
                        title="Only letters, numbers, and spaces are allowed.">
                </div>
                <div class="mb-3">
                    <label for="tournamentDate" class="form-label">Tournament Date</label>
                    <input type="date" id="tournamentDate" name="start_date" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="sportsType" class="form-label">Sports Type</label>
                    <select id="sportsType" name="sport" class="form-select">
                        <option value="">Select a sport</option>
                        <option value="Basketball">Basketball</option>
                        <option value="Volleyball">Volleyball</option>
                       
                    </select>
                </div>
                <div class="mb-3">
                    <label for="bracketType" class="form-label">Bracket Type</label>
                    <select id="bracketType" name="bracket_type" class="form-select">
                        <option value="">Select bracket type</option>
                        <option value="single-elimination">Single Elimination</option>
                        <option value="double-elimination">Double Elimination</option>
                        <option value="round-robin">Round Robin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i> Create Tournament
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Client-side search: filter by name, sport or bracket
    const searchInput = document.getElementById('searchInput');
    const cardsSelector = '.tournament-card';
    
    function filterCards() {
        const q = (searchInput.value || '').trim().toLowerCase();
        const cards = document.querySelectorAll(cardsSelector);
        
        cards.forEach(card => {
            const name = (card.dataset.name || '').toLowerCase();
            const sport = (card.dataset.sport || '').toLowerCase();
            const bracket = (card.dataset.bracket || '').toLowerCase();
            const match = !q || name.includes(q) || sport.includes(q) || bracket.includes(q);
            card.style.display = match ? '' : 'none';
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterCards);
    }
    
    // Set min date for the modal date input
    const dateInput = document.getElementById('tournamentDate');
    if (dateInput) {
        dateInput.min = new Date().toISOString().split('T')[0];
    }
});
</script>
@endpush