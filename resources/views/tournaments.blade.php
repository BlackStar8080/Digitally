@extends('layouts.app')

@section('title', 'Tournaments')

@push('styles')
<style>
/* Toast Notification Styles */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.toast-notification {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 320px;
    max-width: 400px;
    opacity: 0;
    transform: translateX(400px);
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    border-left: 4px solid #28a745;
}

.toast-notification.show {
    opacity: 1;
    transform: translateX(0);
}

.toast-notification.hide {
    opacity: 0;
    transform: translateX(400px);
}

.toast-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745, #20c997);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.toast-content {
    flex: 1;
}

.toast-title {
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.25rem;
    font-size: 14px;
}

.toast-message {
    color: #6c757d;
    font-size: 13px;
    margin: 0;
}

.toast-close {
    background: none;
    border: none;
    color: #6c757d;
    font-size: 20px;
    cursor: pointer;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.toast-close:hover {
    background: #f0f0f0;
    color: #212529;
}

/* Fade in animation */
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

.fade-in-card {
    animation: fadeIn 0.4s ease-in;
}

/* Root Variables */
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
    position: relative;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    display: block;
    min-height: 180px;
}

.tournament-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-purple), var(--secondary-purple));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.tournament-card:hover::before {
    opacity: 1;
}

.tournament-card:hover {
    border-color: var(--primary-purple);
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(157, 78, 221, 0.13);
}

.tournament-card:hover .tournament-actions {
    opacity: 1;
}

.tournament-actions {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    gap: 6px;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 10;
}

