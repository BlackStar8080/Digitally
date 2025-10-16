<!-- resources/views/brackets/printable.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tournament->name }} - Bracket</title>
    <style>
        @page {
            size: A3 landscape;
            margin: 0.5in;
        }
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #212529;
            line-height: 1.2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .bracket-container {
            width: 100%;
            overflow: hidden;
        }
        .bracket-table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
        }
        .bracket-round {
            vertical-align: top;
            padding: 10px;
        }
        .game-row {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .team-slot {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 8px;
            text-align: center;
            width: 200px;
            margin: 5px 0;
        }
        .team-slot.winner {
            background: #d4edda;
            font-weight: bold;
        }
        .team-slot.bye {
            background: #e9ecef;
            font-style: italic;
        }
        .vs-divider {
            font-weight: 700;
            color: #6c757d;
            margin: 0 10px;
        }
        .round-title {
            font-size: 14px;
            font-weight: 700;
            color: #9d4edd;
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $tournament->name }}</h1>
    </div>
    @foreach ($brackets as $bracket)
        <div class="bracket-container">
            <div class="bracket-title">{{ $bracket->name ?? 'Bracket' }}</div>
            <table class="bracket-table">
                <tr>
                    @php
                        $rounds = $bracket->games->groupBy('round');
                        $maxRounds = $rounds->keys()->max();
                    @endphp
                    @for ($round = 1; $round <= $maxRounds; $round++)
                        <td class="bracket-round">
                            <div class="round-title">Round {{ $round }}</div>
                            @foreach ($rounds[$round] ?? [] as $game)
                                <div class="game-row">
                                    <div class="team-slot {{ $game->winner_id == $game->team1_id ? 'winner' : '' }}">
                                        {{ $game->team1->team_name ?? 'TBD' }}
                                        @if ($game->team1_score !== null)
                                            ({{ $game->team1_score }})
                                        @endif
                                    </div>
                                    <div class="vs-divider">VS</div>
                                    <div class="team-slot {{ $game->winner_id == $game->team2_id ? 'winner' : '' }}">
                                        {{ $game->team2->team_name ?? 'TBD' }}
                                        @if ($game->team2_score !== null)
                                            ({{ $game->team2_score }})
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </td>
                    @endfor
                </tr>
            </table>
        </div>
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>