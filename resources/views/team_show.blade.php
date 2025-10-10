@extends('layouts.app')

@section('title', $team->team_name . ' - Team Information')

@push('styles')
    <style>
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .team-page { 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 40px;
        }

        .team-page main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .team-page .back-button {
            background: linear-gradient(135deg, #9d4edd, #7c3aed);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 25px;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(157, 78, 221, 0.3);
            animation: fadeInUp 0.6s ease-out;
        }

        .team-page .back-button:hover {
            background: linear-gradient(135deg, #7c3aed, #5f2da8);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(157,78,221,.4);
            color: #fff;
        }

        /* Team Header */
        .team-page .team-header-card {
            background: linear-gradient(135deg, #9d4edd, #7c3aed, #5f2da8);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(157, 78, 221, 0.3);
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out 0.1s backwards;
        }

        .team-page .team-header-card::before {
            content: '🏀';
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 6rem;
            opacity: 0.1;
        }

        .team-page .team-header-content { 
            display: flex; 
            align-items: center; 
            gap: 30px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .team-page .team-logo-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: bold;
            color: #9d4edd;
            background: white;
            flex-shrink: 0;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            border: 4px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .team-page .team-logo-large:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .team-page .team-details { flex: 1 1 auto; min-width: 180px; }
        .team-page .team-details h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 0 15px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .team-page .team-meta {
            font-size: 1.1rem;
            margin: 0;
            opacity: 0.95;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .team-page .team-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Stats Cards */
        .team-page .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .team-page .stat-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .team-page .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #9d4edd, #7c3aed);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .team-page .stat-card:hover::before {
            transform: scaleX(1);
        }

        .team-page .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(157, 78, 221, 0.15);
            border-color: #9d4edd;
        }

        .team-page .stat-card:nth-child(1) { animation-delay: 0.2s; }
        .team-page .stat-card:nth-child(2) { animation-delay: 0.3s; }
        .team-page .stat-card:nth-child(3) { animation-delay: 0.4s; }
        .team-page .stat-card:nth-child(4) { animation-delay: 0.5s; }

        .team-page .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(157, 78, 221, 0.1), rgba(124, 58, 237, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
            color: #9d4edd;
        }

        .team-page .stat-value {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #9d4edd, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
        }

        .team-page .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Info Section */
        .team-page .info-section {
            background: white;
            border-radius: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 16px rgba(0,0,0,.08);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }

        .team-page .section-header {
            background: linear-gradient(135deg, rgba(157, 78, 221, 0.1), rgba(124, 58, 237, 0.1));
            padding: 25px 30px;
            border-bottom: 2px solid rgba(157, 78, 221, 0.2);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .team-page .section-header h2 {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }

        .team-page .section-header i {
            color: #9d4edd;
            font-size: 24px;
        }

        .team-page .coach-info { padding: 30px; }

        .team-page .coach-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .team-page .info-item {
            animation: slideInLeft 0.4s ease-out;
        }

        .team-page .info-item:nth-child(1) { animation-delay: 0.1s; }
        .team-page .info-item:nth-child(2) { animation-delay: 0.2s; }
        .team-page .info-item:nth-child(3) { animation-delay: 0.3s; }

        .team-page .info-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .team-page .info-value {
            display: block;
            font-size: 1.1rem;
            font-weight: 500;
            color: #1a1a1a;
            padding: 12px 16px;
            background: linear-gradient(135deg, rgba(157, 78, 221, 0.05), rgba(124, 58, 237, 0.05));
            border-radius: 10px;
            border: 2px solid rgba(157, 78, 221, 0.1);
            transition: all 0.3s ease;
        }

        .team-page .info-value:hover {
            background: rgba(157, 78, 221, 0.1);
            border-color: #9d4edd;
            transform: translateX(4px);
        }

        /* Players Table */
        .team-page .players-table-container {
            padding: 0;
            overflow-x: auto;
        }

        .team-page .players-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .team-page .players-table thead {
            background: linear-gradient(135deg, #9d4edd, #7c3aed);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .team-page .players-table th {
            color: #fff;
            padding: 18px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .team-page .players-table td {
            padding: 18px 20px;
            font-size: 0.95rem;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
        }

        .team-page .players-table tbody tr {
            transition: all 0.2s ease;
            animation: fadeIn 0.4s ease-out;
        }

        .team-page .players-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(157, 78, 221, 0.05), transparent);
            transform: translateX(4px);
        }

        .team-page .player-name {
            font-weight: 600;
            color: #212529;
        }

        .team-page .player-number {
            background: linear-gradient(135deg, #9d4edd, #7c3aed);
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
            min-width: 40px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(157, 78, 221, 0.3);
        }

        .team-page .position-badge {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 6px 14px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }

        .team-page .action-buttons {
            display: flex;
            gap: 8px;
        }

        .team-page .action-btn {
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .team-page .edit-btn {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #fff;
        }

        .team-page .edit-btn:hover {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
        }

        .team-page .delete-btn {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: #fff;
        }

        .team-page .delete-btn:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }

        .team-page .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .team-page .empty-state i {
            font-size: 4rem;
            color: rgba(157, 78, 221, 0.3);
            margin-bottom: 20px;
        }

        .team-page .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #212529;
        }

        .team-page .add-player-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #9d4edd, #7c3aed);
            color: #fff;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            margin-top: 20px;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(157, 78, 221, 0.3);
            transition: all 0.3s ease;
        }

        .team-page .add-player-btn:hover {
            background: linear-gradient(135deg, #7c3aed, #5f2da8);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(157, 78, 221, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .team-page main { padding: 0 15px; }
            
            .team-page .team-header-content {
                text-align: center;
                justify-content: center;
                gap: 20px;
            }
            
            .team-page .team-details h1 {
                font-size: 2rem;
            }
            
            .team-page .team-meta {
                justify-content: center;
            }
            
            .team-page .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .team-page .coach-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .team-page .section-header {
                padding: 20px;
            }
            
            .team-page .coach-info {
                padding: 20px;
            }

            .team-page .players-table th,
            .team-page .players-table td {
                padding: 12px 10px;
                font-size: 0.85rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="team-page">
        <main>
            <a href="{{ route('teams.index') }}" class="back-button">
                <i class="bi bi-arrow-left"></i> All Teams
            </a>

            <!-- Team Header -->
            <div class="team-header-card">
                <div class="team-header-content">
                    <div class="team-logo-large">
                        {{ strtoupper(substr($team->team_name, 0, 1)) }}
                    </div>
                    <div class="team-details">
                        <h1>{{ $team->team_name }}</h1>
                        <p class="team-meta">
                            <span class="team-meta-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                {{ $team->address ?? 'N/A' }}
                            </span>
                            <span class="team-meta-item">
                                <i class="bi bi-trophy-fill"></i>
                                {{ $team->sport->sport_name ?? 'N/A' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-value">{{ $team->players->count() }}</div>
                    <div class="stat-label">Total Players</div>
                </div>

                @php
                    $record = $team->getRecord();
                @endphp

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div class="stat-value">{{ $record['wins'] ?? 0 }}</div>
                    <div class="stat-label">Wins</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div class="stat-value">{{ $record['losses'] ?? 0 }}</div>
                    <div class="stat-label">Losses</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="stat-value">
                        @php
                            $totalGames = ($record['wins'] ?? 0) + ($record['losses'] ?? 0);
                            $winRate = $totalGames > 0 ? round(($record['wins'] ?? 0) / $totalGames * 100) : 0;
                        @endphp
                        {{ $winRate }}%
                    </div>
                    <div class="stat-label">Win Rate</div>
                </div>
            </div>

            <!-- Team Information -->
            <div class="info-section">
                <div class="section-header">
                    <i class="bi bi-person-badge-fill"></i>
                    <h2>Team Information</h2>
                </div>
                <div class="coach-info">
                    <div class="coach-grid">
                        <div class="info-item">
                            <div class="info-label">Coach Name</div>
                            <div class="info-value">{{ $team->coach_name ?? 'Not Assigned' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Contact Number</div>
                            <div class="info-value">{{ $team->contact ?? 'Not Available' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Team Address</div>
                            <div class="info-value">{{ $team->address ?? 'Not Provided' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Players Information -->
            <div class="info-section">
                <div class="section-header">
                    <i class="bi bi-people-fill"></i>
                    <h2>Team Roster</h2>
                </div>
                <div class="players-table-container">
                    @if($team->players->count() > 0)
                        <table class="players-table">
                            <thead>
                                <tr>
                                    <th>Player Name</th>
                                    <th>Age</th>
                                    <th>Jersey #</th>
                                    <th>Position</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($team->players as $player)
                                    <tr>
                                        <td class="player-name">{{ $player->name }}</td>
                                        <td>{{ $player->age ?? '-' }}</td>
                                        <td>
                                            <span class="player-number">
                                                {{ $player->number ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="position-badge">
                                                {{ $player->position ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('players.edit', $player->id) }}" 
                                                   class="action-btn edit-btn" 
                                                   title="Edit Player">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <form action="{{ route('players.destroy', $player->id) }}" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('Are you sure you want to remove {{ $player->name }} from the team?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="action-btn delete-btn" 
                                                            title="Remove Player">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-person-plus"></i>
                            <h3>No Players Yet</h3>
                            <p>This team doesn't have any players. Start building your roster!</p>
                            <a href="{{ route('players.index') }}" class="add-player-btn">
                                <i class="bi bi-plus-lg"></i> Add Players
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
@endsection