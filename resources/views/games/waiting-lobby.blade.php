@extends('layouts.app')

@section('title', 'Waiting for Game to Start')

@push('styles')
<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.05); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .waiting-container {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 2rem;
    }

    .waiting-card {
        background: white;
        border-radius: 24px;
        padding: 3rem 2rem;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .waiting-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        background: linear-gradient(135deg, #9d4edd, #7c3aed);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        animation: pulse 2s infinite;
    }

    .waiting-icon::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 3px solid #9d4edd;
        animation: spin 3s linear infinite;
        opacity: 0.3;
    }

    .waiting-icon i {
        font-size: 60px;
        color: white;
    }

    .waiting-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .waiting-message {
        font-size: 1.1rem;
        color: #718096;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .game-info-box {
        background: linear-gradient(135deg, rgba(157, 78, 221, 0.08), rgba(124, 58, 237, 0.05));
        border: 2px solid #e9d5ff;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .game-info-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #9d4edd;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .game-info-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #fef3c7;
        color: #92400e;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .status-badge i {
        animation: pulse 1.5s infinite;
    }

    .btn-cancel {
        margin-top: 2rem;
        background: white;
        color: #718096;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .waiting-card {
            padding: 2rem 1.5rem;
        }

        .waiting-title {
            font-size: 1.5rem;
        }

        .waiting-message {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="waiting-container">
    <div class="waiting-card">
        <div class="waiting-icon">
            <i class="bi bi-hourglass-split"></i>
        </div>

        <h1 class="waiting-title">Waiting for Game to Start</h1>
        
        <p class="waiting-message">
            You've successfully joined as <strong>Stat-Keeper</strong>. 
            The scorer will start the game shortly.
        </p>

        <div class="game-info-box">
            <div class="game-info-label">Game</div>
            <div class="game-info-value">
                {{ $game->team1->team_name }} vs {{ $game->team2->team_name }}
            </div>
        </div>

        <div class="status-badge">
            <i class="bi bi-clock-history"></i>
            <span>Waiting for scorer...</span>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-cancel">
            <i class="bi bi-arrow-left me-2"></i>
            Return to Dashboard
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Poll every 3 seconds to check if game has started
    const gameId = {{ $game->id }};
    const userRole = 'stat_keeper';
    
    function checkGameStatus() {
        fetch(`/games/${gameId}/check-start-status`)
            .then(response => response.json())
            .then(data => {
                console.log('Game status:', data);
                
                if (data.game_started) {
                    // Game has started! Redirect to live interface
                    console.log('✅ Game started! Redirecting to live interface...');
                    window.location.href = `/games/${gameId}/live?role=${userRole}`;
                }
            })
            .catch(error => {
                console.error('Error checking game status:', error);
            });
    }

    // Check immediately on load
    checkGameStatus();
    
    // Then check every 3 seconds
    const pollInterval = setInterval(checkGameStatus, 3000);
    
    // Clean up interval when leaving page
    window.addEventListener('beforeunload', function() {
        clearInterval(pollInterval);
    });
</script>
@endpush