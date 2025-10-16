{{-- resources/views/games/volleyball-scoresheet.blade.php --}}

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Volleyball Scoresheet - {{ $game->id }}</title>
    <style>
        @page {
            size: 14in 8.5in;
            margin: 0.25in;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.2;
            color: #000;
        }

        .sheet {
            width: 100%;
            page-break-after: avoid;
            page-break-inside: avoid;
        }

        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            margin-bottom: 5px;
        }

        .header-flex {
            display: table;
            width: 100%;
        }

        .header-left, .header-center, .header-right {
            display: table-cell;
            vertical-align: middle;
        }

        .header-left {
            width: 20%;
            font-size: 9pt;
        }

        .header-center {
            width: 60%;
            text-align: center;
        }

        .header-center h1 {
            font-size: 18pt;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 3px;
            font-weight: bold;
        }

        .header-right {
            width: 20%;
            text-align: right;
            font-size: 9pt;
        }

        .meta-section {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .meta-box {
            display: table-cell;
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 8pt;
        }

        .meta-box:not(:last-child) {
            border-right: none;
        }

        .teams-section {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .team-panel {
            display: table-cell;
            width: 50%;
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        .team-panel:first-child {
            border-right: none;
        }

        .team-name {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .team-content {
            display: table;
            width: 100%;
        }

        .roster-col {
            display: table-cell;
            width: 65%;
            font-size: 8pt;
            vertical-align: top;
        }

        .notes-col {
            display: table-cell;
            width: 35%;
            border-left: 1px solid #000;
            padding-left: 4px;
            vertical-align: top;
        }

        .notes-box {
            border: 1px solid #000;
            height: 60px;
            margin-top: 2px;
        }

        .sets-section {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .set-column {
            display: table-cell;
            width: 33.33%;
            padding: 2px;
            vertical-align: top;
        }

        .set-card {
            border: 1px solid #000;
            padding: 4px;
            height: 180px;
        }

        .set-title {
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 3px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        .score-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .score-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            border: 1px solid #000;
            padding: 3px;
            font-weight: bold;
            font-size: 10pt;
        }

        .score-box:first-child {
            border-right: none;
        }

        .running-title {
            text-align: center;
            font-weight: bold;
            font-size: 7pt;
            margin: 4px 0 2px 0;
            border-top: 1px solid #000;
            padding-top: 3px;
        }

        .running-numbers {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }

        .team-numbers {
            display: table-cell;
            width: 50%;
        }

        .team-numbers:first-child {
            padding-right: 2px;
        }

        .team-numbers:last-child {
            padding-left: 2px;
        }

        .team-label {
            text-align: center;
            font-weight: bold;
            font-size: 7pt;
            margin-bottom: 2px;
        }

        .number-grid {
            border: 1px solid #000;
        }

        .number-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #000;
        }

        .number-row:last-child {
            border-bottom: none;
        }

        .number-cell {
            display: table-cell;
            width: 10%;
            text-align: center;
            border-right: 1px solid #000;
            padding: 1px 0;
            font-size: 7pt;
            height: 12px;
            line-height: 12px;
            position: relative;
        }

        .number-cell:last-child {
            border-right: none;
        }

        .number-cell.checked {
            background-color: #000;
            color: #fff;
            font-weight: bold;
        }

        .number-cell.checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 9pt;
            font-weight: bold;
        }

        .timeout-section {
            display: table;
            width: 100%;
            margin-top: 3px;
        }

        .timeout-box {
            display: table-cell;
            width: 50%;
            border: 1px solid #000;
            padding: 2px;
            font-size: 6pt;
        }

        .timeout-box:first-child {
            border-right: none;
        }

        .bottom-section {
            display: table;
            width: 100%;
            margin-top: 5px;
        }

        .mvp-box, .sig-box {
            display: table-cell;
            width: 50%;
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        .mvp-box {
            border-right: none;
        }

        .section-title {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 4px;
        }

        .mvp-info {
            font-size: 8pt;
            margin-bottom: 2px;
        }

        .remarks-label {
            font-weight: bold;
            font-size: 8pt;
            margin-top: 4px;
            margin-bottom: 2px;
        }

        .remarks-box {
            border: 1px solid #000;
            height: 25px;
        }

        .sig-line {
            font-size: 8pt;
            margin-bottom: 5px;
        }

        .final-score {
            font-size: 8pt;
            margin-top: 5px;
            padding-top: 4px;
            border-top: 1px solid #000;
        }

        .no-print {
            display: none;
        }

        strong {
            font-weight: bold;
        }

        .muted {
            color: #555;
            font-size: 7pt;
        }
    </style>
</head>
<body>
    @if(!isset($isPdf) || !$isPdf)
    <div style="background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-bottom: 2px solid #ddd;">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="margin: 0; font-size: 18px; color: #333;">Volleyball Scoresheet Preview</h2>
                <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">Game #{{ $game->id }} - {{ $game->team1->team_name ?? 'Team A' }} vs {{ $game->team2->team_name ?? 'Team B' }}</p>
            </div>
            <div>
                <a href="{{ route('pdf.volleyball-scoresheet', $game->id) }}" 
                   style="display: inline-block; background: #007bff; color: white; padding: 10px 20px; 
                          border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 14px;
                          box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: background 0.3s;"
                   onmouseover="this.style.background='#0056b3'" 
                   onmouseout="this.style.background='#007bff'">
                    📥 Download PDF
                </a>
                <a href="{{ url()->previous() }}" 
                   style="display: inline-block; background: #6c757d; color: white; padding: 10px 20px; 
                          border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 14px;
                          box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-left: 10px; transition: background 0.3s;"
                   onmouseover="this.style.background='#545b62'" 
                   onmouseout="this.style.background='#6c757d'">
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
        
        // Build lookup arrays for checked numbers per set and team
        $maxNumberPerSet = 30;
        $teamAChecks = [];
        $teamBChecks = [];
        
        for ($s = 1; $s <= 5; $s++) {
            $teamAChecks[$s] = array_fill(1, $maxNumberPerSet, false);
            $teamBChecks[$s] = array_fill(1, $maxNumberPerSet, false);
        }
        
        // Process running scores to mark which numbers should be checked
        // Expected format: [['team' => 'A' or 'B', 'score' => 1-30, 'set' => 1-5], ...]
        foreach ($runningScores as $rs) {
            $set = (int) ($rs['set'] ?? 1);
            $team = $rs['team'] ?? '';
            $score = (int) ($rs['score'] ?? 0);
            
            if ($set < 1 || $set > 5) continue;
            
            if ($score >= 1 && $score <= $maxNumberPerSet) {
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
                    if ((isset($s['set']) && (int) $s['set'] === $setIndex)) {
                        return $teamKey === 'team1' ? ($s['team1'] ?? '') : ($s['team2'] ?? '');
                    }
                }
            }
            return '';
        };

        $bestPlayer = null;
        if (!empty($liveData['best_player_id'])) {
            $bestPlayer = $team1Players->firstWhere('id', $liveData['best_player_id']) ??
                          $team2Players->firstWhere('id', $liveData['best_player_id']);
        }
    @endphp

    <div class="sheet">
        <!-- Header -->
        <div class="header">
            <div class="header-flex">
                <div class="header-left">
                    <div style="font-size: 8pt; color: #666;">Organized by:</div>
                    <div style="font-size: 7pt; margin-top: 2px;">{{ $game->bracket->tournament->organizer ?? 'Sports Committee' }}</div>
                </div>
                <div class="header-center">
                    <div style="font-size: 11pt; font-weight: bold; margin-bottom: 2px;">{{ strtoupper($game->bracket->tournament->name ?? 'TOURNAMENT') }}</div>
                    <h1>VOLLEYBALL SCORESHEET</h1>
                    <div class="muted" style="margin-top: 2px;">
                        {{ $game->started_at ? $game->started_at->format('M d, Y H:i') : now()->format('M d, Y') }} | Game #{{ $game->id }}
                    </div>
                </div>
                <div class="header-right">
                    <div><strong>Venue:</strong></div>
                    <div style="margin-top: 2px;">{{ strtoupper($game->venue ?? '---') }}</div>
                    <div style="margin-top: 4px; font-size: 8pt;"><strong>Referee:</strong> {{ $game->referee ?? '________' }}</div>
                </div>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="meta-section">
            <div class="meta-box" style="width: 30%;">
                <strong>Team A:</strong> {{ strtoupper($game->team1->team_name ?? 'Team A') }}<br>
                <strong>Coach:</strong> {{ $game->team1->coach_name ?? '________' }}
            </div>
            <div class="meta-box" style="width: 30%;">
                <strong>Team B:</strong> {{ strtoupper($game->team2->team_name ?? 'Team B') }}<br>
                <strong>Coach:</strong> {{ $game->team2->coach_name ?? '________' }}
            </div>
            <div class="meta-box" style="width: 40%;">
                <strong>Initial Server:</strong> {{ $liveData['initial_server'] ?? '____' }} | 
                <strong>Match Type:</strong> Best of 5
            </div>
        </div>

        <!-- Teams -->
        <div class="teams-section">
            <div class="team-panel">
                <div class="team-name">TEAM A - {{ strtoupper($game->team1->team_name ?? '') }}</div>
                <div class="team-content">
                    <div class="roster-col">
                        <strong style="font-size: 8pt;">Roster</strong>
                        @foreach ($team1Players as $p)
                            <div>{{ $loop->iteration }}. {{ $p->name }} <span style="float:right;">#{{ $p->number ?? '00' }}</span></div>
                        @endforeach
                    </div>
                    <div class="notes-col">
                        <strong style="font-size: 8pt;">Stats/Notes</strong>
                        <div class="notes-box"></div>
                    </div>
                </div>
            </div>
            <div class="team-panel">
                <div class="team-name">TEAM B - {{ strtoupper($game->team2->team_name ?? '') }}</div>
                <div class="team-content">
                    <div class="roster-col">
                        <strong style="font-size: 8pt;">Roster</strong>
                        @foreach ($team2Players as $p)
                            <div>{{ $loop->iteration }}. {{ $p->name }} <span style="float:right;">#{{ $p->number ?? '00' }}</span></div>
                        @endforeach
                    </div>
                    <div class="notes-col">
                        <strong style="font-size: 8pt;">Stats/Notes</strong>
                        <div class="notes-box"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sets Row 1 -->
        <div class="sets-section">
            @for ($set = 1; $set <= 3; $set++)
                <div class="set-column">
                    <div class="set-card">
                        <div class="set-title">SET {{ $set }}</div>
                        <div class="score-row">
                            <div class="score-box">A: {{ $getSetScore($set, 'team1') !== '' ? $getSetScore($set, 'team1') : '---' }}</div>
                            <div class="score-box">B: {{ $getSetScore($set, 'team2') !== '' ? $getSetScore($set, 'team2') : '---' }}</div>
                        </div>
                        <div class="running-title">Running Score (1-30)</div>
                        <div class="running-numbers">
                            <div class="team-numbers">
                                <div class="team-label">Team A</div>
                                <div class="number-grid">
                                    @for ($row = 0; $row < 3; $row++)
                                        <div class="number-row">
                                            @for ($col = 1; $col <= 10; $col++)
                                                @php 
                                                    $num = ($row * 10) + $col;
                                                    $isChecked = $teamAChecks[$set][$num] ?? false;
                                                @endphp
                                                <div class="number-cell {{ $isChecked ? 'checked' : '' }}">{{ $num }}</div>
                                            @endfor
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="team-numbers">
                                <div class="team-label">Team B</div>
                                <div class="number-grid">
                                    @for ($row = 0; $row < 3; $row++)
                                        <div class="number-row">
                                            @for ($col = 1; $col <= 10; $col++)
                                                @php 
                                                    $num = ($row * 10) + $col;
                                                    $isChecked = $teamBChecks[$set][$num] ?? false;
                                                @endphp
                                                <div class="number-cell {{ $isChecked ? 'checked' : '' }}">{{ $num }}</div>
                                            @endfor
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="timeout-section">
                            <div class="timeout-box">
                                <strong>Timeouts:</strong> {{ $liveData['team1_timeouts'] ?? '0' }}<br>
                                <strong>Subs:</strong> {{ $liveData['team1_substitutions'] ?? '0' }}
                            </div>
                            <div class="timeout-box">
                                <strong>Timeouts:</strong> {{ $liveData['team2_timeouts'] ?? '0' }}<br>
                                <strong>Subs:</strong> {{ $liveData['team2_substitutions'] ?? '0' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Sets Row 2 -->
        <div class="sets-section">
            @for ($set = 4; $set <= 5; $set++)
                <div class="set-column">
                    <div class="set-card">
                        <div class="set-title">SET {{ $set }}</div>
                        <div class="score-row">
                            <div class="score-box">A: {{ $getSetScore($set, 'team1') !== '' ? $getSetScore($set, 'team1') : '---' }}</div>
                            <div class="score-box">B: {{ $getSetScore($set, 'team2') !== '' ? $getSetScore($set, 'team2') : '---' }}</div>
                        </div>
                        <div class="running-title">Running Score (1-30)</div>
                        <div class="running-numbers">
                            <div class="team-numbers">
                                <div class="team-label">Team A</div>
                                <div class="number-grid">
                                    @for ($row = 0; $row < 3; $row++)
                                        <div class="number-row">
                                            @for ($col = 1; $col <= 10; $col++)
                                                @php $num = ($row * 10) + $col; @endphp
                                                <div class="number-cell">{{ $num }}</div>
                                            @endfor
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="team-numbers">
                                <div class="team-label">Team B</div>
                                <div class="number-grid">
                                    @for ($row = 0; $row < 3; $row++)
                                        <div class="number-row">
                                            @for ($col = 1; $col <= 10; $col++)
                                                @php $num = ($row * 10) + $col; @endphp
                                                <div class="number-cell">{{ $num }}</div>
                                            @endfor
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="timeout-section">
                            <div class="timeout-box">
                                <strong>Timeouts:</strong> {{ $liveData['team1_timeouts'] ?? '0' }}<br>
                                <strong>Subs:</strong> {{ $liveData['team1_substitutions'] ?? '0' }}
                            </div>
                            <div class="timeout-box">
                                <strong>Timeouts:</strong> {{ $liveData['team2_timeouts'] ?? '0' }}<br>
                                <strong>Subs:</strong> {{ $liveData['team2_substitutions'] ?? '0' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
            <!-- Empty column for alignment -->
            <div class="set-column"></div>
        </div>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <div class="mvp-box">
                <div class="section-title">BEST PLAYER / MVP</div>
                @if (!empty($liveData['best_player_stats']) || !empty($bestPlayer))
                    <div class="mvp-info"><strong>Name:</strong> {{ $bestPlayer->name ?? ($liveData['best_player_stats']['name'] ?? '---') }}</div>
                    <div class="mvp-info"><strong>#:</strong> {{ $bestPlayer->number ?? ($liveData['best_player_stats']['number'] ?? '---') }}</div>
                    <div class="mvp-info">
                        <strong>Kills:</strong> {{ $liveData['best_player_stats']['kills'] ?? '0' }} | 
                        <strong>Aces:</strong> {{ $liveData['best_player_stats']['aces'] ?? '0' }} | 
                        <strong>Blocks:</strong> {{ $liveData['best_player_stats']['blocks'] ?? '0' }}
                    </div>
                @else
                    <div class="mvp-info">Name: _____________________________</div>
                    <div class="mvp-info">Stats: Kills ___ | Aces ___ | Blocks ___</div>
                @endif
                <div class="remarks-label">Remarks:</div>
                <div class="remarks-box"></div>
            </div>
            <div class="sig-box">
                <div class="section-title">SIGNATURES</div>
                <div class="sig-line"><strong>Scorekeeper:</strong> ________________________________</div>
                <div class="sig-line"><strong>Referee:</strong> ________________________________</div>
                <div class="sig-line"><strong>Coach A:</strong> ______________ <strong>Coach B:</strong> ______________</div>
                <div class="final-score">
                    <strong>FINAL SETS:</strong> Team A <u>{{ $liveData['team1_score'] ?? ($game->team1_score ?? 0) }}</u> - Team B <u>{{ $liveData['team2_score'] ?? ($game->team2_score ?? 0) }}</u>
                </div>
            </div>
        </div>
    </div>
</body>
</html>