{{-- resources/views/games/basketball-scoresheet.blade.php --}}

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PADAYON CUP - Basketball Scoresheet</title>
    <style>
        @font-face {
            font-family: 'Varsity';
            src: url('{{ public_path('fonts/Varsity-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: 8.5in 13in portrait;
            margin: 0.1in; /* Reduced margin to save space */
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 7px; /* Reduced base font size */
            background: white;
            padding: 0;
            line-height: 1.1; /* Tightened line height */
        }

        .sheet {
            width: 8.3in; /* Adjusted to fit within margins */
            min-height: 12.8in;
            background: white;
            padding: 0.1in; /* Reduced padding */
            border: 2px solid #000;
            margin: 0 auto;
        }

        /* Download buttons */
        .download-buttons {
            text-align: center;
            padding: 10px; /* Reduced padding */
            background: #f8f9fa;
            margin-bottom: 10px; /* Reduced margin */
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .download-buttons a {
            display: inline-block;
            padding: 8px 16px; /* Reduced padding */
            margin: 0 6px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px; /* Reduced font size */
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
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(40, 167, 69, 0.3);
        }

        .btn-view {
            background: #007bff;
            color: white;
            border: 2px solid #007bff;
        }

        .btn-view:hover {
            background: #0069d9;
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(0, 123, 255, 0.3);
        }

        /* Header with logos */
        .header {
            background: #FFFFFF;
            padding: 10px 4px; /* Reduced padding */
            border: 2px solid #000; /* Thinner border */
            margin-bottom: 3px; /* Reduced margin */
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0;
            border: none;
        }

        .logo-cell {
            width: 80px; /* Reduced width */
            text-align: center;
        }

        .text-cell {
            text-align: center;
        }

        .logo-left img,
        .logo-right img {
            width: 60px; /* Reduced logo size */
            height: 60px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        /* HEADER TEXT - VARSITY STYLE */
        .header h1 {
            font-family: Impact, 'Arial Black', sans-serif;
            font-size: 36px; /* Reduced font size */
            font-weight: 900;
            letter-spacing: 6px;
            color: #8B4513;
            margin: 0;
            padding: 4px 0;
            line-height: 1.05;
            text-transform: uppercase;
            text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.15);
            -webkit-text-stroke: 0.8px #000;
        }

        /* Rest of the styles */
        .meta-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px; /* Reduced margin */
            font-size: 7px; /* Reduced font size */
        }

        .meta-row td {
            border: 1px solid #000;
            padding: 3px 5px; /* Reduced padding */
        }

        .meta-label {
            font-weight: bold;
        }

        .main-container {
            width: 100%;
            border-collapse: collapse;
        }

        .main-container>tbody>tr>td {
            vertical-align: top;
            border: 1.5px solid #000; /* Thinner border */
        }

        .team-section {
            padding: 4px; /* Reduced padding */
            background: white;
        }

        .team-header {
            font-weight: bold;
            font-size: 8px; /* Reduced font size */
            padding: 3px;
            background: #e9ecef;
            border: 1px solid #000;
            margin-bottom: 2px;
            text-align: center;
        }

        .timeout-row,
        .teamfoul-row {
            margin: 2px 0; /* Reduced margin */
            font-size: 7px; /* Reduced font size */
        }

        .timeout-row strong,
        .teamfoul-row strong {
            display: inline-block;
            width: 45px; /* Reduced width */
            vertical-align: top;
        }

        .timeout-boxes,
        .foul-boxes {
            display: inline-block;
        }

        .timeout-boxes table,
        .foul-boxes table {
            display: inline-table;
            border-collapse: collapse;
            margin-left: 2px;
        }

        .timeout-boxes td,
        .foul-boxes td {
            border: 1px solid #000;
            width: 10px; /* Reduced size */
            height: 10px;
            text-align: center;
            padding: 0;
            font-size: 6px; /* Reduced font size */
        }

        .players-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
            font-size: 6.5px; /* Reduced font size */
        }

        .players-table th {
            background: #e9ecef;
            border: 1px solid #000;
            padding: 1.5px; /* Reduced padding */
            font-weight: bold;
            text-align: center;
        }

        .players-table td {
            border: 1px solid #000;
            padding: 1.5px;
            text-align: center;
            height: 16px; /* Reduced height */
        }

        .players-table td:nth-child(2) {
            text-align: left;
        }

        .coach-row {
            margin-top: 2px;
            font-size: 7px; /* Reduced font size */
            padding: 1.5px;
        }

        .running-score-section {
            padding: 4px; /* Reduced padding */
        }

        .running-score-header {
            font-weight: bold;
            font-size: 8px; /* Reduced font size */
            text-align: center;
            padding: 3px;
            background: #e9ecef;
            border: 1px solid #000;
            margin-bottom: 2px;
        }

        .running-score-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6px; /* Reduced font size */
        }

        .running-score-table th {
            background: #f8f9fa;
            border: 1px solid #000;
            padding: 1.5px;
            font-weight: bold;
        }

        .running-score-table td {
            border: 1px solid #000;
            height: 9px; /* Reduced height */
            padding: 0;
            text-align: center;
        }

        .scores-section {
            margin-top: 2px;
            padding: 4px; /* Reduced padding */
            border: 1px solid #000;
            background: #f8f9fa;
            font-size: 7px; /* Reduced font size */
        }

        .scores-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .scores-section td {
            padding: 1.5px; /* Reduced padding */
            border: none;
        }

        .final-score-row {
            font-weight: bold;
            font-size: 7.5px; /* Reduced font size */
            margin-top: 2px;
            padding-top: 2px;
            border-top: 1px solid #000;
        }

        .winning-team-box {
            margin-top: 2px;
            padding: 4px; /* Reduced padding */
            border: 1.5px solid #000;
            background: #d4edda;
            font-size: 7.5px; /* Reduced font size */
        }

        .officials-section {
            margin-top: 2px;
            padding: 3px; /* Reduced padding */
            border: 1px solid #000;
            font-size: 7px; /* Reduced font size */
        }

        .officials-section div {
            margin: 1.5px 0; /* Reduced margin */
        }

        .best-player-section {
            margin-top: 2px;
            padding: 4px; /* Reduced padding */
            border: 1.5px solid #000;
            background: #fff3cd;
            font-size: 7.5px; /* Reduced font size */
        }

        .best-player-section strong {
            font-size: 8px; /* Reduced font size */
        }

        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }

            .sheet {
                border: none;
                box-shadow: none;
                margin: 0;
                padding: 0.1in;
            }

            .download-buttons {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Download Buttons -->
    @if (!isset($isPdf) || !$isPdf)
        <div class="download-buttons">
            <a href="{{ route('pdf.basketball.scoresheet.download', $game->id) }}" class="btn-download">
                📥 Download PDF
            </a>
            <a href="{{ route('pdf.basketball.scoresheet.view', $game->id) }}" target="_blank" class="btn-view">
                👁️ View PDF
            </a>
        </div>
    @endif

    <div class="sheet">
        <!-- Header with Logos -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="logo-cell logo-left">
                        @if (isset($isPdf) && $isPdf && !empty($logoLeft))
                            <img src="data:image/png;base64,{{ $logoLeft }}" alt="Tagoloan Flag">
                        @else
                            <img src="{{ asset('images/logo/tagoloan-flag.png') }}" alt="Tagoloan Flag">
                        @endif
                    </td>
                    <td class="text-cell">
                        <h1>PADAYON CUP</h1>
                    </td>
                    <td class="logo-cell logo-right">
                        @if (isset($isPdf) && $isPdf && !empty($logoRight))
                            <img src="data:image/png;base64,{{ $logoRight }}" alt="Mayor Logo">
                        @else
                            <img src="{{ asset('images/logo/mayor-logo.png') }}" alt="Mayor Logo">
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- Team Names Row -->
        <table class="meta-row">
            <tr>
                <td style="width: 50%;"><span class="meta-label">Team A</span> {{ strtoupper($game->team1->team_name) }}</td>
                <td style="width: 50%;"><span class="meta-label">Team B</span> {{ strtoupper($game->team2->team_name) }}</td>
            </tr>
        </table>

        <!-- Meta Info Row 1 -->
        <table class="meta-row">
            <tr>
                <td style="width: 33%;"><span class="meta-label">Competition</span> {{ $game->bracket->tournament->name ?? '25 BELOW' }}</td>
                <td style="width: 33%;"><span class="meta-label">Date</span> {{ $game->started_at ? $game->started_at->format('m.d.Y') : date('m.d.Y') }}</td>
                <td style="width: 34%;"><span class="meta-label">Time</span> {{ $game->started_at ? $game->started_at->format('H:i') : '________' }}</td>
            </tr>
        </table>

        <!-- Meta Info Row 2 -->
        <table class="meta-row">
            <tr>
                <td style="width: 25%;"><span class="meta-label">Game No.</span> {{ $game->id }}</td>
                <td style="width: 25%;"><span class="meta-label">Place</span></td>
                <td style="width: 20%;"><span class="meta-label">Referee</span> {{ $game->referee ?? '________' }}</td>
                <td style="width: 15%;"><span class="meta-label">Umpire 1</span> {{ $game->assistant_referee_1 ?? '____' }}</td>
                <td style="width: 15%;"><span class="meta-label">Umpire 2</span> {{ $game->assistant_referee_2 ?? '____' }}</td>
            </tr>
        </table>

        <!-- Main Layout: Team A + Running Score -->
        <table class="main-container" style="margin-top: 2px;">
            <tr>
                <!-- LEFT: Team A -->
                <td style="width: 48%; padding: 0;">
                    <div class="team-section">
                        <div class="team-header">Team A {{ strtoupper($game->team1->team_name) }}</div>

                        <!-- Timeouts -->
                        <div class="timeout-row">
                            <strong>Time-outs</strong>
                            <div class="timeout-boxes">
                                <table>
                                    <tr>
                                        <td colspan="4" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 6px;">Period ①</td>
                                        <td colspan="4" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 6px;">Period ②</td>
                                    </tr>
                                    <tr>
                                        @php $team1Timeouts = $liveData['team1_timeouts'] ?? 0; @endphp
                                        @for ($i = 0; $i < 8; $i++)
                                            <td>{!! $team1Timeouts > $i ? '&#x2714;' : '&nbsp;' !!}</td>
                                        @endfor
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="timeout-row">
                            <div style="display: inline-block; width: 45px;"></div>
                            <div class="timeout-boxes">
                                <table>
                                    <tr>
                                        <td colspan="4" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 6px;">Period ③</td>
                                        <td colspan="4" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 6px;">Period ④</td>
                                    </tr>
                                    <tr>
                                        @for ($i = 0; $i < 8; $i++)
                                            <td>&nbsp;</td>
                                        @endfor
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="timeout-row" style="margin-top: 1px; margin-bottom: 2px;">
                            <div style="display: inline-block; width: 45px;"></div>
                            <strong style="width: auto;">Extra periods</strong>
                        </div>

                        <!-- Team Fouls -->
                        @php
                            $team1PeriodFouls = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                            if (isset($liveData['events']) && is_array($liveData['events'])) {
                                foreach ($liveData['events'] as $event) {
                                    if ($event['team'] === 'A' && isset($event['action']) && str_contains($event['action'], 'Foul')) {
                                        if (isset($event['period'])) {
                                            $period = (int) filter_var($event['period'], FILTER_SANITIZE_NUMBER_INT);
                                            if ($period >= 1 && $period <= 4) {
                                                $team1PeriodFouls[$period]++;
                                            }
                                        }
                                    }
                                }
                            }
                        @endphp

                        <div class="teamfoul-row">
                            <strong>Team fouls</strong>
                            <div class="foul-boxes">
                                <table>
                                    <tr>
                                        @foreach ([1, 2, 3, 4] as $q)
                                            <td colspan="5" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 6px;">Period {{ $q }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach ([1, 2, 3, 4] as $q)
                                            @for ($i = 0; $i < 5; $i++)
                                                <td>{!! $team1PeriodFouls[$q] > $i ? '/' : '&nbsp;' !!}</td>
                                            @endfor
                                        @endforeach
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Players Table -->
                        <table class="players-table">
                            <thead>
                                <tr>
                                    <th style="width: 12px;">Ln</th>
                                    <th>Players</th>
                                    <th style="width: 20px;">No.</th>
                                    <th style="width: 10px;">①</th>
                                    <th style="width: 10px;">②</th>
                                    <th style="width: 10px;">③</th>
                                    <th style="width: 10px;">④</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $team1FoulCounts = [];
                                    if (isset($liveData['events'])) {
                                        foreach ($liveData['events'] as $event) {
                                            if ($event['team'] === 'A' && isset($event['player']) && $event['player'] !== 'TEAM' && $event['player'] !== 'SYSTEM') {
                                                if (str_contains($event['action'], 'Foul')) {
                                                    $playerNum = $event['player'];
                                                    $team1FoulCounts[$playerNum] = ($team1FoulCounts[$playerNum] ?? 0) + 1;
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                @foreach ($team1Players as $index => $player)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td style="text-align: left; padding-left: 2px;">{{ $player->name }}</td>
                                        <td><strong>{{ $player->number ?? '00' }}</strong></td>
                                        @php $fouls = $team1FoulCounts[$player->number] ?? 0; @endphp
                                        <td>{!! $fouls >= 1 ? '/' : '&nbsp;' !!}</td>
                                        <td>{!! $fouls >= 2 ? '/' : '&nbsp;' !!}</td>
                                        <td>{!! $fouls >= 3 ? '/' : '&nbsp;' !!}</td>
                                        <td>{!! $fouls >= 4 ? '/' : '&nbsp;' !!}</td>
                                    </tr>
                                @endforeach
                                @for ($i = count($team1Players); $i < 12; $i++) <!-- Reduced rows to save space -->
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>

                        <div class="coach-row">
                            <div><strong>Coach:</strong> {{ $game->team1->coach_name ?? '___________________' }}</div>
                            <div><strong>Assistant Coach:</strong> _________________</div>
                        </div>
                    </div>
                </td>
                <!-- RIGHT: Running Score (Full Height) -->
                <td style="width: 52%; padding: 0;" rowspan="2">
                    <div class="running-score-section">
                        <div class="running-score-header">RUNNING SCORE</div>

                        <table class="running-score-table">
                            <thead>
                                <tr>
                                    @for ($col = 0; $col < 4; $col++)
                                        <th style="width: 6%;"></th>
                                        <th style="width: 6%;">A</th>
                                        <th style="width: 6%;"></th>
                                        <th style="width: 6%;">B</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $teamAChecks = array_fill(1, 120, false); // Reduced max score to 120
                                    $teamBChecks = array_fill(1, 120, false);
                                    if (isset($liveData['events'])) {
                                        $scoreA = 0;
                                        $scoreB = 0;
                                        $sortedEvents = array_reverse($liveData['events']);
                                        foreach ($sortedEvents as $event) {
                                            if (isset($event['points']) && $event['points'] > 0) {
                                                if ($event['team'] === 'A') {
                                                    $scoreA += $event['points'];
                                                    if ($scoreA <= 120) {
                                                        $teamAChecks[$scoreA] = true;
                                                    }
                                                } else {
                                                    $scoreB += $event['points'];
                                                    if ($scoreB <= 120) {
                                                        $teamBChecks[$scoreB] = true;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                @for ($row = 0; $row < 30; $row++) <!-- Reduced rows from 40 to 30 -->
                                    <tr>
                                        @for ($col = 0; $col < 4; $col++)
                                            @php $num = $row + 1 + ($col * 30); @endphp
                                            <td>{{ $num }}</td>
                                            <td>{!! $teamAChecks[$num] ?? false ? '&#x2714;' : '&nbsp;' !!}</td>
                                            <td>{{ $num }}</td>
                                            <td>{!! $teamBChecks[$num] ?? false ? '&#x2714;' : '&nbsp;' !!}</td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>

                        <!-- Scores Section -->
                        <div class="scores-section">
                            @php
                                $periodScores = $liveData['period_scores'] ?? [
                                    'team1' => [0, 0, 0, 0],
                                    'team2' => [0, 0, 0, 0],
                                ];
                            @endphp
                            <table>
                                <tr>
                                    <td style="width: 22%;"><strong>Scores</strong></td>
                                    <td style="width: 18%;"><strong>Period ①</strong></td>
                                    <td style="width: 18%;"><strong>A</strong> {{ $periodScores['team1'][0] ?? 0 }}</td>
                                    <td style="width: 18%;"><strong>B</strong> {{ $periodScores['team2'][0] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><strong>Period ②</strong></td>
                                    <td><strong>A</strong> {{ $periodScores['team1'][1] ?? 0 }}</td>
                                    <td><strong>B</strong> {{ $periodScores['team2'][1] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><strong>Period ③</strong></td>
                                    <td><strong>A</strong> {{ $periodScores['team1'][2] ?? 0 }}</td>
                                    <td><strong>B</strong> {{ $periodScores['team2'][2] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><strong>Period ④</strong></td>
                                    <td><strong>A</strong> {{ $periodScores['team1'][3] ?? 0 }}</td>
                                    <td><strong>B</strong> {{ $periodScores['team2'][3] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><strong>Extra periods</strong></td>
                                    <td><strong>A</strong> ____</td>
                                    <td><strong>B</strong> ____</td>
                                </tr>
                            </table>

                            <div class="final-score-row">
                                <strong>Final Score</strong>
                                <span style="margin-left: 10px;">Team A <u>{{ $liveData['team1_score'] ?? 0 }}</u></span>
                                <span style="margin-left: 20px;">Team B <u>{{ $liveData['team2_score'] ?? 0 }}</u></span>
                            </div>

                            <div class="winning-team-box">
                                <strong>Name of winning team:</strong>
                                @if (($liveData['team1_score'] ?? 0) > ($liveData['team2_score'] ?? 0))
                                    {{ $game->team1->team_name }}
                                @elseif (($liveData['team2_score'] ?? 0) > ($liveData['team1_score'] ?? 0))
                                    {{ $game->team2->team_name }}
                                @else
                                    ________________
                                @endif
                            </div>

                            <!-- Best Player Section -->
                            <div class="best-player-section">
                                @if (isset($mvpPlayer) && $mvpPlayer)
                                    <div style="margin-bottom: 2px;">
                                        <strong>BEST PLAYER:</strong>
                                        <span style="text-decoration: underline;">
                                            {{ $mvpPlayer->player->name ?? 'Unknown Player' }}
                                            (#{{ $mvpPlayer->player->number ?? '00' }})
                                        </span>
                                    </div>
                                    <table style="width: 100%; border: none;">
                                        <tr>
                                            <td style="border: none; text-align: left; padding: 1.5px;">
                                                <strong>Score:</strong> <u>{{ $mvpPlayer->points ?? 0 }}</u>
                                            </td>
                                            <td style="border: none; text-align: left; padding: 1.5px;">
                                                <strong>Assist:</strong> <u>{{ $mvpPlayer->assists ?? 0 }}</u>
                                            </td>
                                            <td style="border: none; text-align: left; padding: 1.5px;">
                                                <strong>Rebound:</strong> <u>{{ $mvpPlayer->rebounds ?? 0 }}</u>
                                            </td>
                                            <td style="border: none; text-align: left; padding: 1.5px;">
                                                <strong>Blocks:</strong> <u>{{ $mvpPlayer->blocks ?? 0 }}</u>
                                            </td>
                                            <td style="border: none; text-align: left; padding: 1.5px;">
                                                <strong>Steal:</strong> <u>{{ $mvpPlayer->steals ?? 0 }}</u>
                                            </td>
                                        </tr>
                                    </table>
                                @else
                                    <div style="margin-bottom: 2px;">
                                        <strong>BEST PLAYER:</strong> ___________________________________
                                    </div>
                                    <table style="width: 100%; border: none;">
                                        <tr>
                                            <td style="border: none; text-align: left; padding: 1.5px;">
                                                <strong>Score:</strong> _____</td>
                                            <td style="border: none; text-align: left; padding: 1.5px;">
                                                <strong>Assist:</strong> _____</td>
                                            <td style="border: none; text-align: left; padding: 1.5px;">
                                                <strong>Rebound:</strong> _____</td>
                                            <td style="border: none; text-align: left; padding: 1.5px;">
                                                <strong>Blocks:</strong> _____</td>
                                            <td style="border: none; text-align: left; padding: 1.5px;">
                                                <strong>Steal:</strong> _____</td>
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            <!-- BOTTOM ROW: Team B -->
            <tr>
                <td style="width: 48%; padding: 0;">
                    <div class="team-section">
                        <div class="team-header">Team B {{ strtoupper($game->team2->team_name) }}</div>

                        <!-- Timeouts -->
                        <div class="timeout-row">
                            <strong>Time-outs</strong>
                            <div class="timeout-boxes">
                                <table>
                                    <tr>
                                        <td colspan="4" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 6px;">Period ①</td>
                                        <td colspan="4" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 6px;">Period ②</td>
                                    </tr>
                                    <tr>
                                        @php $team2Timeouts = $liveData['team2_timeouts'] ?? 0; @endphp
                                        @for ($i = 0; $i < 8; $i++)
                                            <td>{!! $team2Timeouts > $i ? '&#x2714;' : '&nbsp;' !!}</td>
                                        @endfor
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="timeout-row">
                            <div style="display: inline-block; width: 45px;"></div>
                            <div class="timeout-boxes">
                                <table>
                                    <tr>
                                        <td colspan="4" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 6px;">Period ③</td>
                                        <td colspan="4" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 6px;">Period ④</td>
                                    </tr>
                                    <tr>
                                        @for ($i = 0; $i < 8; $i++)
                                            <td>&nbsp;</td>
                                        @endfor
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="timeout-row" style="margin-top: 1px; margin-bottom: 2px;">
                            <div style="display: inline-block; width: 45px;"></div>
                            <strong style="width: auto;">Extra periods</strong>
                        </div>

                        <!-- Team Fouls -->
                        @php
                            $team2PeriodFouls = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                            if (isset($liveData['events']) && is_array($liveData['events'])) {
                                foreach ($liveData['events'] as $event) {
                                    if ($event['team'] === 'B' && isset($event['action']) && str_contains($event['action'], 'Foul')) {
                                        if (isset($event['period'])) {
                                            $period = (int) filter_var($event['period'], FILTER_SANITIZE_NUMBER_INT);
                                            if ($period >= 1 && $period <= 4) {
                                                $team2PeriodFouls[$period]++;
                                            }
                                        }
                                    }
                                }
                            }
                        @endphp

                        <div class="teamfoul-row">
                            <strong>Team fouls</strong>
                            <div class="foul-boxes">
                                <table>
                                    <tr>
                                        @foreach ([1, 2, 3, 4] as $q)
                                            <td colspan="5" style="text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 6px;">Period {{ $q }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach ([1, 2, 3, 4] as $q)
                                            @for ($i = 0; $i < 5; $i++)
                                                <td>{!! $team2PeriodFouls[$q] > $i ? '/' : '&nbsp;' !!}</td>
                                            @endfor
                                        @endforeach
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Players Table -->
                        <table class="players-table">
                            <thead>
                                <tr>
                                    <th style="width: 12px;">Ln</th>
                                    <th>Players</th>
                                    <th style="width: 20px;">No.</th>
                                    <th style="width: 10px;">①</th>
                                    <th style="width: 10px;">②</th>
                                    <th style="width: 10px;">③</th>
                                    <th style="width: 10px;">④</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $team2FoulCounts = [];
                                    if (isset($liveData['events'])) {
                                        foreach ($liveData['events'] as $event) {
                                            if ($event['team'] === 'B' && isset($event['player']) && $event['player'] !== 'TEAM' && $event['player'] !== 'SYSTEM') {
                                                if (str_contains($event['action'], 'Foul')) {
                                                    $playerNum = $event['player'];
                                                    $team2FoulCounts[$playerNum] = ($team2FoulCounts[$playerNum] ?? 0) + 1;
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                @foreach ($team2Players as $index => $player)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td style="text-align: left; padding-left: 2px;">{{ $player->name }}</td>
                                        <td><strong>{{ $player->number ?? '00' }}</strong></td>
                                        @php $fouls = $team2FoulCounts[$player->number] ?? 0; @endphp
                                        <td>{!! $fouls >= 1 ? '/' : '&nbsp;' !!}</td>
                                        <td>{!! $fouls >= 2 ? '/' : '&nbsp;' !!}</td>
                                        <td>{!! $fouls >= 3 ? '/' : '&nbsp;' !!}</td>
                                        <td>{!! $fouls >= 4 ? '/' : '&nbsp;' !!}</td>
                                    </tr>
                                @endforeach
                                @for ($i = count($team2Players); $i < 12; $i++) <!-- Reduced rows to save space -->
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>

                        <div class="coach-row">
                            <div><strong>Coach:</strong> {{ $game->team2->coach_name ?? '___________________' }}</div>
                            <div><strong>Assistant Coach:</strong> _________________</div>
                        </div>

                        <!-- Officials Section -->
                        <div class="officials-section">
                            <div><strong>Scorekeeper:</strong> _______________________</div>
                            <div><strong>Assistant Scorekeeper:</strong> _______________________</div>
                            <div><strong>Timekeeper:</strong> _______________________</div>
                            <div><strong>24" operator:</strong> _______________________</div>
                        </div>

                        <div class="officials-section" style="margin-top: 1.5px;">
                            <div><strong>Referee:</strong> {{ $game->referee ?? '_______________________' }}</div>
                            <div><strong>Umpire 1:</strong> {{ $game->assistant_referee_1 ?? '_______________________' }}</div>
                            <div><strong>Umpire 2:</strong> {{ $game->assistant_referee_2 ?? '_______________________' }}</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>