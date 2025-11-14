<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Volleyball Scoresheet - Game {{ $game->id }}</title>
    <style>
        @page {
            size: 8.5in 14in portrait;
            margin: 0.2in;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.1;
            color: #000;
        }

        /* HEADER */
        .header-container {
            width: 100%;
            border: 2px solid #000;
            margin-bottom: 4px;
        }

        .header-row {
            width: 100%;
        }

        .header-logo {
            width: 15%;
            text-align: center;
            padding: 5px;
            vertical-align: middle;
        }

        .header-logo img {
            max-width: 60px;
            max-height: 60px;
        }

        .header-title {
            width: 70%;
            text-align: center;
            vertical-align: middle;
            padding: 8px;
        }

        .header-title h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            padding: 2px 0;
        }

        .header-title h2 {
            font-size: 11pt;
            font-weight: bold;
            margin: 2px 0;
        }

        .header-title h3 {
            font-size: 9pt;
            margin: 2px 0;
        }

        /* META INFO */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
            font-size: 8pt;
        }

        .meta-table td {
            border: 1px solid #000;
            padding: 3px 5px;
        }

        .meta-label {
            font-weight: bold;
        }

        /* TEAMS TABLE */
        .teams-table {
            width: 35%;
            border-collapse: collapse;
            font-size: 7pt;
            float: right;
        }

        .teams-table th,
        .teams-table td {
            border: 1px solid #000;
            padding: 2px 3px;
            text-align: center;
        }

        .teams-table th {
            background: #e0e0e0;
            font-weight: bold;
        }

        .team-name-cell {
            text-align: left;
            font-weight: bold;
        }

        /* REFEREES */
        .referees-row {
            width: 100%;
            border: 1px solid #000;
            padding: 3px 5px;
            margin-bottom: 3px;
            font-size: 8pt;
            clear: both;
        }

        /* ROSTERS */
        .rosters-container {
            width: 100%;
            margin-bottom: 3px;
        }

        .roster-table {
            width: 49%;
            border-collapse: collapse;
            font-size: 7pt;
            vertical-align: top;
        }

        .roster-left {
            float: left;
        }

        .roster-right {
            float: right;
        }

        .roster-table th {
            background: #e0e0e0;
            border: 1px solid #000;
            padding: 3px 2px;
            font-weight: bold;
            text-align: center;
        }

        .roster-table td {
            border: 1px solid #000;
            padding: 2px 3px;
        }

        .roster-header {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 9pt;
            text-align: left;
        }

        .player-number {
            width: 8%;
            text-align: center;
            font-weight: bold;
        }

        .player-name {
            width: 50%;
            text-align: left;
        }

        .set-check {
            width: 8%;
            text-align: center;
            font-size: 10pt;
        }

        .points-cell {
            width: 10%;
            text-align: center;
        }

        .coach-row {
            background: #f5f5f5;
            font-weight: bold;
        }

        /* PERFORMANCE STATS */
        .performance-title {
            width: 100%;
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            padding: 5px;
            background: #e0e0e0;
            border: 2px solid #000;
            margin-top: 3px;
            clear: both;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
            margin-top: 2px;
        }

        .stats-table th,
        .stats-table td {
            border: 1px solid #000;
            padding: 2px 3px;
            text-align: center;
        }

        .stats-table th {
            background: #e0e0e0;
            font-weight: bold;
        }

        .skill-category {
            background: #f0f0f0;
            font-weight: bold;
        }

        .total-row {
            font-weight: bold;
            background: #f8f8f8;
        }

        .best-scorer-row {
            background: #e8e8e8;
            font-weight: bold;
        }

        /* SIGNATURES */
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
            font-size: 7pt;
        }

        .signatures-table td {
            border: 1px solid #000;
            padding: 15px 5px;
            vertical-align: bottom;
        }

        /* LEGEND */
        .legend {
            font-size: 6pt;
            margin-top: 2px;
            padding: 2px;
        }

        /* UTILITIES */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    @if (!isset($isPdf) || !$isPdf)
        <div class="no-print" style="background: #f5f5f5; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd;">
            <div style="max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="margin: 0; font-size: 16px; color: #333;">Volleyball Scoresheet Preview</h2>
                    <p style="margin: 3px 0 0 0; font-size: 12px; color: #666;">
                        Game #{{ $game->id }} - {{ $game->team1->team_name }} vs {{ $game->team2->team_name }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('pdf.volleyball-scoresheet', $game->id) }}"
                        style="display: inline-block; background: #007bff; color: white; padding: 10px 18px; 
                        border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 13px;">
                        📥 Download PDF
                    </a>
                    <a href="{{ url()->previous() }}"
                        style="display: inline-block; background: #6c757d; color: white; padding: 10px 18px; 
                        border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 13px; margin-left: 8px;">
                        ← Back
                    </a>
                </div>
            </div>
        </div>
    @endif

    @php
        $liveData = $liveData ?? [];
        $setScores = $liveData['set_scores'] ?? [];
        
        // Function to get set score
        $getSetScore = function($setIndex, $teamKey) use ($setScores) {
            foreach ($setScores as $s) {
                if (isset($s['set']) && (int)$s['set'] === $setIndex) {
                    return $s[$teamKey] ?? '—';
                }
            }
            return '—';
        };

        // Get set checkmarks for players
        $runningScores = $liveData['running_scores'] ?? [];
        $team1Checks = [];
        $team2Checks = [];
        
        foreach ($team1Players as $player) {
            $team1Checks[$player->number] = [1 => false, 2 => false, 3 => false, 4 => false, 5 => false];
        }
        foreach ($team2Players as $player) {
            $team2Checks[$player->number] = [1 => false, 2 => false, 3 => false, 4 => false, 5 => false];
        }
        
        foreach ($runningScores as $rs) {
            $set = (int)($rs['set'] ?? 1);
            $team = $rs['team'] ?? '';
            $playerNum = $rs['player'] ?? null;
            
            if ($playerNum && $set >= 1 && $set <= 5) {
                if ($team === 'A' || $team === 'team1') {
                    if (isset($team1Checks[$playerNum])) {
                        $team1Checks[$playerNum][$set] = true;
                    }
                } elseif ($team === 'B' || $team === 'team2') {
                    if (isset($team2Checks[$playerNum])) {
                        $team2Checks[$playerNum][$set] = true;
                    }
                }
            }
        }
    @endphp

    <!-- HEADER -->
    <table class="header-container">
        <tr class="header-row">
            <td class="header-logo">
                @if(isset($logoLeft) && $logoLeft)
                    <img src="data:image/png;base64,{{ $logoLeft }}" alt="Logo">
                @endif
            </td>
            <td class="header-title">
                <h2>P-{{ $game->id }} VOLLEYBALL • Match result</h2>
                <h1>{{ strtoupper($game->bracket->tournament->name ?? 'VOLLEYBALL TOURNAMENT') }}</h1>
                <h3>{{ $game->bracket->name ?? 'CHAMPIONSHIP' }}</h3>
            </td>
            <td class="header-logo">
                @if(isset($logoRight) && $logoRight)
                    <img src="data:image/png;base64,{{ $logoRight }}" alt="Logo">
                @endif
            </td>
        </tr>
    </table>

    <!-- META INFO & TEAMS TABLE -->
    <div style="width: 100%; margin-bottom: 3px;">
        <!-- Teams Scores Table (Right) -->
        <table class="teams-table">
            <tr>
                <th>Teams</th>
                <th>Sets</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>Total</th>
            </tr>
            <tr>
                <td class="team-name-cell">{{ strtoupper(substr($game->team1->team_name ?? 'TEAM A', 0, 3)) }}</td>
                <td><strong>{{ $liveData['team1_sets_won'] ?? 0 }}</strong></td>
                <td>{{ $getSetScore(1, 'team1') }}</td>
                <td>{{ $getSetScore(2, 'team1') }}</td>
                <td>{{ $getSetScore(3, 'team1') }}</td>
                <td>{{ $getSetScore(4, 'team1') }}</td>
                <td>{{ $getSetScore(5, 'team1') }}</td>
                <td><strong>{{ array_sum(array_column($setScores, 'team1')) }}</strong></td>
            </tr>
            <tr>
                <td class="team-name-cell">{{ strtoupper(substr($game->team2->team_name ?? 'TEAM B', 0, 3)) }}</td>
                <td><strong>{{ $liveData['team2_sets_won'] ?? 0 }}</strong></td>
                <td>{{ $getSetScore(1, 'team2') }}</td>
                <td>{{ $getSetScore(2, 'team2') }}</td>
                <td>{{ $getSetScore(3, 'team2') }}</td>
                <td>{{ $getSetScore(4, 'team2') }}</td>
                <td>{{ $getSetScore(5, 'team2') }}</td>
                <td><strong>{{ array_sum(array_column($setScores, 'team2')) }}</strong></td>
            </tr>
        </table>

        <!-- Meta Info (Left) -->
        <table class="meta-table" style="width: 63%;">
            <tr>
                <td class="meta-label" style="width: 15%;">Match:</td>
                <td style="width: 18%;">{{ $game->id }}</td>
                <td class="meta-label" style="width: 15%;">Date:</td>
                <td style="width: 18%;">{{ $game->started_at ? $game->started_at->format('n/j/Y') : now()->format('n/j/Y') }}</td>
                <td class="meta-label" style="width: 18%;">Spectators:</td>
                <td style="width: 16%;">{{ $game->spectators ?? '—' }}</td>
            </tr>
            <tr>
                <td class="meta-label">City:</td>
                <td>{{ strtoupper($game->city ?? 'CITY') }}</td>
                <td class="meta-label" rowspan="2" style="vertical-align: top;">Match duration:</td>
                <td rowspan="2" style="vertical-align: top;">
                    <strong>Start:</strong> {{ $game->started_at ? $game->started_at->format('H:i') : '00:00' }}<br>
                    <strong>End:</strong> {{ $game->ended_at ? $game->ended_at->format('H:i') : '00:00' }}<br>
                    <strong>Total:</strong> {{ $game->duration ?? '0:00' }}
                </td>
                <td colspan="2" rowspan="2">
                    @php
                        $setDurations = [];
                        foreach ($setScores as $s) {
                            $setDurations[] = $s['duration'] ?? '0:00';
                        }
                    @endphp
                    <strong>Set duration:</strong><br>
                    {{ implode(' | ', $setDurations) }}
                </td>
            </tr>
            <tr>
                <td class="meta-label">Hall:</td>
                <td>{{ strtoupper($game->venue ?? 'VENUE') }}</td>
            </tr>
        </table>
        <div style="clear: both;"></div>
    </div>

    <!-- REFEREES -->
    <div class="referees-row">
        <strong>Referees:</strong> {{ $game->referee ?? '_______________' }} & {{ $game->assistant_referee_1 ?? '_______________' }}
    </div>

    <!-- TEAM ROSTERS -->
    <div class="rosters-container clearfix">
        <!-- TEAM A (LEFT) -->
        <table class="roster-table roster-left">
            <tr>
                <th colspan="8" class="roster-header">{{ strtoupper($game->team1->team_name ?? 'TEAM A') }}</th>
            </tr>
            <tr>
                <th style="width: 5%;">L</th>
                <th class="player-number">#</th>
                <th class="player-name">Player Name</th>
                <th class="set-check">1</th>
                <th class="set-check">2</th>
                <th class="set-check">3</th>
                <th class="set-check">4</th>
                <th class="set-check">5</th>
            </tr>
            @foreach($team1Players as $index => $player)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td class="player-number">{{ $player->number ?? '00' }}</td>
                    <td class="player-name">{{ strtoupper($player->name ?? '___________') }}</td>
                    @for($s = 1; $s <= 5; $s++)
                        <td class="set-check">
                            {{ isset($team1Checks[$player->number][$s]) && $team1Checks[$player->number][$s] ? '■' : '' }}
                        </td>
                    @endfor
                </tr>
            @endforeach
            @for($i = count($team1Players); $i < 12; $i++)
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
            <tr class="coach-row">
                <td colspan="2"><strong>Coach:</strong></td>
                <td colspan="6">{{ strtoupper($game->team1->coach_name ?? '___________') }}</td>
            </tr>
            <tr class="coach-row">
                <td colspan="2"><strong>Assistant:</strong></td>
                <td colspan="6">{{ strtoupper($game->team1->assistant_coach ?? '___________') }}</td>
            </tr>
        </table>

        <!-- TEAM B (RIGHT) -->
        <table class="roster-table roster-right">
            <tr>
                <th colspan="8" class="roster-header">{{ strtoupper($game->team2->team_name ?? 'TEAM B') }}</th>
            </tr>
            <tr>
                <th style="width: 5%;">L</th>
                <th class="player-number">#</th>
                <th class="player-name">Player Name</th>
                <th class="set-check">1</th>
                <th class="set-check">2</th>
                <th class="set-check">3</th>
                <th class="set-check">4</th>
                <th class="set-check">5</th>
            </tr>
            @foreach($team2Players as $index => $player)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td class="player-number">{{ $player->number ?? '00' }}</td>
                    <td class="player-name">{{ strtoupper($player->name ?? '___________') }}</td>
                    @for($s = 1; $s <= 5; $s++)
                        <td class="set-check">
                            {{ isset($team2Checks[$player->number][$s]) && $team2Checks[$player->number][$s] ? '■' : '' }}
                        </td>
                    @endfor
                </tr>
            @endforeach
            @for($i = count($team2Players); $i < 12; $i++)
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
            <tr class="coach-row">
                <td colspan="2"><strong>Coach:</strong></td>
                <td colspan="6">{{ strtoupper($game->team2->coach_name ?? '___________') }}</td>
            </tr>
            <tr class="coach-row">
                <td colspan="2"><strong>Assistant:</strong></td>
                <td colspan="6">{{ strtoupper($game->team2->assistant_coach ?? '___________') }}</td>
            </tr>
        </table>
    </div>

    <!-- TEAMS AND PLAYERS PERFORMANCES - WITH DATA -->
