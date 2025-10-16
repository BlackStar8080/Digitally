@extends('layouts.app')

@section('title', 'Teams')

@push('styles')
<style>
/* [Previous CSS styles remain the same - keeping all existing styles] */
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

.teams-page {
    min-height: 100vh;
    background-color: var(--light-purple);
    padding: 2rem 0;
}

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
    text-decoration: none;
    color: inherit;
    position: relative;
    display: block;
}

.team-card:hover {
    border-color: var(--primary-purple);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(157, 78, 221, 0.15);
}

.team-card:hover .team-actions {
    opacity: 1;
}

.team-actions {
    position: absolute;
    top: 12px;
    right: 12px;
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
}

.btn-card-edit {
    background: var(--primary-purple);
    color: white;
}

.btn-card-edit:hover {
    background: var(--secondary-purple);
    transform: scale(1.1);
}

.btn-card-delete {
    background: #dc3545;
    color: white;
}

.btn-card-delete:hover {
    background: #c82333;
    transform: scale(1.1);
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
    overflow: hidden;
    object-fit: cover;
}

.team-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
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

/* Logo Upload Styles */
.logo-upload-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    border: 2px dashed var(--border-color);
    border-radius: 10px;
    transition: all 0.3s ease;
    cursor: pointer;
    background: #f8f9fa;
}

.logo-upload-container:hover {
    border-color: var(--primary-purple);
    background: var(--hover-purple);
}

.logo-preview {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-purple);
    color: white;
    font-size: 36px;
}

.logo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.upload-text {
    text-align: center;
    color: var(--text-muted);
}

.upload-text strong {
    color: var(--primary-purple);
    display: block;
    margin-bottom: 5px;
}

