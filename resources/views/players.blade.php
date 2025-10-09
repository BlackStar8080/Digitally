@extends('layouts.app')

@section('title', 'Players')
@section('players-active', 'active')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">

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

    /* Table Row Animation */
    @keyframes slideInRow {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .new-row-animation {
        animation: slideInRow 0.5s ease-out;
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

    /* Modal Animation Enhancement */
    .modal.fade .modal-dialog {
        transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .modal.show .modal-dialog {
        transform: scale(1);
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

    .action-buttons {
        display: flex;
        gap: 6px;
    }

    .btn-action {
        padding: 6px 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-edit {
        background: var(--primary-purple);
        color: white;
    }

    .btn-edit:hover {
        background: var(--secondary-purple);
        transform: translateY(-1px);
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
        transform: translateY(-1px);
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

    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        max-width: 700px;
        width: 100%;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: #fff;
        border: none;
        padding: 1.5rem 2rem;
        box-shadow: 0 2px 8px rgba(157, 78, 221, 0.10);
    }

    .modal-title {
        font-weight: 700;
        font-size: 20px;
        margin: 0;
    }

    .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .btn-close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 2rem;
        display: flex;
        flex-direction: row;
        gap: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
        width: 100%;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control,
    .form-select {
        width: 100%;
        padding: 12px 14px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--secondary-purple);
        box-shadow: 0 0 0 3px rgba(157, 78, 221, 0.10);
    }

    .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid #e0c3fc;
        background: #f3e8ff;
    }

    .btn-modal {
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: var(--primary-purple);
        color: #fff;
        box-shadow: 0 2px 8px rgba(157, 78, 221, 0.10);
    }

    .btn-primary:hover {
        background: var(--secondary-purple);
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #a084ca;
        color: #fff;
    }

    .btn-secondary:hover {
        background: #7c3aed;
        transform: translateY(-1px);
    }

    .alert {
        border: none;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 1.5rem;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
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

        .players-table th,
        .players-table td {
            padding: 10px 8px;
            font-size: 13px;
        }

        .action-buttons {
            flex-direction: column;
            gap: 4px;
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

    .pagination {
        display: inline-flex;
        gap: 6px;
    }

    .pagination .page-link {
        border-radius: 8px !important;
        border: 1px solid var(--border-color);
        color: var(--primary-purple);
        font-weight: 600;
    }

    .pagination .page-link:hover {
        background: var(--primary-purple);
        color: white;
    }

    .pagination .active .page-link {
        background: var(--primary-purple);
        color: white;
        border-color: var(--primary-purple);
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
                <p class="toast-message" id="toastMessage">Player has been successfully added.</p>
            </div>
            <button class="toast-close" onclick="hideToast()">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>

    <div class="players-page">
        <div class="container">
            <div class="page-card">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Players Management</h1>
                </div>
                <div class="page-content">
                    <div class="controls-section">
                        <form method="GET" action="{{ route('players.index') }}" class="filters-group">
                            <select name="team_id" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Teams</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}"
                                        {{ request('team_id') == $team->id ? 'selected' : '' }}>
                                        {{ $team->team_name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="position" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Positions</option>
                                <option value="Shooting Guard"
                                    {{ request('position') == 'Shooting Guard' ? 'selected' : '' }}>Shooting Guard</option>
                                <option value="Point Guard" {{ request('position') == 'Point Guard' ? 'selected' : '' }}>
                                    Point Guard</option>
                                <option value="Center" {{ request('position') == 'Center' ? 'selected' : '' }}>Center
                                </option>
                                <option value="Small Forward"
                                    {{ request('position') == 'Small Forward' ? 'selected' : '' }}>Small Forward</option>
                                <option value="Power Forward"
                                    {{ request('position') == 'Power Forward' ? 'selected' : '' }}>Power Forward</option>
                                <option value="Libero" {{ request('position') == 'Libero' ? 'selected' : '' }}>Libero
                                </option>
                                <option value="Setter" {{ request('position') == 'Setter' ? 'selected' : '' }}>Setter
                                </option>
                                <option value="Middle Blocker"
                                    {{ request('position') == 'Middle Blocker' ? 'selected' : '' }}>Middle Blocker</option>
                                <option value="Outside Hitter"
                                    {{ request('position') == 'Outside Hitter' ? 'selected' : '' }}>Outside Hitter</option>
                                <option value="Opposite Hitter"
                                    {{ request('position') == 'Opposite Hitter' ? 'selected' : '' }}>Opposite Hitter
                                </option>
                            </select>

                            <select name="sport" class="filter-select" onchange="this.form.submit()">
                            <option value="">All Sports</option>
                            @foreach($sports as $sport)
                                <option value="{{ $sport->sports_id }}" {{ request('sport') == $sport->sports_id ? 'selected' : '' }}>
                                    {{ $sport->sports_name }}
                                </option>
                            @endforeach
                        </select>
                        </form>

                        <div class="actions-group">
                            <div class="search-container">
                                <input type="text" class="search-input" placeholder="Search players..." id="searchInput">
                                <button class="search-btn" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <button class="add-btn" type="button" onclick="openModal()">
                                <i class="bi bi-plus-circle"></i>
                                Add Player
                            </button>
                        </div>
                    </div>

                    <!-- Players Table -->
                    <div class="table-container">
                        <table class="players-table">
                            <thead>
                                <tr>
                                    <th>Player</th>
                                    <th>Age</th>
                                    <th>Position</th>
                                    <th>Number</th>
                                    <th>Sport</th>
                                    <th>Team</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="playerTable">
                                @forelse($players as $player)
                                    <tr>
                                        <td><strong>{{ $player->name }}</strong></td>
                                        <td>{{ $player->age ?? '-' }}</td>
                                        <td>{{ $player->position ?? '-' }}</td>
                                        <td>
                                            @if ($player->number)
                                                <span
                                                    style="background: var(--primary-purple); color: white; padding: 2px 8px; border-radius: 4px; font-weight: 600; font-size: 12px;">
                                                    #{{ $player->number }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $player->sport->sports_name ?? '-' }}</td>
                                        <td>{{ $player->team->team_name ?? '-' }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button" class="btn-action btn-edit"
                                                    onclick="openEditFromButton(this)"
                                                    data-update-url="{{ route('players.update', $player->id) }}"
                                                    data-id="{{ $player->id }}" 
                                                    data-name="{{ $player->name }}"
                                                    data-team-id="{{ $player->team_id }}"
                                                    data-number="{{ $player->number }}" 
                                                    data-sport="{{ $player->sports_id }}"
                                                    data-position="{{ $player->position }}" 
                                                    data-age="{{ $player->age }}"
                                                    title="Edit Player">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <form action="{{ route('players.destroy', $player->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-action btn-delete"
                                                        onclick="return confirm('Are you sure you want to delete {{ $player->name }}?')"
                                                        title="Delete Player">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <div class="empty-icon">
                                                    <i class="bi bi-people"></i>
                                                </div>
                                                <p><strong>No players found</strong></p>
                                                <p>Start by adding your first player to the system.</p>
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
    </div>

    @if ($players->total() > 15)
        <div style="margin-top: 20px; text-align: center;">
            {{ $players->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <!-- Player Modal -->
    <div class="modal fade" id="playerModal" tabindex="-1" aria-labelledby="playerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">                   
                    <h5 class="modal-title" id="modalTitle">
                        <i class="bi bi-person-add"></i> Add New Player</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="playerForm" method="POST" action="{{ route('players.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body">
                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger" style="width:100%">
                                <ul style="margin: 0; padding-left: 18px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div style="display: flex; flex-direction: row; gap: 2rem; width: 100%;">
                            <div style="flex:1; min-width: 0;">
                                <div class="form-group">
                                    <label class="form-label">Player Name</label>
                                    <input type="text" name="name" id="playerName" class="form-control"
                                        value="{{ old('name') }}" required pattern="^[a-zA-Z0-9\s]+$"
                                        title="Only letters, numbers, and spaces are allowed." placeholder="Enter player name">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Team</label>
                                    <select name="team_id" id="playerTeam" class="form-select" required>
                                        <option value="">Select team</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Jersey Number</label>
                                    <input type="number" name="number" id="playerNumber" class="form-control"
                                        placeholder="Enter jersey number" min="0" max="99">
                                </div>
                            </div>
                            <div style="flex:1; min-width: 0;">
                                <div class="form-group">
                                    <label class="form-label">Sport</label>
                                    <select name="sports_id" id="sportSelect" class="form-select" required>
                                        <option value="">Select sport</option>
                                        @foreach($sports as $sport)
                                            <option value="{{ $sport->sports_id }}">{{ $sport->sports_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Position</label>
                                    <select name="position" id="positionSelect" class="form-select" required>
                                        <option value="">Select position</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Birthday</label>
                                    <input type="date" name="birthday" id="playerBirthday" class="form-control"
                                        placeholder="Select birthday" max="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal btn-primary">Save Player</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Toast Notification Functions
        function showToast(message) {
            const toast = document.getElementById('successToast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            toast.classList.add('show');
            
            // Auto hide after 4 seconds
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

        document.addEventListener('DOMContentLoaded', function() {
            // Check for Laravel success message
            @if(session('success'))
                showToast("{{ session('success') }}");
            @endif

            const playerModalEl = document.getElementById('playerModal');
            const bsPlayerModal = new bootstrap.Modal(playerModalEl);

            const playerForm = document.getElementById('playerForm');
            const formMethodInput = document.getElementById('formMethod');
            const modalTitleEl = document.getElementById('modalTitle');

            const playerName = document.getElementById('playerName');
            const playerTeam = document.getElementById('playerTeam');
            const playerNumber = document.getElementById('playerNumber');
            const sportSelect = document.getElementById('sportSelect');
            const positionSelect = document.getElementById('positionSelect');
            const playerBirthday = document.getElementById('playerBirthday');

            const positions = {
            @foreach($sports as $sport)
                {{ $sport->sports_id }}: [
                    @if($sport->sports_name == 'Basketball')
                        "Point Guard", "Shooting Guard", "Small Forward", "Power Forward", "Center"
                    @elseif($sport->sports_name == 'Volleyball')
                        "Setter", "Middle Blocker", "Outside Hitter", "Opposite Hitter", "Libero"
                    @endif
                ],
            @endforeach
        };

            window.openModal = function() {
                playerForm.reset();
                playerForm.action = "{{ route('players.store') }}";
                formMethodInput.value = "POST";
                modalTitleEl.innerHTML = '<i class="bi bi-person-add"></i> Add New Player';
                positionSelect.innerHTML = '<option value="">Select position</option>';
                bsPlayerModal.show();
            };

            window.openEditFromButton = function(button) {
                    playerForm.reset();
                    playerForm.action = button.dataset.updateUrl;
                    formMethodInput.value = "PUT";
                    modalTitleEl.innerHTML = '<i class="bi bi-pencil-square"></i> Edit Player';

                    playerName.value = button.dataset.name || '';
                    playerTeam.value = button.dataset.teamId || '';
                    playerNumber.value = button.dataset.number || '';

                    const sportId = button.dataset.sport || ''; // This is now sports_id
                    sportSelect.value = sportId;

                    positionSelect.innerHTML = '<option value="">Select position</option>';
                    if (positions[sportId]) { // Use sportId (which is sports_id)
                        positions[sportId].forEach(pos => {
                            const opt = document.createElement('option');
                            opt.value = pos;
                            opt.textContent = pos;
                            if (pos === button.dataset.position) opt.selected = true;
                            positionSelect.appendChild(opt);
                        });
                    }

                    bsPlayerModal.show();
                };

            sportSelect.addEventListener('change', function() {
                const sportId = this.value; // This is now sports_id (1, 2, etc.)
                positionSelect.innerHTML = '<option value="">Select position</option>';
                if (positions[sportId]) {
                    positions[sportId].forEach(pos => {
                        const opt = document.createElement('option');
                        opt.value = pos;
                        opt.textContent = pos;
                        positionSelect.appendChild(opt);
                    });
                }
            });

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const search = this.value.toLowerCase();
                    document.querySelectorAll('#playerTable tr').forEach(row => {
                        row.style.display = row.innerText.toLowerCase().includes(search) ? '' :
                            'none';
                    });
                });
            }

            // Add fade-in animation to existing table rows
            document.querySelectorAll('#playerTable tr').forEach((row, index) => {
                row.style.animationDelay = `${index * 0.05}s`;
                row.classList.add('fade-in-row');
            });

            @if ($errors->any())
                // Auto-open the modal if there are validation errors
                bsPlayerModal.show();
            @endif
        });
    </script>
@endsection