<div class="stats-wrapper" style="page-break-inside: avoid;">
    <div class="stats-title">TEAMS AND PLAYERS PERFORMANCES</div>
    
    <!-- SCORING SKILLS TABLE -->
    <table style="width: 100%; border-collapse: collapse; font-size: 5pt;">
        <tbody>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Won Pts</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Atts</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">No Name</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Scoring Skills</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Won Pts</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Atts</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">No Name</td>
        </tr>
        
        <!-- SPIKE SECTION -->
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['total_kills'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['total_attack_attempts'] ?? 0 }}</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Spike</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['total_kills'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['total_attack_attempts'] ?? 0 }}</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['top_killer']['kills'] ?? '' }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['top_killer']['attempts'] ?? '' }}</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['top_killer']['number'] ?? '' }}</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['top_killer']['kills'] ?? '' }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['top_killer']['attempts'] ?? '' }}</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['top_killer']['number'] ?? '' }}</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;"></td>
        </tr>
        
        <!-- BLOCK SECTION -->
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['total_blocks'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">-</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Block</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['total_blocks'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">-</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['top_blocker']['blocks'] ?? '' }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['top_blocker']['number'] ?? '' }}</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['top_blocker']['blocks'] ?? '' }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['top_blocker']['number'] ?? '' }}</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;"></td>
        </tr>
        
        <!-- SERVE SECTION -->
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['total_aces'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">-</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Serve</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['total_aces'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">-</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['top_server']['aces'] ?? '' }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['top_server']['number'] ?? '' }}</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['top_server']['aces'] ?? '' }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['top_server']['number'] ?? '' }}</td>
        </tr>
        
        <!-- OPPONENT ERROR SECTION -->
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['opponent_errors'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Opp. error Total</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['opponent_errors'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        
        <!-- BEST SCORER -->
        <tr style="background-color: #e8e8e8;">
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['best_scorer']['points'] ?? '' }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; font-weight: bold; text-align: center;">{{ $team1Stats['best_scorer']['number'] ?? '' }}</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Best Scorer</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['best_scorer']['points'] ?? '' }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; font-weight: bold; text-align: center;">{{ $team2Stats['best_scorer']['number'] ?? '' }}</td>
        </tr>
        </tbody>
    </table>

    <br>

    <!-- NON-SCORING SKILLS TABLE -->
    <table style="width: 100%; border-collapse: collapse; font-size: 5pt;">
        <tbody>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Excellent</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Atts</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">No Name</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Non scoring Skills</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Excellent</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Atts</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">No Name</td>
        </tr>
        
        <!-- DIG SECTION -->
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['total_digs'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">-</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Dig</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['total_digs'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">-</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['top_digger']['digs'] ?? '' }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['top_digger']['attempts'] ?? '' }}</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['top_digger']['number'] ?? '' }}</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['top_digger']['digs'] ?? '' }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['top_digger']['attempts'] ?? '' }}</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['top_digger']['number'] ?? '' }}</td>
        </tr>
        
        <!-- SET SECTION -->
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team1Stats['total_assists'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">-</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Set</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">{{ $team2Stats['total_assists'] ?? 0 }}</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px; text-align: center;">-</td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        
        <!-- RECEPTION SECTION -->
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Reception</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; font-weight: bold;">Success - Faults # Attempts</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; font-weight: bold;">Success - Faults # Attempts</td>
        </tr>
        </tbody>
    </table>
</div>
    <!-- SIGNATURES -->
    <table class="signatures-table">
        <tr>
            <td style="width: 33%;">
                <strong>Name game jury president:</strong><br><br>
                _________________________________<br><br>
                <strong>Signature:</strong>
            </td>
            <td style="width: 33%;">
                <strong>1st Referee:</strong><br><br>
                _________________________________<br><br>
                <strong>Signature:</strong>
            </td>
            <td style="width: 34%;">
                <strong>2nd Referee:</strong><br><br>
                _________________________________<br><br>
                <strong>Signature:</strong>
            </td>
        </tr>
    </table>

    <!-- LEGEND -->
    <div class="legend">
        <strong>■</strong> Starting line-up &nbsp;&nbsp;
        <strong>Pts</strong> = Points scored &nbsp;&nbsp;
        <strong>Atts</strong> = Attempts &nbsp;&nbsp;
        <strong>nn</strong> = Captain &nbsp;&nbsp;
        <strong>L</strong> = Libero &nbsp;&nbsp;
        <strong>□</strong> Substitute &nbsp;&nbsp;
        <strong>Opp</strong> = Opponent
    </div>
</body>
</html>