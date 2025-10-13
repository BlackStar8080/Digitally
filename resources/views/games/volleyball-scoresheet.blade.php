<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volleyball Scoresheet - {{ $game->team1->team_name }} vs {{ $game->team2->team_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }

        .scoresheet {
            background: white;
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .game-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .info-item {
            display: flex;
            gap: 10px;
        }

        .info-label {
            font-weight: bold;
            min-width: 100px;
        }

        .final-score-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, #4E56C0, #696FC7);
            color: white;
            border-radius: 12px;
        }

        .final-score-title {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .final-score-display {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            font-size: 36px;
            font-weight: bold;
        }

        .set-scores-table {
            margin: 30px 0;
        }

        .set-scores-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .set-scores-table th,
        .set-scores-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        .set-scores-table th {
            background: #f0f0f0;
            font-weight: bold;
        }

        .winner-cell {
            background: #d4edda;
            font-weight: bold;
        }

        .stats-section {
            margin-top: 40px;
        }

        .stats-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 10px;
            background: #f0f0f0;
            border-left: 4px solid #4E56C0;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .stats-table th,
        .stats-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .stats-table th {
            background: #f8f9fa;
            font-weight: bold;
            font-size: 12px;
        }

        .stats-table td {
            font-size: 14px;
        }

        .player-name {
            text-align: left;
            font-weight: 600;
        }

        .totals-row {
            background: #f8f9fa;
            font-weight: bold;
            border-top: 2px solid #333;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4E56C0;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .print-btn:hover {
            background: #3d4596;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .scoresheet {
                box-shadow: none;
                padding: 0;
            }

            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Print Scoresheet</button>

    <div class="scoresheet">
        <!-- Header -->
        <div class="header">
            <h1>VOLLEYBALL MATCH SCORESHEET</h1>
            @if($game->bracket && $game->bracket->tournament)
                <p style="font-size: 16px; color: #666; margin-top: 10px;">
                    {{ $game->bracket->tournament->name }}
                </p>
            @endif
        </div>

        <!-- Game Information -->
        <div class="game-info">
            <div class="info-item">
                <span class="info-label">Date:</span>
                <span>{{ $game->completed_at ? $game->completed_at->format('F j, Y') : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Referee:</span>
                <span>{{ $game->referee ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Team A:</span>
                <span>{{ $game->team1->team_name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Team B:</span>
                <span>{{ $game->team2->team_name }}</span>
            </div>
        </div>

        <!-- Final Score -->
        <div class="final-score-section">
            <div class="final-score-title">FINAL SCORE</div>
            <div class="final-score-display">
                <div>
                    <div style="font-size: 16px; opacity: 0.9; margin-bottom: 5px;">
                        {{ $game->team1->team_name }}
                    </div>
                    <div>{{ $game->team1_score }}</div>
                    @if($game->winner_id === $game->team1_id)
                        <div style="font-size: 14px; margin-top: 5px;">🏆 WINNER</div>
                    @endif
                </div>
                <div style="opacity: 0.7;">-</div>
                <div>
                    <div style="font-size: 16px; opacity: 0.9; margin-bottom: 5px;">
                        {{ $game->team2->team_name }}
                    </div>
                    <div>{{ $game->team2_score }}</div>
                    @if($game->winner_id === $game->team2_id)
                        <div style="font-size: 14px; margin-top: 5px;">🏆 WINNER</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Set Scores -->
        @if($liveData && isset($liveData['set_scores']))
            <div class="set-scores-table">
                <h3 style="margin-bottom: 15px;">Set Scores</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Set</th>
                            <th>{{ $game->team1->team_name }}</th>
                            <th>{{ $game->team2->team_name }}</th>
                            <th>Winner</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($liveData['set_scores'] as $setScore)
                            <tr>
                                <td><strong>Set {{ $setScore['set'] }}</strong></td>
                                <td class="{{ $setScore['team1'] > $setScore['team2'] ? 'winner-cell' : '' }}">
                                    {{ $setScore['team1'] }}
                                </td>
                                <td class="{{ $setScore['team2'] > $setScore['team1'] ? 'winner-cell' : '' }}">
                                    {{ $setScore['team2'] }}
                                </td>
                                <td>
                                    @if($setScore['team1'] > $setScore['team2'])
                                        {{ $game->team1->team_name }}
                                    @else
                                        {{ $game->team2->team_name }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Team 1 Statistics -->
        <div class="stats-section">
            <div class="stats-title">{{ $game->team1->team_name }} - Player Statistics</div>
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Player Name</th>
                        <th>Kills</th>
                        <th>Aces</th>
                        <th>Blocks</th>
                        <th>Digs</th>
                        <th>Assists</th>
                        <th>Errors</th>
                        <th>Total Pts</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $team1Stats = $game->volleyballPlayerStats()
                            ->where('team_id', $game->team1_id)
                            ->with('player')
                            ->get();
                    @endphp

                    @forelse($team1Stats as $stat)
                        <tr>
                            <td><strong>{{ $stat->player->number ?? '00' }}</strong></td>
                            <td class="player-name">
                                {{ $stat->player->name }}
                                @if($stat->is_mvp) ⭐ @endif
                            </td>
                            <td>{{ $stat->kills }}</td>
                            <td>{{ $stat->aces }}</td>
                            <td>{{ $stat->blocks }}</td>
                            <td>{{ $stat->digs }}</td>
                            <td>{{ $stat->assists }}</td>
                            <td>{{ $stat->errors }}</td>
                            <td><strong>{{ $stat->getTotalPoints() }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 20px;">
                                No statistics available
                            </td>
                        </tr>
                    @endforelse

                    @if($team1Stats->count() > 0)
                        <tr class="totals-row">
                            <td colspan="2">TEAM TOTALS</td>
                            <td>{{ $team1Stats->sum('kills') }}</td>
                            <td>{{ $team1Stats->sum('aces') }}</td>
                            <td>{{ $team1Stats->sum('blocks') }}</td>
                            <td>{{ $team1Stats->sum('digs') }}</td>
                            <td>{{ $team1Stats->sum('assists') }}</td>
                            <td>{{ $team1Stats->sum('errors') }}</td>
                            <td><strong>{{ $team1Stats->sum(fn($s) => $s->getTotalPoints()) }}</strong></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Team 2 Statistics -->
        <div class="stats-section">
            <div class="stats-title">{{ $game->team2->team_name }} - Player Statistics</div>
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Player Name</th>
                        <th>Kills</th>
                        <th>Aces</th>
                        <th>Blocks</th>
                        <th>Digs</th>
                        <th>Assists</th>
                        <th>Errors</th>
                        <th>Total Pts</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $team2Stats = $game->volleyballPlayerStats()
                            ->where('team_id', $game->team2_id)
                            ->with('player')
                            ->get();
                    @endphp

                    @forelse($team2Stats as $stat)
                        <tr>
                            <td><strong>{{ $stat->player->number ?? '00' }}</strong></td>
                            <td class="player-name">
                                {{ $stat->player->name }}
                                @if($stat->is_mvp) ⭐ @endif
                            </td>
                            <td>{{ $stat->kills }}</td>
                            <td>{{ $stat->aces }}</td>
                            <td>{{ $stat->blocks }}</td>
                            <td>{{ $stat->digs }}</td>
                            <td>{{ $stat->assists }}</td>
                            <td>{{ $stat->errors }}</td>
                            <td><strong>{{ $stat->getTotalPoints() }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 20px;">
                                No statistics available
                            </td>
                        </tr>
                    @endforelse

                    @if($team2Stats->count() > 0)
                        <tr class="totals-row">
                            <td colspan="2">TEAM TOTALS</td>
                            <td>{{ $team2Stats->sum('kills') }}</td>
                            <td>{{ $team2Stats->sum('aces') }}</td>
                            <td>{{ $team2Stats->sum('blocks') }}</td>
                            <td>{{ $team2Stats->sum('digs') }}</td>
                            <td>{{ $team2Stats->sum('assists') }}</td>
                            <td>{{ $team2Stats->sum('errors') }}</td>
                            <td><strong>{{ $team2Stats->sum(fn($s) => $s->getTotalPoints()) }}</strong></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #ddd; text-align: center; color: #666;">
            <p>Official Volleyball Scoresheet</p>
            <p style="margin-top: 10px; font-size: 12px;">
                Generated on {{ now()->format('F j, Y \a\t g:i A') }}
            </p>
        </div>
    </div>
</body>
</html>