@extends('layouts.app')

@section('title', $team->team_name . ' - Team Information')

@push('styles')
    <style>
        /* --- Scope everything to the team page to avoid affecting the navbar/layout --- */
        .team-page { 
            background-color: #fffff; 
            min-height: 100vh;
            padding-top: 100px; /* ✅ enough space under the fixed navbar */
            padding-bottom: 40px;
        }

        /* Constrain main content */
        .team-page main {
            max-width: 1200px;
            margin: 0 auto 60px;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .team-page .back-button {
            background: linear-gradient(135deg, #9d4edd, #7c3aed);
            color: #fff;
            border: none;
            padding: 10px 20px;
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
            box-shadow: 0 2px 8px rgba(157, 78, 221, 0.10);
        }
        .team-page .back-button:hover {
            background: linear-gradient(135deg, #7c3aed, #9d4edd);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(157,78,221,.18);
            color: #fff;
        }

        .team-page .team-header-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
            margin-bottom: 30px;
            border: 1px solid #e3f2fd;
        }

        .team-page .team-header-content { 
            display: flex; 
            align-items: center; 
            gap: 25px;
            flex-wrap: wrap;
        }

        .team-page .team-logo-large {
            width: 80px; height: 80px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 32px; font-weight: bold; color: #fff;
            background: linear-gradient(135deg, #9d4edd, #7c3aed);
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(157, 78, 221, 0.10);
        }

        .team-page .team-details { flex: 1 1 auto; min-width: 180px; }
        .team-page .team-details h1 { font-size: 32px; font-weight: 700; margin: 0 0 10px; }
        .team-page .team-meta { color: #6c757d; font-size: 16px; margin: 0; }

        .team-page .info-section {
            background: white;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,.06);
            border: 1px solid #e9ecef;
        }

        .team-page .section-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 20px 25px;
            border-bottom: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .team-page .section-header h2 { font-size: 18px; font-weight: 700; margin: 0; }
        .team-page .section-header i { color: #2C7CF9; font-size: 20px; }

        .team-page .coach-info { padding: 25px; }
        .team-page .coach-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(250px,1fr)); gap: 20px; }
        .team-page .info-label { font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; }
        .team-page .info-value {
            display: inline-block;
            font-size: 16px;
            font-weight: 500;
            color: #1a1a1a;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Players table */
        .team-page .players-table-container {
            padding: 0;
            overflow-x: auto;
        }
        .team-page .players-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            table-layout: auto;
        }
        .team-page .players-table th {
            background: linear-gradient(135deg, #9d4edd, #7c3aed);
            color: #fff;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
        }
        .team-page .players-table td {
            padding: 15px 20px;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #e9ecef;
        }
        .team-page .players-table tbody tr:hover { background-color: #f8f9ff; }
        .team-page .player-name { font-weight: 600; }
        .team-page .player-number {
            background: #9d4edd; color: #fff; padding: 4px 8px;
            border-radius: 15px; font-weight: 600; font-size: 12px;
            display: inline-block; min-width: 30px; text-align: center;
        }
        .team-page .position-badge {
            background: linear-gradient(135deg,#28a745,#20c997);
            color: white; padding: 4px 10px; border-radius: 12px;
            font-size: 11px; font-weight: 600; text-transform: uppercase;
        }

        .team-page .action-buttons { display: flex; gap: 8px; }
        .team-page .action-btn {
            border: none; padding: 6px 10px; border-radius: 8px; cursor: pointer;
            transition: 0.3s; font-size: 14px; display: inline-flex; align-items: center; justify-content: center;
        }
        .team-page .edit-btn { background: #ffc107; color: #fff; }
        .team-page .edit-btn:hover { background: #e0a800; }
        .team-page .delete-btn { background: #dc3545; color: #fff; }
        .team-page .delete-btn:hover { background: #bb2d3b; }

        .team-page .empty-state { text-align: center; padding: 60px 20px; color: #6c757d; }
        .team-page .add-player-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg,#9d4edd,#7c3aed);
            color: #fff; padding: 10px 20px; border-radius: 25px;
            font-weight: 600; margin-top: 15px; text-decoration: none;
            box-shadow: 0 2px 8px rgba(157, 78, 221, 0.10);
        }
        .team-page .add-player-btn:hover {
            background: linear-gradient(135deg,#7c3aed,#9d4edd);
            color: #fff;
        }

        @media (max-width: 768px) {
            .team-page main { padding: 0 15px; }
            .team-page .team-header-content { text-align: center; justify-content: center; gap: 12px; }
            .team-page .coach-grid { grid-template-columns: 1fr; gap: 15px; }
            .team-page .section-header { padding: 15px 20px; }
            .team-page .coach-info { padding: 20px; }
        }
    </style>
@endpush

@section('content')
    <div class="team-page">
        <main>
            <a href="{{ route('teams.index') }}" class="back-button">
                <i class="bi bi-arrow-left"></i> All Teams
            </a>

            <div class="team-header-card">
                <div class="team-header-content">
                    <div class="team-logo-large">
                        {{ strtoupper(substr($team->team_name, 0, 1)) }}
                    </div>
                    <div class="team-details">
                        <h1>{{ $team->team_name }}</h1>
                        <p class="team-meta">
                            <i class="bi bi-geo-alt me-2"></i>{{ $team->address ?? 'N/A' }}
                            • <i class="bi bi-trophy ms-2 me-2"></i>{{ $team->sport ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <div class="section-header">
                    <i class="bi bi-person-badge"></i>
                    <h2>Team Information</h2>
                </div>
                <div class="coach-info">
                    <div class="coach-grid">
                        <div>
                            <div class="info-label">Coach</div>
                            <div class="info-value">{{ $team->coach_name ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="info-label">Contact</div>
                            <div class="info-value">{{ $team->contact ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="info-label">Address</div>
                            <div class="info-value">{{ $team->address ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <div class="section-header">
                    <i class="bi bi-people"></i>
                    <h2>Players Information</h2>
                </div>
                <div class="players-table-container">
                    @if($team->players->count() > 0)
                        <table class="players-table">
                            <thead>
                                <tr>
                                    <th>Player</th>
                                    <th>Age</th>
                                    <th>Number</th>
                                    <th>Position</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($team->players as $player)
                                    <tr>
                                        <td class="player-name">{{ $player->name }}</td>
                                        <td>{{ $player->age ?? '-' }}</td>
                                        <td><span class="player-number">{{ $player->number ?? '-' }}</span></td>
                                        <td><span class="position-badge">{{ $player->position ?? '-' }}</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('players.edit', $player->id) }}" class="action-btn edit-btn" title="Edit Player">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('players.destroy', $player->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete-btn" title="Delete Player">
                                                        <i class="bi bi-trash"></i>
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
                            <h3>No players found</h3>
                            <p>This team doesn't have any players yet.</p>
                            <a href="{{ route('players.create', ['team_id' => $team->id]) }}" class="add-player-btn">
                                <i class="bi bi-plus-lg"></i> Add First Player
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
@endsection
