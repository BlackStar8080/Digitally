@extends('layouts.app')

@section('title', 'Scorekeeper Panel - Manage Users')

@push('styles')
<style>
    :root {
        --primary-purple: #9d4edd;
        --secondary-purple: #7c3aed;
        --accent-purple: #5f2da8;
        --light-purple: #f3e8ff;
        --border-color: #e5e7eb;
        --text-dark: #212529;
        --text-muted: #6c757d;
        --background-light: #f8faff;
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

    .scorekeepers-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 2rem 0;
    }

    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    /* Header Banner */
    .scorekeepers-header-banner {
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

    .scorekeepers-header-banner::before {
        content: '👥';
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 5rem;
        opacity: 0.2;
    }

    .scorekeepers-header-banner h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        position: relative;
        z-index: 1;
    }

    .scorekeepers-header-banner p {
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
    .scorekeepers-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.2s backwards;
    }

    .scorekeepers-header {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        padding: 1.75rem 2rem;
        border-bottom: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .scorekeepers-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .scorekeepers-title i {
        font-size: 1.75rem;
    }

    .btn-add-scorekeeper {
        background: white;
        color: var(--primary-purple);
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-add-scorekeeper:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    /* Search Section */
    .search-section {
        padding: 1.75rem 2rem;
        border-bottom: 2px solid rgba(157, 78, 221, 0.1);
        background: linear-gradient(135deg, rgba(157, 78, 221, 0.02), rgba(124, 58, 237, 0.02));
    }

    .search-box {
        position: relative;
        max-width: 400px;
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

    .scorekeepers-table {
        width: 100%;
        border-collapse: collapse;
    }

    .scorekeepers-table thead {
        background: #d1b3ff;
    }

    .scorekeepers-table th {
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

    .scorekeepers-table th i {
        margin-right: 0.35rem;
        opacity: 0.8;
    }

    .scorekeepers-table td {
        padding: 1.25rem;
        border-bottom: 1px solid #f0f2f5;
        font-size: 0.9rem;
        color: var(--text-dark);
        vertical-align: middle;
    }

    .scorekeepers-table tbody tr {
        transition: all 0.2s ease;
        animation: slideIn 0.4s ease-out;
    }

    .scorekeepers-table tbody tr:nth-child(even) {
        background: rgba(157, 78, 221, 0.02);
    }

    .scorekeepers-table tbody tr:hover {
        background: var(--hover-purple);
        transform: translateX(4px);
        box-shadow: 0 2px 12px rgba(157, 78, 221, 0.08);
    }

    /* Badge Styles */
    .badge-id {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit, .btn-delete {
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-edit {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 16px;
        border: none;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        border-bottom: none;
        padding: 1.5rem 2rem;
    }

    .modal-title {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid var(--border-color);
        border-radius: 10px;
        padding: 10px 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--secondary-purple);
        box-shadow: 0 0 0 3px rgba(157, 78, 221, 0.1);
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(157, 78, 221, 0.3);
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
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .scorekeepers-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .btn-add-scorekeeper {
            width: 100%;
            justify-content: center;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-edit, .btn-delete {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="scorekeepers-page">
    <div class="container-fluid px-4 py-4">
        <!-- Header Banner -->
        <div class="scorekeepers-header-banner">
            <h2>👥 Scorekeeper Panel</h2>
            <p>Manage all scorekeepers and their accounts</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-value">{{ $totalScorekeepers }}</div>
                <div class="stat-label">Total Scorekeepers</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div class="stat-value">{{ $recentScorekeepers }}</div>
                <div class="stat-label">Added This Month</div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="scorekeepers-card">
            <div class="scorekeepers-header">
                <h2 class="scorekeepers-title">
                    <i class="bi bi-table"></i>
                    Scorekeeper Accounts
                </h2>
                <button class="btn-add-scorekeeper" data-bs-toggle="modal" data-bs-target="#addScorekeeperModal">
                    <i class="bi bi-plus-circle"></i>
                    Add Scorekeeper
                </button>
            </div>

            <!-- Search Section -->
            <div class="search-section">
                <form method="GET" action="{{ route('scorekeepers.index') }}">
                    <div class="search-box">
                        <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}">
                        <i class="bi bi-search"></i>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="scorekeepers-table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash"></i> ID</th>
                            <th><i class="bi bi-person"></i> Name</th>
                            <th><i class="bi bi-envelope"></i> Email</th>
                            <th><i class="bi bi-calendar-plus"></i> Joined Date</th>
                            <th><i class="bi bi-gear"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scorekeepers as $scorekeeper)
                            <tr>
                                <td><span class="badge-id">#{{ $scorekeeper->id }}</span></td>
                                <td><strong>{{ $scorekeeper->name }}</strong></td>
                                <td>{{ $scorekeeper->email }}</td>
                                <td>{{ $scorekeeper->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-edit" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editScorekeeperModal{{ $scorekeeper->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </button>
                                        <button class="btn-delete" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteScorekeeperModal{{ $scorekeeper->id }}">
                                            <i class="bi bi-trash"></i>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editScorekeeperModal{{ $scorekeeper->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bi bi-pencil-square"></i>
                                                Edit Scorekeeper
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('scorekeepers.update', $scorekeeper) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $scorekeeper->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control" value="{{ $scorekeeper->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">New Password (leave blank to keep current)</label>
                                                    <input type="password" name="password" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Confirm New Password</label>
                                                    <input type="password" name="password_confirmation" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn-primary-custom">Update Scorekeeper</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteScorekeeperModal{{ $scorekeeper->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                Confirm Delete
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('scorekeepers.destroy', $scorekeeper) }}">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete <strong>{{ $scorekeeper->name }}</strong>?</p>
                                                <p class="text-danger">This action cannot be undone.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <h3>No Scorekeepers Found</h3>
                                        <p>Start by adding your first scorekeeper!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($scorekeepers->hasPages())
                <div class="pagination-wrapper">
                    {{ $scorekeepers->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Scorekeeper Modal -->
<div class="modal fade" id="addScorekeeperModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus"></i>
                    Add New Scorekeeper
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('scorekeepers.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-custom">Add Scorekeeper</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection