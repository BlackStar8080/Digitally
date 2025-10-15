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
            margin: 0.25in;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            background: white;
            padding: 10px;
        }
        .sheet {
            width: 8in;
            background: white;
            border: 3px solid #000;
            padding: 12px;
            margin: 0 auto;
        }
        .download-buttons {
            position: fixed;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 8px;
            z-index: 1000;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 13px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0069d9;
            transform: translateY(-2px);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
            position: relative;
        }
        .header h1 {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 4px;
            margin: 5px 0;
        }
        .logo {
            position: absolute;
            width: 50px;
            height: 50px;
            top: 5px;
            font-size: 35px;
        }
        .logo-left { left: 20px; }
        .logo-right { right: 20px; }
        
        .meta-section {
            border: 2px solid #000;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 9px;
        }
        .meta-row {
            display: flex;
            gap: 15px;
            margin-bottom: 5px;
            flex-wrap: wrap;
        }
        .meta-item {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        .meta-label {
            font-weight: bold;
        }
        .meta-value {
            border-bottom: 1px solid #000;
            min-width: 80px;
            padding: 0 5px;
        }
        
        .main-layout {
            display: grid;
            grid-template-columns: 3.5in 4.2in;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .team-box {
            border: 2px solid #000;
            padding: 8px;
            margin-bottom: 10px;
        }
        .team-header {
            font-weight: bold;
            font-size: 10px;
            padding-bottom: 5px;
            margin-bottom: 8px;
            border-bottom: 2px solid #000;
        }
        
        .timeout-section {
            margin-bottom: 8px;
            font-size: 8px;
        }
        .timeout-grid {
            display: inline-grid;
            grid-template-columns: repeat(3, 18px);
            gap: 3px;
            margin-left: 8px;
            vertical-align: middle;
        }
        .timeout-box {
            width: 18px;
            height: 18px;
            border: 1px solid #000;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
        }
        .circle-box {
            width: 20px;
            height: 20px;
            border: 2px solid #000;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
        }
        
        .foul-section {
            margin-bottom: 8px;
            font-size: 8px;
        }
        .foul-row {
            margin-bottom: 5px;
        }
        .foul-boxes {
            display: inline-flex;
            gap: 2px;
            margin: 0 5px;
        }
        .square-box {
            width: 16px;
            height: 16px;
            border: 1px solid #000;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-bottom: 8px;
        }
        td, th {
            border: 1px solid #000;
            padding: 3px 2px;
            text-align: center;
        }
        th {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 7px;
        }
        .player-name {
            text-align: left !important;
            padding-left: 5px !important;
        }
        
        .coach-field {
            margin-top: 5px;
            font-size: 8px;
            display: flex;
            gap: 5px;
        }
        .coach-label {
            font-weight: bold;
            min-width: 80px;
        }
        .coach-value {
            border-bottom: 1px solid #000;
            flex: 1;
            padding: 0 5px;
        }
        
        .running-score-box {
            border: 2px solid #000;
            padding: 8px;
        }
        .running-score-header {
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            padding-bottom: 5px;
            margin-bottom: 8px;
            border-bottom: 2px solid #000;
        }
        .running-score-table {
            font-size: 7px;
        }
        .running-score-table td {
            padding: 1px;
            height: 13px;
        }
        .running-score-table th {
            font-size: 8px;
            font-weight: bold;
            padding: 5px 0;
        }
        
        .bottom-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            border: 2px solid #000;
            padding: 10px;
        }
        
        .officials-section {
            font-size: 8px;
        }
        .official-field {
            margin-bottom: 8px;
            display: flex;
            gap: 5px;
        }
        .official-label {
            font-weight: bold;
            min-width: 120px;
        }
        .official-value {
            border-bottom: 1px solid #000;
            flex: 1;
            padding: 0 5px;
        }
        .referee-section {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px solid #000;
        }
        
        .scores-section {
            font-size: 8px;
        }
        .period-score {
            display: flex;
            gap: 10px;
            margin-bottom: 5px;
            align-items: center;
        }
        .period-label {
            font-weight: bold;
            min-width: 60px;
        }
        .final-score {
            border-top: 2px solid #000;
            padding-top: 8px;
            margin-top: 8px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .winner-field {
            margin-bottom: 8px;
            display: flex;
            gap: 5px;
        }
        .best-player {
            border-top: 1px solid #000;
            padding-top: 10px;
            margin-top: 10px;
        }
        .best-player-title {
            font-weight: bold;
            margin-bottom: 8px;
        }
        .best-player-stats {
            font-size: 7px;
            line-height: 1.6;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .sheet {
                border: none;
                box-shadow: none;
            }
            .download-buttons {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="download-buttons">
        <a href="{{ route('pdf.basketball.scoresheet.download', $game->id) }}" class="btn btn-success">
            Download
        </a>
        <a href="{{ route('pdf.basketball.scoresheet.view', $game->id) }}" class="btn btn-primary" target="_blank">
            View
        </a>
    </div>

    <div class="sheet">
        <!-- Header -->
        <div class="header">
            <div class="logo logo-left">🏀</div>
            <h1>PADAYON CUP</h1>
            <div class="logo logo-right">🏆</div>
        </div>

        <!-- Meta Information -->
        <div class="meta-section">
            <div class="meta-row">
                <div class="meta-item">
                    <span class="meta-label">Team A</span>
                    <span class="meta-value">{{ $game->team1->team_name }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Team B</span>
                    <span class="meta-value">{{ $game->team2->team_name }}</span>
                </div>
            </div>
            <div class="meta-row">
                <div class="meta-item">
                    <span class="meta-label">Competition</span>
                    <span class="meta-value">{{ $game->bracket->tournament->name ?? '26 ABOVE' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Date</span>
                    <span class="meta-value">{{ $game->started_at ? $game->started_at->format('m/d/Y') : date('m/d/Y') }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Time</span>
                    <span class="meta-value">{{ $game->started_at ? $game->started_at->format('H:i') : '__:__' }}</span>
                </div>
            </div>
            <div class="meta-row">
                <div class="meta-item">
                    <span class="meta-label">Game No.</span>
                    <span class="meta-value">{{ $game->id }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Place</span>
                    <span class="meta-value">CASINGLOT COURT</span>
                </div>
            </div>
            <div class="meta-row">
                <div class="meta-item">
                    <span class="meta-label">Referee</span>
                    <span class="meta-value">{{ $game->referee ?? '________' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Umpire 1</span>
                    <span class="meta-value">{{ $game->assistant_referee_1 ?? '________' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Umpire 2</span>
                    <span class="meta-value">{{ $game->assistant_referee_2 ?? '________' }}</span>
                </div>
            </div>
        </div>

        <!-- Main Layout -->
        <div class="main-layout">
            <!-- Left: Teams -->
            <div>
                <!-- Team A -->
                <div class="team-box">
                    <div class="team-header">Team A {{ strtoupper($game->team1->team_name) }}</div>
                    
                    <div class="timeout-section">
                        <strong>Time-outs</strong>
                        <div class="timeout-grid">
                            @php $team1Timeouts = $liveData['team1_timeouts'] ?? 0; @endphp
                            @for ($i = 0; $i < 2; $i++)
                                <div class="timeout-box">{{ $team1Timeouts > $i ? '✓' : '' }}</div>
                            @endfor
                            <div></div>
                            @for ($i = 2; $i < 5; $i++)
                                <div class="timeout-box">{{ $team1Timeouts > $i ? '✓' : '' }}</div>
                            @endfor
                            @for ($i = 5; $i < 8; $i++)
                                <div class="timeout-box">{{ $team1Timeouts > $i ? '✓' : '' }}</div>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="foul-section">
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
                        <div class="foul-row">
                            <strong>Period</strong>
                            <span class="circle-box">①</span>
                            <div class="foul-boxes">
                                @for ($i = 0; $i < 4; $i++)
                                    <span class="square-box">{{ $team1PeriodFouls[1] > $i ? '/' : '' }}</span>
                                @endfor
                            </div>
                            <span class="circle-box">②</span>
                            <div class="foul-boxes">
                                @for ($i = 0; $i < 4; $i++)
                                    <span class="square-box">{{ $team1PeriodFouls[2] > $i ? '/' : '' }}</span>
                                @endfor
                            </div>
                        </div>
                        <div class="foul-row">
                            <strong>Period</strong>
                            <span class="circle-box">③</span>
                            <div class="foul-boxes">
                                @for ($i = 0; $i < 4; $i++)
                                    <span class="square-box">{{ $team1PeriodFouls[3] > $i ? '/' : '' }}</span>
                                @endfor
                            </div>
                            <span class="circle-box">④</span>
                            <div class="foul-boxes">
                                @for ($i = 0; $i < 4; $i++)
                                    <span class="square-box">{{ $team1PeriodFouls[4] > $i ? '/' : '' }}</span>
                                @endfor
                            </div>
                        </div>
                        <div style="margin-top: 5px;"><strong>Extra periods</strong></div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 30px;">License<br>no.</th>
                                <th>Players</th>
                                <th style="width: 30px;">No.</th>
                                <th style="width: 40px;">Player<br>in</th>
                                <th style="width: 18px;">1</th>
                                <th style="width: 18px;">2</th>
                                <th style="width: 18px;">3</th>
                                <th style="width: 18px;">4</th>
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
                                    <td class="player-name">{{ $player->name }}</td>
                                    <td><strong>{{ $player->number ?? '00' }}</strong></td>
                                    <td></td>
                                    @php $fouls = $team1FoulCounts[$player->number] ?? 0; @endphp
                                    <td>{{ $fouls >= 1 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 2 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 3 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 4 ? '/' : '' }}</td>
                                </tr>
                            @endforeach
                            @for ($i = count($team1Players); $i < 15; $i++)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td></td>
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

                    <div class="coach-field">
                        <span class="coach-label">Coach</span>
                        <span class="coach-value">{{ $game->team1->coach_name ?? '____________' }}</span>
                    </div>
                    <div class="coach-field">
                        <span class="coach-label">Assistant Coach</span>
                        <span class="coach-value">____________</span>
                    </div>
                </div>

                <!-- Team B -->
                <div class="team-box">
                    <div class="team-header">Team B {{ strtoupper($game->team2->team_name) }}</div>
                    
                    <div class="timeout-section">
                        <strong>Time-outs</strong>
                        <div class="timeout-grid">
                            @php $team2Timeouts = $liveData['team2_timeouts'] ?? 0; @endphp
                            @for ($i = 0; $i < 2; $i++)
                                <div class="timeout-box">{{ $team2Timeouts > $i ? '✓' : '' }}</div>
                            @endfor
                            <div></div>
                            @for ($i = 2; $i < 5; $i++)
                                <div class="timeout-box">{{ $team2Timeouts > $i ? '✓' : '' }}</div>
                            @endfor
                            @for ($i = 5; $i < 8; $i++)
                                <div class="timeout-box">{{ $team2Timeouts > $i ? '✓' : '' }}</div>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="foul-section">
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
                        <div class="foul-row">
                            <strong>Period</strong>
                            <span class="circle-box">①</span>
                            <div class="foul-boxes">
                                @for ($i = 0; $i < 4; $i++)
                                    <span class="square-box">{{ $team2PeriodFouls[1] > $i ? '/' : '' }}</span>
                                @endfor
                            </div>
                            <span class="circle-box">②</span>
                            <div class="foul-boxes">
                                @for ($i = 0; $i < 4; $i++)
                                    <span class="square-box">{{ $team2PeriodFouls[2] > $i ? '/' : '' }}</span>
                                @endfor
                            </div>
                        </div>
                        <div class="foul-row">
                            <strong>Period</strong>
                            <span class="circle-box">③</span>
                            <div class="foul-boxes">
                                @for ($i = 0; $i < 4; $i++)
                                    <span class="square-box">{{ $team2PeriodFouls[3] > $i ? '/' : '' }}</span>
                                @endfor
                            </div>
                            <span class="circle-box">④</span>
                            <div class="foul-boxes">
                                @for ($i = 0; $i < 4; $i++)
                                    <span class="square-box">{{ $team2PeriodFouls[4] > $i ? '/' : '' }}</span>
                                @endfor
                            </div>
                        </div>
                        <div style="margin-top: 5px;"><strong>Extra periods</strong></div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 30px;">License<br>no.</th>
                                <th>Players</th>
                                <th style="width: 30px;">No.</th>
                                <th style="width: 40px;">Player<br>in</th>
                                <th style="width: 18px;">1</th>
                                <th style="width: 18px;">2</th>
                                <th style="width: 18px;">3</th>
                                <th style="width: 18px;">4</th>
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
                                    <td class="player-name">{{ $player->name }}</td>
                                    <td><strong>{{ $player->number ?? '00' }}</strong></td>
                                    <td></td>
                                    @php $fouls = $team2FoulCounts[$player->number] ?? 0; @endphp
                                    <td>{{ $fouls >= 1 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 2 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 3 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 4 ? '/' : '' }}</td>
                                </tr>
                            @endforeach
                            @for ($i = count($team2Players); $i < 15; $i++)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td></td>
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

                    <div class="coach-field">
                        <span class="coach-label">Coach</span>
                        <span class="coach-value">{{ $game->team2->coach_name ?? '____________' }}</span>
                    </div>
                    <div class="coach-field">
                        <span class="coach-label">Assistant Coach</span>
                        <span class="coach-value">____________</span>
                    </div>
                </div>
            </div>

            <!-- Right: Running Score -->
            <div class="running-score-box">
                <div class="running-score-header">RUNNING SCORE</div>
                <table class="running-score-table">
                    <thead>
                        <tr>
                            @for ($col = 0; $col < 4; $col++)
                                <th style="width:8px;"></th>
                                <th style="width:10px;">A</th>
                                <th style="width:8px;"></th>
                                <th style="width:10px;">B</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $teamAChecks = array_fill(1, 160, false);
                            $teamBChecks = array_fill(1, 160, false);
                            if (isset($liveData['events'])) {
                                $scoreA = 0;
                                $scoreB = 0;
                                $sortedEvents = array_reverse($liveData['events']);
                                foreach ($sortedEvents as $event) {
                                    if (isset($event['points']) && $event['points'] > 0) {
                                        if ($event['team'] === 'A') {
                                            $scoreA += $event['points'];
                                            if ($scoreA <= 160) {
                                                $teamAChecks[$scoreA] = true;
                                            }
                                        } else {
                                            $scoreB += $event['points'];
                                            if ($scoreB <= 160) {
                                                $teamBChecks[$scoreB] = true;
                                            }
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
                                    <td>{{ $teamAChecks[$num] ?? false ? '✓' : '' }}</td>
                                    <td>{{ $num }}</td>
                                    <td>{{ $teamBChecks[$num] ?? false ? '✓' : '' }}</td>
                                @endfor
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <div class="officials-section">
                <div class="official-field">
                    <span class="official-label">Scorekeeper</span>
                    <span class="official-value">___________</span>
                </div>
                <div class="official-field">
                    <span class="official-label">Assistant Scorekeeper</span>
                    <span class="official-value">___________</span>
                </div>
                <div class="official-field">
                    <span class="official-label">Timekeeper</span>
                    <span class="official-value">___________</span>
                </div>
                <div class="official-field">
                    <span class="official-label">24" operator</span>
                    <span class="official-value">___________</span>
                </div>
                
                <div class="referee-section">
                    <div class="official-field">
                        <span class="official-label">Referee</span>
                        <span class="official-value">{{ $game->referee ?? '___________' }}</span>
                    </div>
                    <div class="official-field">
                        <span class="official-label">Umpire 1</span>
                        <span class="official-value">{{ $game->assistant_referee_1 ?? '___________' }}</span>
                    </div>
                    <div class="official-field">
                        <span class="official-label">Umpire 2</span>
                        <span class="official-value">{{ $game->assistant_referee_2 ?? '___________' }}</span>
                    </div>
                </div>
            </div>

            <div class="scores-section">
                @php
                    $periodScores = $liveData['period_scores'] ?? ['team1' => [0, 0, 0, 0], 'team2' => [0, 0, 0, 0]];
                @endphp
                
                <div class="period-score">
                    <span class="period-label">Period ①</span>
                    <span>A {{ $periodScores['team1'][0] ?? 0 }}</span>
                    <span>B {{ $periodScores['team2'][0] ?? 0 }}</span>
                </div>
                <div class="period-score">
                    <span class="period-label">Period ②</span>
                    <span>A {{ $periodScores['team1'][1] ?? 0 }}</span>
                    <span>B {{ $periodScores['team2'][1] ?? 0 }}</span>
                </div>
                <div class="period-score">
                    <span class="period-label">Period ③</span>
                    <span>A {{ $periodScores['team1'][2] ?? 0 }}</span>
                    <span>B {{ $periodScores['team2'][2] ?? 0 }}</span>
                </div>
                <div class="period-score">
                    <span class="period-label">Period ④</span>
                    <span>A  {{ $periodScores['team1'][3] ?? 0 }}</span>
                    <span>B {{ $periodScores['team2'][3] ?? 0 }}</span>
                </div>
                <div class="period-score">
                    <span class="period-label">Extra periods</span>
                    <span>A ____</span>
                    <span>B ____</span>
                </div>

                <div class="final-score">
                    <div style="margin-bottom: 5px;">
                        Final Score: Team A {{ $liveData['team1_score'] ?? 0 }} - Team B {{ $liveData['team2_score'] ?? 0 }}
                    </div>
                </div>

                <div class="winner-field">
                    <span class="official-label">Name of winning team</span>
                    <span class="official-value">
                        @if (isset($liveData['team1_score']) && isset($liveData['team2_score']))
                            @if ($liveData['team1_score'] > $liveData['team2_score'])
                                {{ $game->team1->team_name }}
                            @elseif($liveData['team2_score'] > $liveData['team1_score'])
                                {{ $game->team2->team_name }}
                            @else
                                TIE
                            @endif
                        @else
                            __________
                        @endif
                    </span>
                </div>

                <div class="best-player">
                    <div class="best-player-title">BEST PLAYER: ___________________</div>
                    <div class="best-player-stats">
                        <div>Score: ____ Assist: ____ Rebound: ____ Blocks: ____ Steal: ____</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>