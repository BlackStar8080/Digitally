<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PADAYON CUP - Basketball Scoresheet</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    @page {
        size: 8.5in 13in portrait;
        margin: 0.15in;
    }

    body {
       font-family: DejaVu Sans, Arial, sans-serif; /* DejaVu supports checkmarks */
        font-size: 7px;
        background: #f0f0f0;
        padding: 5px;
        line-height: 1.1;
    }

    .sheet {
        width: 8.2in;
        height: 12.7in;
        background: white;
        padding: 0.15in;
        border: 1px solid #000;
        margin: 0 auto;
    }

    /* Header */
    .header {
        text-align: center;
        background: #FFD700;
        padding: 4px;
        border: 2px solid #000;
        margin-bottom: 3px;
        border-radius: 3px;
    }

    .header h1 {
        font-size: 16px;
        font-weight: bold;
        letter-spacing: 2px;
        color: #8B4513;
        margin: 0;
        line-height: 1;
    }

    .header p {
        font-size: 7px;
        color: #8B4513;
        margin: 1px 0 0 0;
        font-weight: 600;
    }

    .logo {
        display: inline-block;
        width: 28px;
        height: 28px;
        border: 2px solid #000;
        border-radius: 50%;
        text-align: center;
        line-height: 28px;
        font-size: 10px;
        color: #8B4513;
        font-weight: bold;
        vertical-align: middle;
        background: white;
    }

    /* Meta section */
    .meta-section {
        margin-bottom: 4px;
    }

    .meta-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 6.5px;
    }

    .meta-table td {
        border: 1px solid #ccc;
        padding: 2px 3px;
    }

    .meta-title {
        font-weight: bold;
        color: #333;
    }

    /* Replaces Flex/Grid layout with Table layout for DomPDF */
    .main-layout {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 3px;
    }

    .main-layout td {
        vertical-align: top;
        padding: 2px;
    }

    /* Teams and running score boxes */
    .box {
        border: 2px solid #000;
        background: white;
        border-radius: 3px;
        padding: 3px;
        margin-bottom: 3px;
    }

    .box-header {
        font-weight: bold;
        font-size: 7.5px;
        margin-bottom: 2px;
        padding: 2px;
        border-bottom: 2px solid #000;
        background: #FFD700;
        text-align: center;
        color: #8B4513;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 5.5px;
    }

    td, th {
        border: 1px solid #000;
        padding: 1px;
        text-align: center;
    }

    .players-table th {
        background: #e9ecef;
        font-weight: 700;
    }

    /* Running Score table fix */
    .running-score-table th {
        background: #f8f9fa;
        font-weight: bold;
        font-size: 6px;
    }

    .running-score-table td {
        height: 8px;
    }

    /* Bottom section layout fix */
    .bottom-layout {
        width: 100%;
        border-collapse: collapse;
        margin-top: 4px;
    }

    .bottom-layout td {
        vertical-align: top;
        padding: 3px;
    }

    .winner-section {
        padding: 2px;
        border-top: 2px solid #000;
        background: #d4edda;
        font-size: 6px;
        margin-top: 3px;
    }

    .best-player-section {
        padding: 3px;
        border-top: 2px solid #000;
        background: #fff3cd;
        font-size: 6px;
        margin-top: 3px;
    }

    /* Officials table fix */
    .officials-content td {
        padding: 2px;
        border: none;
        font-size: 6px;
    }

    /* Print compatibility */
    @media print {
        body {
            background: white;
            margin: 0;
            padding: 0;
        }

        .sheet {
            border: none;
            box-shadow: none;
            page-break-after: avoid;
        }

        .download-buttons {
            display: none !important;
        }
    }
</style>

    </style>
</head>

