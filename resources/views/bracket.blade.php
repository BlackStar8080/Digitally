<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tournament->name }} - Tournament Bracket</title>
    <style>
        @page { 
            size: A3 landscape; 
            margin: 0.5in; 
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            background: white;
            line-height: 1.4;
        }

        /* Bracket Container */
        .bracket-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 3px solid #000;
        }

        .header-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            margin-bottom: 15px;
        }

        .header img {
            max-height: 60px;
        }

        .header h1 {
            font-size: 28px;
            margin: 10px 0 5px;
            color: #000;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 14px;
            color: #333;
            margin: 5px 0;
        }

        /* Bracket Info Bar */
        .bracket-info {
            background: #000;
            color: #fff;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .bracket-info h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .bracket-info p {
            font-size: 12px;
            margin: 0;
        }

        /* Round Headers */
        .round-headers {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 20px;
        }

        .round-header {
            flex: 1;
            text-align: center;
            padding: 10px;
            background: #f0f0f0;
            border: 2px solid #000;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

        /* Horizontal bracket layout */
        .bracket-wrapper {
            display: flex;
            justify-content: center;
            padding: 20px 0;
            position: relative;
        }

        .bracket {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 80px;
            position: relative;
        }

        /* Each round is a column */
        .round {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            position: relative;
            min-width: 200px;
        }

        .matches-container {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        /* Quarterfinals spacing */
        .round-1 .matches-container {
            gap: 20px;
        }

        /* Semifinals spacing */
        .round-2 .matches-container {
            gap: 100px;
        }

        /* Finals spacing */
        .round-3 .matches-container {
            gap: 0;
        }

        .match-wrapper {
            position: relative;
        }

        /* Match box */
        .match {
            width: 180px;
            background: #c9daf8;
            border: 2px solid #000;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }

        .team {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 2px solid #000;
            font-size: 13px;
            font-weight: 600;
            color: #000;
            background: #c9daf8;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .team:last-child {
            border-bottom: none;
        }

        .team-name {
            flex: 1;
        }

        .score {
            color: #000;
            font-weight: bold;
            font-size: 14px;
            margin-left: 8px;
        }

        .match-status {
            font-size: 10px;
            text-align: center;
            padding: 5px;
            background: #f0f0f0;
            border-top: 1px solid #ccc;
            color: #666;
        }

        /* Connector lines */
        .match-wrapper::after {
            content: '';
            position: absolute;
            left: 100%;
            top: 50%;
            width: 40px;
            height: 2px;
            background: #000;
            z-index: 1;
        }

        /* Remove connector from Finals */
        .round-3 .match-wrapper::after {
            display: none;
        }

        /* Champion box */
        .champion-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 200px;
        }

        .final-winner {
            background: #ffd966;
            border: 3px solid #000;
            padding: 25px 30px;
            border-radius: 8px;
            text-align: center;
            color: #000;
            min-width: 180px;
        }

        .final-winner .label {
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .final-winner .winner-name {
            font-size: 18px;
            font-weight: bold;
            margin-top: 5px;
        }

        @media print {
            body {
                background: white !important;
            }
            .bracket-container {
                background: white !important;
            }
            @page {
                margin: 0.3in;
            }
        }
    </style>
</head>
<body>
    <div class="bracket-container">
        <!-- Header -->
        <div class="header">
            <div class="header-logos">
                @if($logoLeft)
                    <img src="data:image/png;base64,{{ $logoLeft }}" alt="Logo Left">
                @endif
                
                <div>
                    <h1>{{ $tournament->name }}</h1>
                    <p class="subtitle">Division: {{ $tournament->division ?? 'Open' }}</p>
                    <p class="subtitle">Date: {{ \Carbon\Carbon::parse($tournament->date)->format('F d, Y') }}</p>
                </div>
                
                @if($logoRight)
                    <img src="data:image/png;base64,{{ $logoRight }}" alt="Logo Right">
                @endif
            </div>
        </div>

        @foreach ($brackets as $bracket)
            @php
                $gamesByRound = $bracket->games->groupBy('round')->sortKeys();
                $totalTeams = $bracket->teams->count() ?? 8;
                $totalGames = $bracket->games->count();
                
                // Determine champion
                $finalRound = $gamesByRound->keys()->max();
                $finalGame = $gamesByRound->get($finalRound)?->first();
                $champion = 'TBD';
                
                if ($finalGame && isset($finalGame->winner_id)) {
                    if ($finalGame->winner_id == $finalGame->team1_id) {
                        $champion = $finalGame->team1->team_name ?? 'TBD';
                    } else {
                        $champion = $finalGame->team2->team_name ?? 'TBD';
                    }
                }
            @endphp

            <!-- Bracket Info -->
            <div class="bracket-info">
                <h2>{{ $bracket->name ?? 'Main Bracket' }}</h2>
                <p>{{ ucwords(str_replace('-', ' ', $bracket->type)) }} • {{ $totalTeams }} Teams • {{ $totalGames }} Games</p>
            </div>

            <!-- Round Headers -->
            <div class="round-headers">
                @foreach ($gamesByRound as $roundNum => $roundGames)
                    <div class="round-header">
                        @if($roundNum == 1) QUARTERFINALS
                        @elseif($roundNum == 2) SEMIFINALS
                        @elseif($roundNum == 3) FINALS
                        @else ROUND {{ $roundNum }}
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Bracket -->
            <div class="bracket-wrapper">
                <div class="bracket">
                    @foreach ($gamesByRound as $roundNum => $roundGames)
                        <div class="round round-{{ $roundNum }}">
                            <div class="matches-container">
                                @foreach ($roundGames as $game)
                                    <div class="match-wrapper">
                                        <div class="match">
                                            <div class="team">
                                                <span class="team-name">{{ $game->team1->team_name ?? 'TBD' }}</span>
                                                @if(isset($game->team1_score)) 
                                                    <span class="score">{{ $game->team1_score }}</span>
                                                @endif
                                            </div>
                                            <div class="team">
                                                <span class="team-name">{{ $game->team2->team_name ?? 'TBD' }}</span>
                                                @if(isset($game->team2_score)) 
                                                    <span class="score">{{ $game->team2_score }}</span>
                                                @endif
                                            </div>
                                            <div class="match-status">
                                                Game #{{ $game->match_number ?? $loop->iteration }} | 
                                                {{ strtoupper($game->status ?? 'PENDING') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Champion -->
                    <div class="champion-wrapper">
                        <div class="final-winner">
                            <div class="label">CHAMPION</div>
                            <div class="winner-name">{{ $champion }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>