.btn-card-action {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-card-edit {
    background: var(--primary-purple);
    color: white;
}

.btn-card-edit:hover {
    background: var(--secondary-purple);
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(157, 78, 221, 0.3);
}

.btn-card-delete {
    background: #dc3545;
    color: white;
}

.btn-card-delete:hover {
    background: #c82333;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.tournament-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding-top: 2.5rem;
}

.tournament-info {
    flex: 1;
    padding-right: 1rem;
}

.tournament-name {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 0.5rem 0;
    line-height: 1.3;
}

.tournament-date {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.tournament-date::before {
    content: '📅';
    font-size: 12px;
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
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.sport-icon.basketball {
    background: linear-gradient(135deg, #ff6b35, #ff8e53);
}

.sport-icon.volleyball {
    background: linear-gradient(135deg, #4ecdc4, #44a08d);
}

.tournament-type {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(157, 78, 221, 0.08);
    color: var(--primary-purple);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 1.5rem;
}

.tournament-type::before {
    content: '🏆';
    font-size: 14px;
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

.modal-footer .btn-primary {
    background: var(--primary-purple);
    border: none;
    color: #fff;
    transition: 0.3s ease;
}

.modal-footer .btn-primary:hover {
    background: var(--secondary-purple);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(157, 78, 221, 0.3);
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

    .tournament-actions {
        opacity: 1;
        background: rgba(255, 255, 255, 0.95);
        padding: 4px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .toast-container {
        left: 10px;
        right: 10px;
    }

    .toast-notification {
        min-width: auto;
        max-width: 100%;
    }
}
</style>
@endpush

@section('content')
<!-- Toast Notification -->
<div class="toast-container">
    <div class="toast-notification" id="successToast">
        <div class="toast-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">Success!</div>
            <p class="toast-message" id="toastMessage">Tournament has been successfully added.</p>
        </div>
        <button class="toast-close" onclick="hideToast()">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>

<div class="tournaments-page">
    <div class="container">
        <div class="page-card">
            <!-- Page Header -->
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
                    @forelse($tournaments as $index => $tournament)
                        <div class="tournament-card fade-in-card" style="animation-delay: {{ $index * 0.05 }}s"
                             data-name="{{ strtolower($tournament->name) }}"
                             data-sport="{{ strtolower($tournament->sport ?? '') }}"
                             data-bracket="{{ strtolower($tournament->bracket_type ?? $tournament->bracketType ?? '') }}"
                             data-id="{{ $tournament->id }}">
                            <div class="tournament-actions">
                                <button class="btn-card-action btn-card-edit" onclick="openEditModal({{ $tournament->id }}, event)" title="Edit Tournament">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn-card-action btn-card-delete" onclick="deleteTournament({{ $tournament->id }}, '{{ addslashes($tournament->name) }}', event)" title="Delete Tournament">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                            <a href="{{ route('tournaments.show', $tournament->id) }}" style="text-decoration: none; color: inherit; display: block;">
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
                        </div>
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
                    <select id="sportsType" name="sport" class="form-select" required>
                        <option value="">Select a sport</option>
                        <option value="Basketball">Basketball</option>
                        <option value="Volleyball">Volleyball</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="bracketType" class="form-label">Bracket Type</label>
                    <select id="bracketType" name="bracket_type" class="form-select" required>
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

<!-- Edit Tournament Modal -->
<div class="modal fade" id="editTournamentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="editTournamentForm" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i> Edit Tournament
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Tournament Name</label>
                    <input type="text" class="form-control" id="edit_name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="edit_division" class="form-label">Division</label>
                    <input type="text" class="form-control" id="edit_division" name="division" required>
                </div>
                <div class="mb-3">
                    <label for="edit_date" class="form-label">Tournament Date</label>
                    <input type="date" id="edit_date" name="start_date" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="edit_sport" class="form-label">Sports Type</label>
                    <select id="edit_sport" name="sport" class="form-select" required>
                        <option value="">Select a sport</option>
                        <option value="Basketball">Basketball</option>
                        <option value="Volleyball">Volleyball</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="edit_bracket_type" class="form-label">Bracket Type</label>
                    <select id="edit_bracket_type" name="bracket_type" class="form-select" required>
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
                    <i class="bi bi-check-lg me-2"></i> Update Tournament
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toast Notification Functions
function showToast(message) {
    const toast = document.getElementById('successToast');
    const toastMessage = document.getElementById('toastMessage');
    
    toastMessage.textContent = message;
    toast.classList.add('show');
    
    setTimeout(() => {
        hideToast();
    }, 4000);
}

function hideToast() {
    const toast = document.getElementById('successToast');
    toast.classList.remove('show');
    toast.classList.add('hide');
    
    setTimeout(() => {
        toast.classList.remove('hide');
    }, 400);
}

document.addEventListener('DOMContentLoaded', function () {
    // Check for Laravel success message
    @if(session('success'))
        showToast("{{ session('success') }}");
    @endif

    // Store tournaments data
    const tournaments = @json($tournaments);

    // Client-side search
    const searchInput = document.getElementById('searchInput');
    
    function filterCards() {
        const q = (searchInput.value || '').trim().toLowerCase();
        const cards = document.querySelectorAll('.tournament-card');
        
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
    
    // Set min date for date inputs
    const dateInput = document.getElementById('tournamentDate');
    if (dateInput) {
        dateInput.min = new Date().toISOString().split('T')[0];
    }

    // Edit Tournament Function
    window.openEditModal = function(tournamentId, event) {
        event.preventDefault();
        event.stopPropagation();
        
        const tournament = tournaments.find(t => t.id === tournamentId);
        if (!tournament) return;

        const form = document.getElementById('editTournamentForm');
        form.action = `/tournaments/${tournamentId}`;
        
        document.getElementById('edit_name').value = tournament.name || '';
        document.getElementById('edit_division').value = tournament.division || '';
        document.getElementById('edit_date').value = tournament.start_date || tournament.date || '';
        document.getElementById('edit_sport').value = tournament.sport || '';
        document.getElementById('edit_bracket_type').value = tournament.bracket_type || tournament.bracketType || '';

        const modal = new bootstrap.Modal(document.getElementById('editTournamentModal'));
        modal.show();
    };

    // Delete Tournament Function
    window.deleteTournament = function(tournamentId, tournamentName, event) {
        event.preventDefault();
        event.stopPropagation();
        
        if (confirm(`Are you sure you want to delete "${tournamentName}"? This action cannot be undone.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/tournaments/${tournamentId}`;
            
            // Get CSRF token
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                csrfToken = '{{ csrf_token() }}';
            }
            
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            
            document.body.appendChild(form);
            form.submit();
        }
    };
});
</script>
@endpush