<body>

    <div class="download-buttons">
        <a href="{{ route('pdf.basketball.scoresheet.download', $game->id) }}" class="btn btn-success">
            📥 Download
        </a>
        <a href="{{ route('pdf.basketball.scoresheet.view', $game->id) }}" class="btn btn-primary" target="_blank">
            👁️ View
        </a>
    </div>

    <div class="sheet">

        <!-- Header -->
        <div class="header">
            <span class="logo">🏀</span>
            <h1>PADAYON CUP</h1>
            <p>Basketball Scoresheet</p>
            <span class="logo">🏆</span>
        </div>

        <!-- Meta Information -->
        <div class="meta-section">
            <table class="meta-table">
                <tr>
                    <td><span class="meta-title">Team A:</span> {{ $game->team1->team_name }}</td>
                    <td><span class="meta-title">Team B:</span> {{ $game->team2->team_name }}</td>
                    <td><span class="meta-title">Competition:</span> {{ $game->bracket->tournament->name ?? '26 ABOVE' }}</td>
                </tr>
                <tr>
                    <td><span class="meta-title">Date:</span> {{ $game->started_at ? $game->started_at->format('m/d/Y') : date('m/d/Y') }}</td>
                    <td><span class="meta-title">Time:</span> {{ $game->started_at ? $game->started_at->format('H:i') : '__:__' }}</td>
                    <td><span class="meta-title">Game No.:</span> {{ $game->id }}</td>
                </tr>
                <tr>
                    <td><span class="meta-title">Place:</span> CASINGLOT COURT</td>
                    <td><span class="meta-title">Referee:</span> {{ $game->referee ?? '________' }}</td>
                    <td><span class="meta-title">Umpires:</span> {{ $game->assistant_referee_1 ?? '_____' }}, {{ $game->assistant_referee_2 ?? '_____' }}</td>
                </tr>
            </table>
        </div>

        <!-- Main Layout: Teams + Running Score -->
        <table class="main-layout">
            <tr>
                <!-- Left Column: Teams -->
                <td style="width:2.9in;">

                    <div class="teams-section">

                        <!-- Team A -->
                        <div class="box">
                            <div class="box-header">TEAM A: {{ strtoupper($game->team1->team_name) }}</div>

                            <div class="period-labels">
                                <strong>Timeouts:</strong>
                                <table style="margin-left:4px;">
                                    <tr>
                                        @php $team1Timeouts = $liveData['team1_timeouts'] ?? 0; @endphp
                                        @for ($i = 0; $i < 8; $i++)
                                            <td style="border:1px solid #000; width:10px; height:9px; text-align:center;">
                                                {!! $team1Timeouts > $i ? '&#x2714;' : '&nbsp;' !!}
                                            </td>
                                        @endfor
                                    </tr>
                                </table>
                            </div>

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

                            <div class="team-fouls-section">
                                <strong>Team Fouls:</strong>
                                <table style="width:100%; margin-top:2px;">
                                    <tr>
                                        @foreach ([1,2,3,4] as $q)
                                            <td>Q{{ $q }}</td>
                                            @for ($i = 0; $i < 4; $i++)
                                                <td style="border:1px solid #000; width:10px; height:9px; text-align:center;">
                                                    {!! $team1PeriodFouls[$q] > $i ? '/' : '&nbsp;' !!}
                                                </td>
                                            @endfor
                                        @endforeach
                                    </tr>
                                </table>
                            </div>

                            <table class="players-table">
                                <thead>
                                    <tr>
                                        <th style="width:14px;">Ln</th>
                                        <th>Player Name</th>
                                        <th style="width:16px;">No</th>
                                        <th style="width:9px;">①</th>
                                        <th style="width:9px;">②</th>
                                        <th style="width:9px;">③</th>
                                        <th style="width:9px;">④</th>
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
                                            <td style="text-align: left;">{{ $player->name }}</td>
                                            <td><strong>{{ $player->number ?? '00' }}</strong></td>
                                            @php $fouls = $team1FoulCounts[$player->number] ?? 0; @endphp
                                            <td>{!! $fouls >= 1 ? '/' : '&nbsp;' !!}</td>
                                            <td>{!! $fouls >= 2 ? '/' : '&nbsp;' !!}</td>
                                            <td>{!! $fouls >= 3 ? '/' : '&nbsp;' !!}</td>
                                            <td>{!! $fouls >= 4 ? '/' : '&nbsp;' !!}</td>
                                        </tr>
                                    @endforeach
                                    @for ($i = count($team1Players); $i < 10; $i++)
                                        <tr>
                                            <td>{{ $i + 1 }}</td><td></td><td></td><td></td><td></td><td></td><td></td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>

                            <div class="coach-section">
                                <strong>Coach:</strong> {{ $game->team1->coach_name ?? '____________' }}
                            </div>
                        </div>

                        <!-- Team B -->
                        <div class="box">
                            <div class="box-header">TEAM B: {{ strtoupper($game->team2->team_name) }}</div>

                            <div class="period-labels">
                                <strong>Timeouts:</strong>
                                <table style="margin-left:4px;">
                                    <tr>
                                        @php $team2Timeouts = $liveData['team2_timeouts'] ?? 0; @endphp
                                        @for ($i = 0; $i < 8; $i++)
                                            <td style="border:1px solid #000; width:10px; height:9px; text-align:center;">
                                                {!! $team2Timeouts > $i ? '&#x2714;' : '&nbsp;' !!}
                                            </td>
                                        @endfor
                                    </tr>
                                </table>
                            </div>

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

                            <div class="team-fouls-section">
                                <strong>Team Fouls:</strong>
                                <table style="width:100%; margin-top:2px;">
                                    <tr>
                                        @foreach ([1,2,3,4] as $q)
                                            <td>Q{{ $q }}</td>
                                            @for ($i = 0; $i < 4; $i++)
                                                <td style="border:1px solid #000; width:10px; height:9px; text-align:center;">
                                                    {!! $team2PeriodFouls[$q] > $i ? '/' : '&nbsp;' !!}
                                                </td>
                                            @endfor
                                        @endforeach
                                    </tr>
                                </table>
                            </div>

                            <table class="players-table">
                                <thead>
                                    <tr>
                                        <th style="width:14px;">Ln</th>
                                        <th>Player Name</th>
                                        <th style="width:16px;">No</th>
                                        <th style="width:9px;">①</th>
                                        <th style="width:9px;">②</th>
                                        <th style="width:9px;">③</th>
                                        <th style="width:9px;">④</th>
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
                                            <td style="text-align: left;">{{ $player->name }}</td>
                                            <td><strong>{{ $player->number ?? '00' }}</strong></td>
                                            @php $fouls = $team2FoulCounts[$player->number] ?? 0; @endphp
                                            <td>{!! $fouls >= 1 ? '/' : '&nbsp;' !!}</td>
                                            <td>{!! $fouls >= 2 ? '/' : '&nbsp;' !!}</td>
                                            <td>{!! $fouls >= 3 ? '/' : '&nbsp;' !!}</td>
                                            <td>{!! $fouls >= 4 ? '/' : '&nbsp;' !!}</td>

                                        </tr>
                                    @endforeach
                                    @for ($i = count($team2Players); $i < 10; $i++)
                                        <tr>
                                            <td>{{ $i + 1 }}</td><td></td><td></td><td></td><td></td><td></td><td></td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>

                            <div class="coach-section">
                                <strong>Coach:</strong> {{ $game->team2->coach_name ?? '____________' }}
                            </div>
                        </div>
                    </div>

                </td>

                <!-- Right Column: Running Score -->
                <td>
                    <div class="box">
                        <div class="box-header">RUNNING SCORE</div>
                        <table class="running-score-table">
                            <thead>
                                <tr>
                                    @for ($col = 0; $col < 4; $col++)
                                        <th></th><th>A</th><th></th><th>B</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $teamAChecks = array_fill(1, 160, false);
                                    $teamBChecks = array_fill(1, 160, false);
                                    if (isset($liveData['events'])) {
                                        $scoreA = 0; $scoreB = 0;
                                        $sortedEvents = array_reverse($liveData['events']);
                                        foreach ($sortedEvents as $event) {
                                            if (isset($event['points']) && $event['points'] > 0) {
                                                if ($event['team'] === 'A') {
                                                    $scoreA += $event['points'];
                                                    if ($scoreA <= 160) $teamAChecks[$scoreA] = true;
                                                } else {
                                                    $scoreB += $event['points'];
                                                    if ($scoreB <= 160) $teamBChecks[$scoreB] = true;
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                @for ($row = 0; $row < 40; $row++)
                                    <tr>
                                        @for ($col = 0; $col < 4; $col++)
                                            @php $num = $row + 1 + ($col * 40); @endphp
                                            <td>{{ $num }}</td>
                                            <td>{!! ($teamAChecks[$num] ?? false) ? '&#x2714;' : '&nbsp;' !!}</td>
                                            <td>{{ $num }}</td>
                                            <td>{!! ($teamBChecks[$num] ?? false) ? '&#x2714;' : '&nbsp;' !!}</td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Bottom Section -->
        <table class="bottom-layout">
            <tr>
                <!-- Left: Scores -->
                <td style="width:50%;">
                    <div class="box">
                        <div class="box-header">SCORES</div>
                        @php
                            $periodScores = $liveData['period_scores'] ?? ['team1'=>[0,0,0,0],'team2'=>[0,0,0,0]];
                        @endphp
                        <table class="scores-table">
                            <tr><td>Q1</td><td><strong>A:</strong> {{ $periodScores['team1'][0] }}</td><td><strong>B:</strong> {{ $periodScores['team2'][0] }}</td></tr>
                            <tr><td>Q2</td><td><strong>A:</strong> {{ $periodScores['team1'][1] }}</td><td><strong>B:</strong> {{ $periodScores['team2'][1] }}</td></tr>
                            <tr><td>Q3</td><td><strong>A:</strong> {{ $periodScores['team1'][2] }}</td><td><strong>B:</strong> {{ $periodScores['team2'][2] }}</td></tr>
                            <tr><td>Q4</td><td><strong>A:</strong> {{ $periodScores['team1'][3] }}</td><td><strong>B:</strong> {{ $periodScores['team2'][3] }}</td></tr>
                            <tr><td>FINAL</td><td><strong>{{ $liveData['team1_score'] }}</strong></td><td><strong>{{ $liveData['team2_score'] }}</strong></td></tr>
                        </table>

                        <div class="winner-section">
                            <strong>🏆 Winner:</strong>
                            @if ($liveData['team1_score'] > $liveData['team2_score'])
                                {{ $game->team1->team_name }}
                            @elseif ($liveData['team2_score'] > $liveData['team1_score'])
                                {{ $game->team2->team_name }}
                            @else
                                TIE
                            @endif
                        </div>

                        <div class="best-player-section">
                            <div><strong>⭐ BEST PLAYER:</strong> __________</div>
                            <div><strong>Pts:</strong> __ <strong>Ast:</strong> __ <strong>Reb:</strong> __</div>
                            <div><strong>Blk:</strong> __ <strong>Stl:</strong> __ <strong>Fls:</strong> __</div>
                        </div>
                    </div>
                </td>

                <!-- Right: Officials -->
                <td style="width:50%;">
                    <div class="box">
                        <div class="box-header">OFFICIALS</div>
                        <table class="officials-content">
                            <tr><td><strong>Scorekeeper:</strong> ___________</td></tr>
                            <tr><td><strong>Asst. Scorekeeper:</strong> ___________</td></tr>
                            <tr><td><strong>Timekeeper:</strong> ___________</td></tr>
                            <tr><td><strong>24-Sec Operator:</strong> ___________</td></tr>
                            <tr><td><strong>Referee:</strong> {{ $game->referee ?? '___________' }}</td></tr>
                            <tr><td><strong>Umpire 1:</strong> {{ $game->assistant_referee_1 ?? '___________' }}</td></tr>
                            <tr><td><strong>Umpire 2:</strong> {{ $game->assistant_referee_2 ?? '___________' }}</td></tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
