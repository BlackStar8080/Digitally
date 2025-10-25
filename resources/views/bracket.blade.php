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
            line-height: 1.2;
        }

        /* Download buttons - EXACTLY like tallysheet */
        .download-buttons {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .download-buttons a {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-download {
            background: #28a745;
            color: white;
            border: 2px solid #28a745;
        }

        .btn-download:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }

        /* Bracket Styles - Matches your image */
        .bracket-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            background: #000;
            border-radius: 12px;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            background: linear-gradient(135deg, #1e90ff, #00bfff);
            border-radius: 12px;
            color: white;
        }

        .header img {
            max-height: 80px;
            margin: 0 20px;
        }

        .header h1 {
            font-size: 32px;
            margin: 15px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .bracket-wrapper {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            min-height: 800px;
        }

        .bracket {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            max-width: 1200px;
        }

        .round {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 30px 0;
            position: relative;
        }

        .round-title {
            font-size: 20px;
            color: #1e90ff;
            margin-bottom: 25px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .matches-column {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .match-column {
            display: flex;
            flex-direction: column;
            gap: 25px;
            align-items: center;
        }

        .match {
            width: 220px;
            background: linear-gradient(145deg, #333, #222);
            border: 3px solid #555;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 
                0 8px 20px rgba(0,0,0,0.5),
                inset 0 1px 0 rgba(255,255,255,0.1);
            position: relative;
            overflow: hidden;
        }

        .match::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1e90ff, #ffd700, #1e90ff);
        }

        .team {
            padding: 10px 8px;
            text-align: center;
            border-bottom: 2px solid #444;
            font-size: 14px;
            font-weight: 500;
        }

        .team:last-child {
            border-bottom: none;
            font-weight: bold;
            color: #1e90ff;
            background: rgba(30,144,255,0.1);
            border-radius: 6px;
            margin-top: 5px;
        }

        .score {
            color: #ffd700;
            font-weight: bold;
            font-size: 16px;
            margin-left: 8px;
        }

        .connector {
            width: 4px;
            height: 50px;
            background: linear-gradient(to bottom, #555, #1e90ff, #ffd700);
            border-radius: 2px;
            margin: 0 auto 20px;
            position: relative;
        }

        .connector::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 20px;
            background: #1e90ff;
            border-radius: 50%;
            border: 3px solid #ffd700;
        }

        .final-winner {
            background: linear-gradient(145deg, #ffd700, #ffed4e);
            border: 4px solid #1e90ff;
            padding: 30px 40px;
            border-radius: 20px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-top: 50px;
            box-shadow: 
                0 15px 35px rgba(255,215,0,0.4),
                inset 0 1px 0 rgba(255,255,255,0.3);
            color: #1e90ff;
            position: relative;
            overflow: hidden;
        }

        .final-winner::before {
            content: '🏆';
            position: absolute;
            top: -20px;
            right: -20px;
            font-size: 60px;
            opacity: 0.3;
        }

        .final-winner::after {
            content: '🏆';
            position: absolute;
            bottom: -20px;
            left: -20px;
            font-size: 60px;
            opacity: 0.3;
        }

        .trophy {
            font-size: 48px;
            display: block;
            margin-top: 15px;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
        }

        @media print {
            .download-buttons {
                display: none !important;
            }
            body {
                background: white !important;
            }
        }
    </style>
</head>
<body>
    <!-- Download Buttons - EXACTLY like tallysheet -->
    <div class="download-buttons">
        <a href="{{ route('tournament.bracket.pdf', $tournament->id) }}" class="btn-download">
            ⬇️ Download PDF
        </a>
    </div>

    <div class="bracket-container">
        <!-- Header - EXACTLY like tallysheet -->
        <div class="header">
            @if($logoLeft)
                <img src="data:image/png;base64,{{ $logoLeft }}" alt="Logo Left">
            @else
                <img src="{{ asset('images/logo/tagoloan-flag.png') }}" alt="Logo Left">
            @endif
            
            <h1>{{ $tournament->name }}<br><small>TOURNAMENT BRACKET</small></h1>
            
            @if($logoRight)
                <img src="data:image/png;base64,{{ $logoRight }}" alt="Logo Right">
            @else
                <img src="{{ asset('images/logo/mayor-logo.png') }}" alt="Logo Right">
            @endif
        </div>

        @foreach ($brackets as $bracket)
            @php
                $gamesByRound = $bracket->games->groupBy('round')->sortKeys();
                $finalGame = $gamesByRound->last()->last();
                $champion = $finalGame->winner_id == $finalGame->team1->id ? 
                    $finalGame->team1->team_name : $finalGame->team2->team_name;
            @endphp

            <div class="bracket-wrapper">
                <div class="bracket">
                    @foreach ($gamesByRound as $roundNum => $roundGames)
                        <div class="round">
                            <div class="round-title">
                                @if($roundNum == 1) Quarterfinals
                                @elseif($roundNum == 2) Semifinals
                                @elseif($roundNum == 3) Championship
                                @endif
                            </div>
                            
                            <div class="matches-column">
                                @foreach ($roundGames as $game)
                                    <div class="match-column">
                                        <div class="match">
                                            <div class="team">
                                                {{ $game->team1->team_name ?? 'TBD' }}
                                                @if(isset($game->team1_score)) 
                                                    <span class="score">{{ $game->team1_score }}</span>
                                                @endif
                                            </div>
                                            <div class="team">
                                                {{ $game->team2->team_name ?? 'TBD' }}
                                                @if(isset($game->team2_score)) 
                                                    <span class="score">{{ $game->team2_score }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="connector"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="final-winner">
                        CHAMPION<br>
                        <strong>{{ $champion }}</strong>
                        <span class="trophy">🏆</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>