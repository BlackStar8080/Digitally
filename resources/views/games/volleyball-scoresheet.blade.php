{{-- resources/views/games/volleyball-scoresheet.blade.php --}}

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Volleyball Scoresheet - {{ $game->id }}</title>
    <style>
        @page {
            size: 8.5in 14in;
            margin: 0.15in;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 7pt;
            line-height: 1.0;
            color: #000;
        }

        .sheet {
            width: 100%;
            page-break-after: avoid;
            page-break-inside: avoid;
        }

        /* HEADER */
        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 3px;
            padding-bottom: 2px;
        }

        .header-left,
        .header-center,
        .header-right {
            display: table-cell;
            vertical-align: top;
            padding: 1px 3px;
        }

        .header-left {
            width: 25%;
            border-right: 1px solid #000;
        }

        .header-center {
            width: 50%;
            text-align: center;
            border-right: 1px solid #000;
        }

        .header-right {
            width: 25%;
        }

        .header-center h1 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 0px;
        }

        .tournament-name {
            font-size: 8pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .match-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        .match-info-table td {
            border: 1px solid #000;
            padding: 1px 3px;
            font-size: 6pt;
        }

        .match-info-table .label {
            font-weight: bold;
            width: 30%;
        }

        /* MATCH META */
        .match-meta {
            display: table;
            width: 100%;
            border: 1px solid #000;
            margin-bottom: 2px;
        }

        .meta-cell {
            display: table-cell;
            border-right: 1px solid #000;
            padding: 2px 3px;
            font-size: 6.5pt;
            vertical-align: top;
        }

        .meta-cell:last-child {
            border-right: none;
        }

        .meta-cell strong {
            display: block;
            font-weight: bold;
        }

        .meta-value {
            font-size: 7pt;
        }

        /* MAIN CONTENT - 3 COLUMN */
        .content-wrapper {
            display: table;
            width: 100%;
            margin-bottom: 2px;
            border: 1px solid #000;
        }

        .roster-column {
            display: table-cell;
            width: 33.33%;
            border-right: 1px solid #000;
            padding: 2px;
            vertical-align: top;
            font-size: 6pt; 
        }

        .roster-column:last-child {
            border-right: none;
        }

        .roster-header {
            font-weight: bold;
            font-size: 6.5pt;
            text-align: left;
            border-bottom: 1px solid #000;
            padding-bottom: 1px;
            margin-bottom: 1px;
            background: #f0f0f0;
        }

        .player-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #ccc;
            padding: 0.5px 0;
            margin-bottom: 0.5px;
            font-size: 5pt;
        }

        .player-number {
            display: table-cell;
            width: 15%;
            text-align: center;
            font-weight: bold;
            border-right: 1px solid #ccc;
            padding: 0.5px;
        }

        .player-name {
            display: table-cell;
            width: 50%;
            padding: 0 1px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .player-checks {
            display: table-cell;
            width: 35%;
            text-align: center;
            font-size: 4pt;
            padding: 0.5px;
        }

        .check-box {
            display: inline-block;
            width: 8px;
            height: 8px;
            border: 1px solid #000;
            margin: 0 0.5px;
        }

        .check-box.checked {
            background: #000;
        }

        /* BOTTOM STATS */
        .stats-wrapper {
            width: 100%;
            border: 1px solid #000;
            margin-bottom: 2px;
        }

        .stats-title {
            font-weight: bold;
            text-align: center;
            padding: 2px;
            font-size: 10pt;
            background: #f0f0f0;
        }

        .stats-content {
            display: table;
            width: 100%;
        }

        .team-stats {
            display: table-cell;
            width: 50%;
            border-right: 1px solid #000;
            padding: 2px;
            vertical-align: top;
        }

        .team-stats:last-child {
            border-right: none;
        }

        .stats-section {
            margin-bottom: 3px;
        }

        .stats-section-title {
            font-weight: bold;
            font-size: 6pt;
            margin-bottom: 1px;
            text-align: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 0.5px;
        }

        .stat-row {
            display: table;
            width: 100%;
            font-size: 5.5pt;
            border-bottom: 1px solid #ccc;
            padding: 0.5px 0;
        }

        .stat-label {
            display: table-cell;
            width: 60%;
            padding: 0 1px;
        }

        .stat-value {
            display: table-cell;
            width: 40%;
            text-align: center;
            border-left: 1px solid #ccc;
        }

        .stat-row:last-child {
            border-bottom: none;
        }

        /* SIGNATURES */
        .signatures {
            display: table;
            width: 100%;
            border: 1px solid #000;
            margin-bottom: 2px;
        }

        .sig-cell {
            display: table-cell;
            width: 50%;
            border-right: 1px solid #000;
            padding: 2px 3px;
            font-size: 6pt;
            vertical-align: top;
        }

        .sig-cell:last-child {
            border-right: none;
        }

        .sig-line {
            margin: 2px 0;
            border-bottom: 1px solid #000;
            padding-top: 2px;
            min-height: 12px;
        }

        /* UTILITIES */
        .no-print {
            display: none;
        }

        strong {
            font-weight: bold;
        }

        .centered {
            text-align: center;
        }

        .small-text {
            font-size: 5pt;
        }
    </style>
</head>

<body>
    @if (!isset($isPdf) || !$isPdf)
        <div style="background: #f5f5f5; padding: 10px; margin-bottom: 15px; border-bottom: 1.5px solid #ddd;">
            <div
                style="max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="margin: 0; font-size: 16px; color: #333;">Volleyball Scoresheet Preview</h2>
                    <p style="margin: 3px 0 0 0; font-size: 12px; color: #666;">Game #{{ $game->id }} -
                        {{ $game->team1->team_name ?? 'Team A' }} vs {{ $game->team2->team_name ?? 'Team B' }}</p>
                </div>
                <div>
                    <a href="{{ route('pdf.volleyball-scoresheet', $game->id) }}"
                        style="display: inline-block; background: #007bff; color: white; padding: 8px 15px; 
                          border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 12px;
                          box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: background 0.3s;"
                        onmouseover="this.style.background='#0056b3'" onmouseout="this.style.background='#007bff'">
                        📥 Download PDF
                    </a>
                    <a href="{{ url()->previous() }}"
                        style="display: inline-block; background: #6c757d; color: white; padding: 8px 15px; 
                          border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 12px;
                          box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-left: 8px; transition: background 0.3s;"
                        onmouseover="this.style.background='#545b62'" onmouseout="this.style.background='#6c757d'">
                        ← Back
                    </a>
                </div>
            </div>
        </div>
    @endif

    @php
        $liveData = $liveData ?? [];
        $setScores = $liveData['set_scores'] ?? [];
        $runningScores = $liveData['running_scores'] ?? [];

        // Build lookup for checked numbers
        $teamAChecks = [];
        $teamBChecks = [];

        for ($s = 1; $s <= 5; $s++) {
            $teamAChecks[$s] = array_fill(1, 30, false);
            $teamBChecks[$s] = array_fill(1, 30, false);
        }

        foreach ($runningScores as $rs) {
            $set = (int) ($rs['set'] ?? 1);
            $team = $rs['team'] ?? '';
            $score = (int) ($rs['score'] ?? 0);

            if ($set >= 1 && $set <= 5 && $score >= 1 && $score <= 30) {
                if ($team === 'A' || $team === 'team1') {
                    $teamAChecks[$set][$score] = true;
                }
                if ($team === 'B' || $team === 'team2') {
                    $teamBChecks[$set][$score] = true;
                }
            }
        }

        $getSetScore = function ($setIndex, $teamKey) use ($setScores) {
            if (is_array($setScores)) {
                foreach ($setScores as $s) {
                    if (isset($s['set']) && (int) $s['set'] === $setIndex) {
                        return $teamKey === 'team1' ? $s['team1'] ?? '' : $s['team2'] ?? '';
                    }
                }
            }
            return '';
        };
    @endphp

    <div class="sheet">
        <!-- HEADER -->
        <div class="header">
            <div class="header-left">
                <strong>Match:</strong> {{ $game->id }}<br>
                <strong>Date:</strong>
                {{ $game->started_at ? $game->started_at->format('m/d/Y') : now()->format('m/d/Y') }}<br>
                <strong>City:</strong> {{ strtoupper($game->venue ?? '---') }}<br>
                <strong>Hall:</strong> {{ strtoupper($game->venue ?? '---') }}
            </div>
            <div class="header-center">
                <div class="tournament-name">
                    {{ strtoupper($game->bracket->tournament->name ?? 'VOLLEYBALL TOURNAMENT') }}</div>
                <h1>VOLLEYBALL • Match result</h1>
                <div class="small-text">{{ $game->started_at ? $game->started_at->format('H:i') : '00:00' }}</div>
            </div>
            <div class="header-right">
                <strong>Teams</strong><br>
                <table class="match-info-table">
                    <tr>
                        <td class="label">Sets</td>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                        <td>4</td>
                        <td>5</td>
                        <td>Total</td>
                    </tr>
                    <tr>
                        <td class="label">{{ strtoupper($game->team1->team_name ?? 'Team A') }}</td>
                        <td>{{ $getSetScore(1, 'team1') ?: '—' }}</td>
                        <td>{{ $getSetScore(2, 'team1') ?: '—' }}</td>
                        <td>{{ $getSetScore(3, 'team1') ?: '—' }}</td>
                        <td>{{ $getSetScore(4, 'team1') ?: '—' }}</td>
                        <td>{{ $getSetScore(5, 'team1') ?: '—' }}</td>
                        <td><strong>{{ $liveData['team1_score'] ?? 0 }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">{{ strtoupper($game->team2->team_name ?? 'Team B') }}</td>
                        <td>{{ $getSetScore(1, 'team2') ?: '—' }}</td>
                        <td>{{ $getSetScore(2, 'team2') ?: '—' }}</td>
                        <td>{{ $getSetScore(3, 'team2') ?: '—' }}</td>
                        <td>{{ $getSetScore(4, 'team2') ?: '—' }}</td>
                        <td>{{ $getSetScore(5, 'team2') ?: '—' }}</td>
                        <td><strong>{{ $liveData['team2_score'] ?? 0 }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- REFEREES INFO -->
        <div class="match-meta">
            <div class="meta-cell" style="width: 25%;">
                <strong>Referees:</strong> <span class="meta-value">{{ $game->referee ?? 'N/A' }}</span>
            </div>
            
            <div class="meta-cell" style="width: 25%;">
                <strong>Spectators:</strong> <span class="meta-value">___________</span>
            </div>
        </div>

        <!-- MAIN CONTENT: ROSTERS & SCORING -->
        <div class="content-wrapper">
            <!-- TEAM A ROSTER -->
            <div class="roster-column">
                <div class="roster-header">{{ strtoupper($game->team1->team_name ?? 'Team A') }}</div>
                @foreach ($team1Players as $player)
                    <div class="player-row">
                        <div class="player-number">{{ $player->number ?? '00' }}</div>
                        <div class="player-name">
                            @if ($player->first_name && $player->last_name)
                                {{ strtoupper($player->last_name) }}
                                {{ strtoupper(substr($player->first_name, 0, 1)) }}.
                            @elseif($player->name)
                                {{ strtoupper($player->name) }}
                            @else
                                ___________
                            @endif
                        </div>
                        <div class="player-checks">
                            @for ($s = 1; $s <= 5; $s++)
                                @php $num = (int)($player->number ?? 0); @endphp
                                <div
                                    class="check-box {{ isset($teamAChecks[$s][$num]) && $teamAChecks[$s][$num] ? 'checked' : '' }}">
                                </div>
                            @endfor
                        </div>
                    </div>
                @endforeach
                 <div class="meta-cell" style="width: 25%;">
                <strong>Coach A:</strong> <span class="meta-value">{{ $game->team1->coach_name ?? '___' }}</span>
            </div>
            
            </div>

            <!-- TEAM B ROSTER -->
            <div class="roster-column">
                <div class="roster-header">{{ strtoupper($game->team2->team_name ?? 'Team B') }}</div>
                @foreach ($team2Players as $player)
                    <div class="player-row">
                        <div class="player-number">{{ $player->number ?? '00' }}</div>
                        <div class="player-name">
                            @if ($player->first_name && $player->last_name)
                                {{ strtoupper($player->last_name) }}
                                {{ strtoupper(substr($player->first_name, 0, 1)) }}.
                            @elseif($player->name)
                                {{ strtoupper($player->name) }}
                            @else
                                ___________
                            @endif
                        </div>
                        <div class="player-checks">
                            @for ($s = 1; $s <= 5; $s++)
                                @php $num = (int)($player->number ?? 0); @endphp
                                <div
                                    class="check-box {{ isset($teamBChecks[$s][$num]) && $teamBChecks[$s][$num] ? 'checked' : '' }}">
                                </div>
                            @endfor
                        </div>
                    </div>
                @endforeach
                <div class="meta-cell" style="width: 25%;">
                <strong>Coach B:</strong> <span class="meta-value">{{ $game->team2->coach_name ?? '___' }}</span>
            </div>
            </div>
        </div>



<!-- TEAMS AND PLAYERS PERFORMANCES - DOMPDF FIX -->
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
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Spike</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Block</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Serve</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Opp. error Total</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
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
        <tr style="background-color: #e8e8e8;">
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; font-weight: bold;">Best Scorer</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; font-weight: bold;">Best Scorer</td>
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
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Dig</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; background-color: #e8e8e8;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px;">Player #</td>
        </tr>
        <tr>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
            <td style="width: 18%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold; background-color: #e8e8e8;">Set</td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 8%; border: 1px solid #000; padding: 2px;"></td>
            <td style="width: 10%; border: 1px solid #000; padding: 2px; text-align: center; font-weight: bold;">Total Team</td>
        </tr>
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

    </div>
</body>

</html>
