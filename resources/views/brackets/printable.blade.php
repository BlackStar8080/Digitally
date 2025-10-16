<!-- resources/views/brackets/printable.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tournament->name }} - Bracket</title>
    <style>
        @page {
            size: letter landscape;
            margin: 0.4in;
        }
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #000;
            line-height: 1.2;
        }
        
        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #000;
        }
        .header h1 {
            margin: 0 0 6px 0;
            font-size: 18px;
            color: #000;
            font-weight: 700;
        }
        
        /* Tournament Info Bar */
        .tournament-info {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-top: 6px;
            flex-wrap: wrap;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 9px;
            color: #000;
        }
        .info-label {
            font-weight: 600;
            color: #000;
        }
        .info-value {
            color: #000;
            font-weight: 500;
        }
        .info-divider {
            width: 1px;
            height: 12px;
            background: #000;
        }
        
        /* Bracket Title */
        .bracket-header {
            background: #000;
            color: white;
            padding: 6px 12px;
            margin: 10px 0 8px 0;
            text-align: center;
        }
        .bracket-title {
            font-size: 12px;
            font-weight: 700;
            margin: 0;
        }
        .bracket-subtitle {
            font-size: 8px;
            margin: 2px 0 0 0;
        }
        
        /* Bracket Container */
        .bracket-container {
            width: 100%;
            overflow: hidden;
            margin-bottom: 15px;
        }
        .bracket-table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
        }
        .bracket-round {
            vertical-align: top;
            padding: 6px 4px;
        }
        
        /* Round Title */
        .round-title {
            font-size: 9px;
            font-weight: 700;
            color: #000;
            text-align: center;
            margin-bottom: 8px;
            padding: 4px 6px;
            background: #fff;
            border: 1px solid #000;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        /* Game Row */
        .game-row {
            display: flex;
            flex-direction: column;
            margin-bottom: 12px;
            background: #fff;
            border: 1px solid #000;
            padding: 4px;
        }
        .game-matchup {
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        
        /* Team Slot */
        .team-slot {
            background: white;
            border: 1px solid #000;
            padding: 5px 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-width: 130px;
        }
        .team-slot:first-child {
            border-bottom: none;
        }
        
        .team-name {
            font-size: 9px;
            font-weight: 500;
            color: #000;
        }
        .team-score {
            font-size: 9px;
            font-weight: 700;
            color: #000;
            min-width: 20px;
            text-align: right;
        }
        
        /* Winner Styling */
        .team-slot.winner {
            background: #d8d8d8;
            border-color: #000;
        }
        .team-slot.winner .team-name {
            font-weight: 700;
            color: #000;
        }
        .team-slot.winner .team-score {
            color: #000;
        }
        
        /* TBD/Bye Styling */
        .team-slot.tbd {
            background: #f0f0f0;
            border-style: dashed;
        }
        .team-slot.tbd .team-name {
            font-style: italic;
            color: #666;
        }
        .team-slot.bye {
            background: #f0f0f0;
            border-style: dashed;
        }
        .team-slot.bye .team-name {
            font-style: italic;
            color: #666;
        }
        
        /* Game Info */
        .game-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 3px;
            padding: 3px 4px;
            font-size: 7px;
            color: #000;
            background: #f5f5f5;
            border-top: 1px solid #000;
        }
        .game-number {
            font-weight: 600;
        }
        .game-status {
            padding: 1px 4px;
            border: 1px solid #000;
            font-weight: 600;
            font-size: 6px;
            text-transform: uppercase;
            background: #fff;
        }
        
        /* Page Break */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1>{{ $tournament->name }}</h1>
        <div class="tournament-info">
            @if($tournament->division)
                <div class="info-item">
                    <span class="info-label">Division:</span>
                    <span class="info-value">{{ $tournament->division }}</span>
                </div>
                <div class="info-divider"></div>
            @endif
            
            @if($tournament->start_date)
                <div class="info-item">
                    <span class="info-label">Date:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($tournament->start_date)->format('F d, Y') }}</span>
                </div>
            @endif
        </div>
    </div>

    @foreach ($brackets as $bracket)
        <!-- Bracket Header -->
        <div class="bracket-header">
            <h2 class="bracket-title">{{ $bracket->name ?? 'Main Bracket' }}</h2>
            <p class="bracket-subtitle">
                {{ ucwords(str_replace('-', ' ', $bracket->type)) }} 
                @if($bracket->teams->count() > 0)
                    • {{ $bracket->teams->count() }} Teams
                @endif
                @if($bracket->games->count() > 0)
                    • {{ $bracket->games->count() }} Games
                @endif
            </p>
        </div>

        <!-- Bracket Container -->
        <div class="bracket-container">
            <table class="bracket-table">
                <tr>
                    @php
                        $rounds = $bracket->games->groupBy('round');
                        $maxRounds = $rounds->keys()->max();
                        
                        // Determine round names based on bracket type
                        $roundNames = [];
                        if ($bracket->type === 'round-robin-playoff') {
                            $roundNames = [1 => 'Round Robin', 2 => 'Semifinals', 3 => 'Finals'];
                        } else {
                            // For elimination brackets
                            for ($r = 1; $r <= $maxRounds; $r++) {
                                if ($r == $maxRounds) {
                                    $roundNames[$r] = 'Finals';
                                } elseif ($r == $maxRounds - 1) {
                                    $roundNames[$r] = 'Semifinals';
                                } elseif ($r == $maxRounds - 2) {
                                    $roundNames[$r] = 'Quarterfinals';
                                } else {
                                    $roundNames[$r] = 'Round ' . $r;
                                }
                            }
                        }
                    @endphp
                    
                    @for ($round = 1; $round <= $maxRounds; $round++)
                        <td class="bracket-round">
                            <div class="round-title">{{ $roundNames[$round] ?? 'Round ' . $round }}</div>
                            @foreach ($rounds[$round] ?? [] as $game)
                                <div class="game-row">
                                    <div class="game-matchup">
                                        <div class="team-slot {{ $game->winner_id == $game->team1_id ? 'winner' : ($game->team1_id ? '' : 'tbd') }} {{ $game->is_bye ?? false ? 'bye' : '' }}">
                                            <span class="team-name">{{ $game->team1->team_name ?? 'TBD' }}</span>
                                            @if ($game->team1_score !== null)
                                                <span class="team-score">{{ $game->team1_score }}</span>
                                            @endif
                                        </div>
                                        <div class="team-slot {{ $game->winner_id == $game->team2_id ? 'winner' : ($game->team2_id ? '' : 'tbd') }} {{ $game->is_bye ?? false ? 'bye' : '' }}">
                                            <span class="team-name">{{ $game->team2->team_name ?? 'TBD' }}</span>
                                            @if ($game->team2_score !== null)
                                                <span class="team-score">{{ $game->team2_score }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="game-info">
                                        <span class="game-number">Game #{{ $game->match_number ?? $game->id }}</span>
                                        <span class="game-status">
                                            {{ ucfirst($game->status ?? 'scheduled') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </td>
                    @endfor
                </tr>
            </table>
        </div>
    @endforeach
</body>
</html>