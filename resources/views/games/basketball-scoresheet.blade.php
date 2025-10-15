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
            font-family: Arial, sans-serif;
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header {
            text-align: center;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            padding: 4px;
            border: 2px solid #000;
            margin-bottom: 3px;
            position: relative;
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
            position: absolute;
            width: 30px;
            height: 30px;
            top: 50%;
            transform: translateY(-50%);
            border: 2px solid #000;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #8B4513;
            font-size: 10px;
        }

        .logo-left {
            left: 10px;
        }

        .logo-right {
            right: 10px;
        }

        .meta-section {
            margin-bottom: 3px;
        }

        .meta-row {
            display: flex;
            gap: 4px;
            margin-bottom: 2px;
            font-size: 6.5px;
            align-items: center;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 2px;
            background: #f8f9fa;
            padding: 1px 4px;
            border-radius: 2px;
            border: 1px solid #dee2e6;
        }

        .meta-item strong {
            font-size: 6.5px;
            color: #495057;
        }

        .meta-value {
            border-bottom: 1px solid #000;
            min-width: 50px;
            padding: 0 3px;
            font-size: 6.5px;
            font-weight: 600;
            background: white;
        }

        .main-layout {
            display: grid;
            grid-template-columns: 2.9in 1fr;
            gap: 4px;
            margin-bottom: 3px;
            flex: 1;
            min-height: 0;
        }

        .teams-section {
            display: flex;
            flex-direction: column;
            gap: 3px;
            min-height: 0;
        }

        .teams-section .box {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
        }

        .box {
            border: 2px solid #000;
            padding: 3px;
            background: white;
            border-radius: 3px;
        }

        .box-header {
            font-weight: bold;
            font-size: 7.5px;
            margin-bottom: 2px;
            padding: 2px;
            border-bottom: 2px solid #000;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            border-radius: 2px;
            text-align: center;
            color: #8B4513;
            line-height: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 5.5px;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 0.5px 1px;
            text-align: center;
            line-height: 1;
        }

        .players-table {
            flex: 1;
            min-height: 0;
        }

        .players-table td {
            height: 11px;
            padding: 0.5px 2px;
        }

        .players-table td:nth-child(2) {
            text-align: left;
            font-size: 6px;
            padding-left: 3px;
        }

        .players-table th {
            background: #e9ecef;
            font-weight: 700;
            padding: 2px 1px;
            font-size: 5.5px;
        }

        .timeout-grid {
            display: inline-grid;
            grid-template-columns: repeat(3, 11px);
            gap: 1px;
            vertical-align: middle;
        }

        .foul-grid {
            display: inline-grid;
            grid-template-columns: repeat(4, 10px);
            gap: 1px;
            vertical-align: middle;
        }

        .timeout-box,
        .foul-box {
            width: 10px;
            height: 9px;
            border: 1px solid #000;
            display: inline-block;
            text-align: center;
            line-height: 9px;
            font-size: 6px;
            background: white;
            font-weight: bold;
            color: #28a745;
        }

        .running-score-table {
            font-size: 5px;
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        .running-score-table td,
        .running-score-table th {
            padding: 0;
            height: 8.5px;
            vertical-align: middle;
            font-size: 5px;
            line-height: 1;
        }

        .running-score-table th {
            font-size: 6px;
            font-weight: bold;
            padding: 8px 0;
            background: #e9ecef;
        }

        .running-score-table td:nth-child(2),
        .running-score-table td:nth-child(6),
        .running-score-table td:nth-child(10),
        .running-score-table td:nth-child(14) {
            background: #fff8dc;
        }

        .running-score-table td:nth-child(4),
        .running-score-table td:nth-child(8),
        .running-score-table td:nth-child(12),
        .running-score-table td:nth-child(16) {
            background: #f0f8ff;
        }

        .running-score-container {
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
        }

        .running-score-container table {
            flex: 1;
            min-height: 0;
        }

        .period-labels {
            display: flex;
            gap: 3px;
            align-items: center;
            margin-bottom: 2px;
            font-size: 6px;
            padding: 1px;
            background: #f8f9fa;
            border-radius: 2px;
        }

        .bottom-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
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
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #218838, #1aa179);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0069d9, #004085);
            transform: translateY(-2px);
        }

        .team-fouls-section {
            font-size: 6px;
            margin-top: 2px;
            padding: 2px;
            background: #f8f9fa;
            border-radius: 2px;
        }

        .fouls-row {
            display: flex;
            gap: 3px;
            align-items: center;
            margin-top: 1px;
        }

        .fouls-row span {
            font-size: 5.5px;
            min-width: 14px;
        }

        .coach-section {
            margin-top: 2px;
            font-size: 6px;
            padding: 2px;
            background: #fff3cd;
            border-radius: 2px;
            border: 1px solid #ffc107;
        }

        .scores-table {
            font-size: 6px;
            margin-bottom: 3px;
        }

        .scores-table td {
            padding: 1px 3px;
            font-size: 6px;
        }

        .scores-table tr:last-child {
            background: #fffacd;
            font-weight: bold;
            border-top: 2px solid #000;
        }

        .winner-section {
            padding: 2px;
            border-top: 2px solid #000;
            margin: 3px 0;
            font-size: 6px;
            background: #d4edda;
            border-radius: 2px;
        }

        .best-player-section {
            padding: 3px;
            border-top: 2px solid #000;
            margin-top: 3px;
            background: #fff3cd;
            border-radius: 2px;
        }

        .best-player-section>div:first-child {
            font-weight: bold;
            margin-bottom: 2px;
            font-size: 6px;
            color: #856404;
        }

        .best-player-stats {
            font-size: 5.5px;
            line-height: 1.4;
        }

        .officials-content {
            font-size: 6px;
            line-height: 1.6;
        }

        .officials-content>div {
            padding: 1px 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .officials-content>div:last-child {
            border-bottom: none;
        }

        .referee-section {
            margin-top: 4px;
            padding-top: 4px;
            border-top: 2px solid #000;
            background: #e7f3ff;
            padding: 3px;
            border-radius: 2px;
        }

        .referee-section>div {
            font-size: 6px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .sheet {
                box-shadow: none;
                margin: 0;
                padding: 0.15in;
                width: 100%;
                height: 100%;
                page-break-after: avoid;
                page-break-inside: avoid;
            }

            .download-buttons {
                display: none !important;
            }

            * {
                page-break-inside: avoid;
            }

            .box,
            .teams-section,
            .main-layout,
            .bottom-section,
            table {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        @media print {
  * {
    page-break-inside: avoid;
  }
  
  .box,
  .teams-section,
  .main-layout,
  .bottom-section,
  table {
    page-break-inside: avoid;
    break-inside: avoid;
  }
}

@page { 
    size: 8.5in 13in portrait; 
    margin: 0.1in;  /* Even smaller margins */
}

.sheet {
    height: 12.8in;  /* Slightly larger */
}
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
            <div class="logo logo-left">🏀</div>
            <h1>PADAYON CUP</h1>
            <p>Basketball Scoresheet</p>
            <div class="logo logo-right">🏆</div>
        </div>

        <!-- Meta Information -->
        <div class="meta-section">
            <div class="meta-row">
                <div class="meta-item">
                    <strong>Team A:</strong>
                    <span class="meta-value">{{ $game->team1->team_name }}</span>
                </div>
                <div class="meta-item">
                    <strong>Team B:</strong>
                    <span class="meta-value">{{ $game->team2->team_name }}</span>
                </div>
                <div class="meta-item">
                    <strong>Competition:</strong>
                    <span class="meta-value">{{ $game->bracket->tournament->name ?? '26 ABOVE' }}</span>
                </div>
            </div>

            <div class="meta-row">
                <div class="meta-item">
                    <strong>Date:</strong>
                    <span
                        class="meta-value">{{ $game->started_at ? $game->started_at->format('m/d/Y') : date('m/d/Y') }}</span>
                </div>
                <div class="meta-item">
                    <strong>Time:</strong>
                    <span class="meta-value">{{ $game->started_at ? $game->started_at->format('H:i') : '__:__' }}</span>
                </div>
                <div class="meta-item">
                    <strong>Game No.:</strong>
                    <span class="meta-value">{{ $game->id }}</span>
                </div>
                <div class="meta-item">
                    <strong>Place:</strong>
                    <span class="meta-value">CASINGLOT COURT</span>
                </div>
            </div>

            <div class="meta-row">
                <div class="meta-item">
                    <strong>Referee:</strong>
                    <span class="meta-value">{{ $game->referee ?? '________' }}</span>
                </div>
                <div class="meta-item">
                    <strong>Umpire 1:</strong>
                    <span class="meta-value">{{ $game->assistant_referee_1 ?? '________' }}</span>
                </div>
                <div class="meta-item">
                    <strong>Umpire 2:</strong>
                    <span class="meta-value">{{ $game->assistant_referee_2 ?? '________' }}</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-layout">
            <!-- Left: Teams -->
            <div class="teams-section">
                <!-- Team A -->
                <div class="box">
                    <div class="box-header">TEAM A: {{ strtoupper($game->team1->team_name) }}</div>

                    <div class="period-labels">
                        <strong>Timeouts:</strong>
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

                    @php
                        $team1PeriodFouls = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                        if (isset($liveData['events']) && is_array($liveData['events'])) {
                            foreach ($liveData['events'] as $event) {
                                if (
                                    $event['team'] === 'A' &&
                                    isset($event['action']) &&
                                    str_contains($event['action'], 'Foul')
                                ) {
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
                        <div class="fouls-row">
                            <span>Q1</span>
                            <div class="foul-grid">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="foul-box">{{ $team1PeriodFouls[1] > $i ? '/' : '' }}</div>
                                @endfor
                            </div>
                            <span>Q2</span>
                            <div class="foul-grid">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="foul-box">{{ $team1PeriodFouls[2] > $i ? '/' : '' }}</div>
                                @endfor
                            </div>
                        </div>
                        <div class="fouls-row">
                            <span>Q3</span>
                            <div class="foul-grid">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="foul-box">{{ $team1PeriodFouls[3] > $i ? '/' : '' }}</div>
                                @endfor
                            </div>
                            <span>Q4</span>
                            <div class="foul-grid">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="foul-box">{{ $team1PeriodFouls[4] > $i ? '/' : '' }}</div>
                                @endfor
                            </div>
                        </div>
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
                                        if (
                                            $event['team'] === 'A' &&
                                            isset($event['player']) &&
                                            $event['player'] !== 'TEAM' &&
                                            $event['player'] !== 'SYSTEM'
                                        ) {
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
                                    <td>{{ $fouls >= 1 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 2 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 3 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 4 ? '/' : '' }}</td>
                                </tr>
                            @endforeach
                            @for ($i = count($team1Players); $i < 10; $i++)
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

                    <div class="coach-section">
                        <strong>Coach:</strong> {{ $game->team1->coach_name ?? '____________' }}
                    </div>
                </div>

                <!-- Team B -->
                <div class="box">
                    <div class="box-header">TEAM B: {{ strtoupper($game->team2->team_name) }}</div>

                    <div class="period-labels">
                        <strong>Timeouts:</strong>
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

                    @php
                        $team2PeriodFouls = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                        if (isset($liveData['events']) && is_array($liveData['events'])) {
                            foreach ($liveData['events'] as $event) {
                                if (
                                    $event['team'] === 'B' &&
                                    isset($event['action']) &&
                                    str_contains($event['action'], 'Foul')
                                ) {
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
                        <div class="fouls-row">
                            <span>Q1</span>
                            <div class="foul-grid">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="foul-box">{{ $team2PeriodFouls[1] > $i ? '/' : '' }}</div>
                                @endfor
                            </div>
                            <span>Q2</span>
                            <div class="foul-grid">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="foul-box">{{ $team2PeriodFouls[2] > $i ? '/' : '' }}</div>
                                @endfor
                            </div>
                        </div>
                        <div class="fouls-row">
                            <span>Q3</span>
                            <div class="foul-grid">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="foul-box">{{ $team2PeriodFouls[3] > $i ? '/' : '' }}</div>
                                @endfor
                            </div>
                            <span>Q4</span>
                            <div class="foul-grid">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="foul-box">{{ $team2PeriodFouls[4] > $i ? '/' : '' }}</div>
                                @endfor
                            </div>
                        </div>
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
                                        if (
                                            $event['team'] === 'B' &&
                                            isset($event['player']) &&
                                            $event['player'] !== 'TEAM' &&
                                            $event['player'] !== 'SYSTEM'
                                        ) {
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
                                    <td>{{ $fouls >= 1 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 2 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 3 ? '/' : '' }}</td>
                                    <td>{{ $fouls >= 4 ? '/' : '' }}</td>
                                </tr>
                            @endforeach
                            @for ($i = count($team2Players); $i < 10; $i++)
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

                    <div class="coach-section">
                        <strong>Coach:</strong> {{ $game->team2->coach_name ?? '____________' }}
                    </div>
                </div>
            </div>

            <!-- Right: Running Score -->
            <div class="box running-score-container">
                <div class="box-header">RUNNING SCORE</div>
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
            <!-- Scores Summary -->
            <div class="box">
                <div class="box-header">SCORES</div>
                @php
                    $periodScores = $liveData['period_scores'] ?? ['team1' => [0, 0, 0, 0], 'team2' => [0, 0, 0, 0]];
                @endphp
                <table class="scores-table">
                    <tr>
                        <td style="text-align: left; font-weight: bold;">Q1</td>
                        <td><strong>A:</strong> {{ $periodScores['team1'][0] ?? 0 }}</td>
                        <td><strong>B:</strong> {{ $periodScores['team2'][0] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-weight: bold;">Q2</td>
                        <td><strong>A:</strong> {{ $periodScores['team1'][1] ?? 0 }}</td>
                        <td><strong>B:</strong> {{ $periodScores['team2'][1] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-weight: bold;">Q3</td>
                        <td><strong>A:</strong> {{ $periodScores['team1'][2] ?? 0 }}</td>
                        <td><strong>B:</strong> {{ $periodScores['team2'][2] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-weight: bold;">Q4</td>
                        <td><strong>A:</strong> {{ $periodScores['team1'][3] ?? 0 }}</td>
                        <td><strong>B:</strong> {{ $periodScores['team2'][3] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-weight: bold; font-size: 7px;">FINAL</td>
                        <td style="font-weight: bold; font-size: 7.5px;">{{ $liveData['team1_score'] ?? 0 }}</td>
                        <td style="font-weight: bold; font-size: 7.5px;">{{ $liveData['team2_score'] ?? 0 }}</td>
                    </tr>
                </table>
                <div class="winner-section">
                    <strong>🏆 Winner:</strong>
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
                </div>

                <div class="best-player-section">
                    <div>⭐ BEST PLAYER: __________</div>
                    <div class="best-player-stats">
                        <div><strong>Pts:</strong> __ <strong>Ast:</strong> __ <strong>Reb:</strong> __</div>
                        <div><strong>Blk:</strong> __ <strong>Stl:</strong> __ <strong>Fls:</strong> __</div>
                    </div>
                </div>
            </div>

            <!-- Officials -->
            <div class="box">
                <div class="box-header">OFFICIALS</div>
                <div class="officials-content">
                    <div><strong>Scorekeeper:</strong> ___________</div>
                    <div><strong>Asst. Scorekeeper:</strong> ___________</div>
                    <div><strong>Timekeeper:</strong> ___________</div>
                    <div><strong>24-Sec Operator:</strong> ___________</div>
                </div>

                <div class="referee-section">
                    <div><strong>Referee:</strong> {{ $game->referee ?? '___________' }}</div>
                    <div style="margin-top: 2px;"><strong>Umpire 1:</strong>
                        {{ $game->assistant_referee_1 ?? '___________' }}</div>
                    <div style="margin-top: 2px;"><strong>Umpire 2:</strong>
                        {{ $game->assistant_referee_2 ?? '___________' }}</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