#logo, #edit_logo {
    display: none;
}

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

    .team-actions {
        opacity: 1;
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
<!-- Toast Notification -->
<div class="toast-container">
    <div class="toast-notification" id="successToast">
        <div class="toast-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">Success!</div>
            <p class="toast-message" id="toastMessage">Team has been successfully added.</p>
        </div>
        <button class="toast-close" onclick="hideToast()">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>

<div class="teams-page">
    <div class="container">
        <div class="page-card">
            <div class="page-header">
                <h1 class="page-title">Teams Management</h1>
            </div>
            
            <div class="page-content">
                <div class="controls-section">
                    <div class="filters-group">
                        <select class="filter-select" id="sportFilter">
                            <option value="all">All Sports</option>
                            @foreach ($sports as $sport)
                                <option value="{{ strtolower($sport->sports_name) }}">{{ $sport->sports_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="actions-group">
                        <div class="search-container">
                            <input type="text" class="search-input" id="searchTeams" placeholder="Search teams...">
                            <button class="search-btn" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        @if(!session('is_guest'))
                            @if ($game->isVolleyball())
                                <a href="javascript:void(0);" 
                                class="tally-sheet-btn btn btn-info"
                                onclick="openTallySheet({{ $game->id }}, 'volleyball')">
                                    <i class="bi bi-clipboard-data"></i> Tallysheet
                                </a>
                            @else
                                <a href="javascript:void(0);" 
                                class="tally-sheet-btn btn btn-info"
                                onclick="openTallySheet({{ $game->id }}, 'basketball')">
                                    <i class="bi bi-clipboard-data"></i> Tallysheet
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
                
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
                
                <form id="addTeamForm" action="{{ route('teams.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Logo Upload -->
                    <div class="mb-4">
                        <label class="form-label">Team Logo</label>
                        <div class="logo-upload-container" onclick="document.getElementById('logo').click()">
                            <div class="logo-preview" id="logoPreview">
                                <i class="bi bi-image"></i>
                            </div>
                            <div class="upload-text">
                                <strong>Click to upload logo</strong>
                                <small>PNG, JPG, GIF, SVG (Max 2MB)</small>
                            </div>
                        </div>
                        <input type="file" id="logo" name="logo" accept="image/*" onchange="previewLogo(event, 'logoPreview')">
                    </div>
                    
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
    <select class="form-control @error('sport_id') is-invalid @enderror" 
            id="sport" name="sport_id" required>
        <option value="">Select Sport</option>
        @foreach ($sports as $sport)
            <option value="{{ $sport->sports_id }}" {{ old('sport_id') == $sport->sports_id ? 'selected' : '' }}>
                {{ $sport->sports_name }}
            </option>
        @endforeach
    </select>
    @error('sport_id')
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

<!-- Edit Team Modal -->
<div class="modal fade" id="editTeamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Edit Team
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editTeamForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Logo Upload -->
                    <div class="mb-4">
                        <label class="form-label">Team Logo</label>
                        <div class="logo-upload-container" onclick="document.getElementById('edit_logo').click()">
                            <div class="logo-preview" id="editLogoPreview">
                                <i class="bi bi-image"></i>
                            </div>
                            <div class="upload-text">
                                <strong>Click to upload logo</strong>
                                <small>PNG, JPG, GIF, SVG (Max 2MB)</small>
                            </div>
                        </div>
                        <input type="file" id="edit_logo" name="logo" accept="image/*" onchange="previewLogo(event, 'editLogoPreview')">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_team_name" class="form-label">Team Name</label>
                        <input type="text" class="form-control" id="edit_team_name" name="team_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_coach_name" class="form-label">Coach Name</label>
                        <input type="text" class="form-control" id="edit_coach_name" name="coach_name">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_contact" class="form-label">Contact</label>
                        <input type="number" class="form-control" id="edit_contact" name="contact">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Address / Location</label>
                        <input type="text" class="form-control" id="edit_address" name="address">
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_sport" class="form-label">Sport</label>
                        <select class="form-control" id="edit_sport" name="sport_id" required>
                            <option value="">Select Sport</option>
                            @foreach ($sports as $sport)
                                <option value="{{ $sport->sports_id }}">{{ $sport->sports_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" form="editTeamForm">
                    <i class="bi bi-check-lg me-2"></i>Update Team
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Logo Preview Function
function previewLogo(event, previewId) {
    const file = event.target.files[0];
    const preview = document.getElementById(previewId);
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview">`;
        }
        reader.readAsDataURL(file);
    }
}

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

(function () {
    // Check for Laravel success message
    @if(session('success'))
        showToast("{{ session('success') }}");
    @endif

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

        teamsGrid.innerHTML = list.map((team, index) => {
            const name = escapeHtml(team.team_name ?? '');
            const address = escapeHtml(team.address ?? team.location ?? '');
            const playersCount = Number(team.players_count ?? 0);
            const wins = Number(team.wins ?? 0);
            const losses = Number(team.losses ?? 0);
            
            // Logo rendering
            let logoHtml;
            if (team.logo) {
                logoHtml = `<img src="/storage/${escapeHtml(team.logo)}" alt="${name} Logo">`;
            } else {
                logoHtml = escapeHtml(String((team.team_name || '').charAt(0) || '').toUpperCase());
            }

            return `
                <div class="team-card fade-in-card" style="animation-delay: ${index * 0.05}s">
                    @if(!session('is_guest'))
                        <div class="team-actions">
                            <button class="btn-card-action btn-card-edit" onclick="openEditModal({{ $team->id }}, event)" title="Edit Team">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn-card-action btn-card-delete" onclick="deleteTeam({{ $team->id }}, '{{ addslashes($team->team_name) }}', event)" title="Delete Team">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    @endif
                    <a href="/teams/${team.id}" style="text-decoration: none; color: inherit; display: block;">
                        <div class="team-header">
                            <div class="team-logo">
                                ${logoHtml}
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
                </div>
            `;
        }).join('');
    }

    function filterTeams() {
        const q = (searchInput.value || '').trim().toLowerCase();
        const selected = (sportFilter.value || 'all').toLowerCase();

        const filtered = teams.filter(team => {
            const name = (team.team_name || '').toString().toLowerCase();
            const address = (team.address || team.location || '').toString().toLowerCase();
            const sport = (team.sport?.sports_name || '').toString().toLowerCase();
            const matchSearch = !q || name.includes(q) || address.includes(q) || 
                              (team.coach_name || '').toString().toLowerCase().includes(q);
            const matchSport = selected === 'all' || (sport && sport === selected);

            return matchSearch && matchSport;
        });

        renderTeams(filtered);
    }

    // Edit Team Function
    window.openEditModal = function(teamId, event) {
        event.preventDefault();
        event.stopPropagation();
        
        const team = teams.find(t => t.id === teamId);
        if (!team) return;

        const form = document.getElementById('editTeamForm');
        form.action = `/teams/${teamId}`;
        
        document.getElementById('edit_team_name').value = team.team_name || '';
        document.getElementById('edit_coach_name').value = team.coach_name || '';
        document.getElementById('edit_contact').value = team.contact || '';
        document.getElementById('edit_address').value = team.address || '';
        document.getElementById('edit_sport').value = team.sport_id || '';        
        // Preview existing logo
        const editLogoPreview = document.getElementById('editLogoPreview');
        if (team.logo) {
            editLogoPreview.innerHTML = `<img src="/storage/${team.logo}" alt="Team Logo">`;
        } else {
            editLogoPreview.innerHTML = '<i class="bi bi-image"></i>';
        }

        const modal = new bootstrap.Modal(document.getElementById('editTeamModal'));
        modal.show();
    };

    // Delete Team Function
    window.deleteTeam = function(teamId, teamName, event) {
        event.preventDefault();
        event.stopPropagation();
        
        if (confirm(`Are you sure you want to delete ${teamName}? This action cannot be undone.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/teams/${teamId}`;
            
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