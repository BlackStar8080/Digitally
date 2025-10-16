{{-- resources/views/games/volleyball-scoresheet.blade.php --}}
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Volleyball Scoresheet - {{ $game->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        @page {
            /* Landscape long bondpaper: 13in x 8.5in */
            size: 13in 8.5in landscape;
            margin: 0.2in;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            background: #fff;
            -webkit-print-color-adjust: exact;
            padding: 0;
            margin: 0;
        }

        .sheet {
            width: 12.6in;
            height: 8.1in;
            margin: 0 auto;
            padding: 0.15in;
            border: 1px solid #000;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 10px;
            border-bottom: 2px solid #000;
            margin-bottom: 8px;
        }

        .header .title {
            text-align: center;
            flex: 1;
        }

        .header .title h1 {
            margin: 0;
            font-size: 20px;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .meta {
            width: 100%;
            display:flex;
            gap: 10px;
            margin-top: 6px;
            margin-bottom: 8px;
        }

        .meta .meta-box {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        .teams-row {
            display:flex;
            justify-content:space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .team-panel {
            width: 48%;
            border: 1px solid #000;
            padding: 6px;
            box-sizing: border-box;
        }

        .team-name {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .sets-grid {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-bottom: 8px;
        }

        .set-card {
            border: 1px solid #000;
            padding: 6px;
            box-sizing: border-box;
            min-height: 120px;
        }

        .set-card h4 {
            margin: 0 0 6px 0;
            font-size: 12px;
            text-align: center;
        }

        .set-score-row {
            display:flex;
            justify-content: space-between;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }

        .set-score {
            width: 48%;
            text-align: center;
            font-weight: 700;
            font-size: 14px;
            padding: 4px 0;
            border: 1px solid #000;
        }

        .running-grid {
            width: 100%;
            border-top: 1px solid #000;
            padding-top: 6px;
        }

        /* Running number grid: 10 columns x 5 rows -> up to 50 */
        .numbers-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 2px;
            font-size: 9px;
            margin-bottom: 6px;
        }

        .num-cell {
            border: 1px solid #000;
            height: 18px;
            display:flex;
            align-items:center;
            justify-content:center;
            box-sizing: border-box;
        }

        .checks-row {
            display:flex;
            justify-content:space-between;
            gap:6px;
            align-items:center;
            font-size: 10px;
        }

        .checks-col {
            width: 48%;
            border: 1px solid #000;
            padding: 4px;
            min-height: 22px;
            text-align: center;
            box-sizing: border-box;
        }

        .checks-col .label {
            font-weight:700;
            margin-bottom: 4px;
        }

        .timeouts-subs {
            display:flex;
            gap:8px;
            margin-top:6px;
        }

        .small-box {
            border:1px solid #000;
            padding:6px;
            width: 50%;
            box-sizing:border-box;
            font-size: 10px;
        }

        .bottom-row {
            display:flex;
            justify-content:space-between;
            gap:10px;
            margin-top: 8px;
        }

        .best-player, .signatures {
            border:1px solid #000;
            padding:8px;
            box-sizing:border-box;
        }

        .best-player strong { display:block; margin-bottom:6px; }

        .signatures .sig {
            margin-bottom: 8px;
        }

        /* Utility */
        .muted { color: #444; font-size: 11px; }

        @media print {
            .sheet { border: none; box-shadow: none; margin: 0; padding: 0.12in; }
        }
    </style>
</head>
<body>
    @php
        // Normalize liveData defaults
        $liveData = $liveData ?? [];
        $runningScores = $liveData['running_scores'] ?? []; // array of ['team'=>'A'|'B','score'=>int,'set'=>int]
        // Build lookup arrays per set for quick checks
        $maxNumberPerSet = 50; // grid will show 1..50 (safely covers rallies)
        $teamAChecks = [];
        $teamBChecks = [];
        for ($s = 1; $s <= 5; $s++) {
            $teamAChecks[$s] = array_fill(1, $maxNumberPerSet, false);
            $teamBChecks[$s] = array_fill(1, $maxNumberPerSet, false);
        }
        // runningScores may contain cumulative 'score' per team per set.
        foreach ($runningScores as $rs) {
            $set = (int)($rs['set'] ?? 1);
            $team = ($rs['team'] ?? '');
            $score = (int)($rs['score'] ?? 0);
            if ($set < 1 || $set > 5) continue;
            if ($score >= 1 && $score <= $maxNumberPerSet) {
                if ($team === 'A') $teamAChecks[$set][$score] = true;
                if ($team === 'B') $teamBChecks[$set][$score] = true;
            }
        }

        // set scores if provided
        $setScores = $liveData['set_scores'] ?? [];
        // Helper: retrieve team score for set i
        $getSetScore = function($setIndex, $teamKey) use ($setScores) {
            // $setScores expected like [ ['set'=>1,'team1'=>25,'team2'=>20], ... ] OR assoc by index 0..4
            if (is_array($setScores)) {
                foreach ($setScores as $s) {
                    if ((isset($s['set']) && (int)$s['set'] === $setIndex) ||
                        (!isset($s['set']) && array_key_exists($setIndex-1, $setScores) && $setScores[$setIndex-1]))
                    {
                        // support both shapes
                        if (isset($s['set']) && (int)$s['set'] === $setIndex) {
                            return $teamKey === 'team1' ? ($s['team1'] ?? '') : ($s['team2'] ?? '');
                        } elseif (!isset($s['set']) && array_key_exists($setIndex-1, $setScores)) {
                            $item = $setScores[$setIndex-1];
                            return $teamKey === 'team1' ? ($item['team1'] ?? '') : ($item['team2'] ?? '');
                        }
                    }
                }
            }
            return '';
        };
    @endphp

    <div class="sheet">
        <div class="header">
            <div style="width:22%; font-size:12px;">
                <div><strong>{{ $game->bracket->tournament->name ?? 'Tournament' }}</strong></div>
                <div class="muted">Game #{{ $game->id }}</div>
            </div>

            <div class="title">
                <h1>Volleyball Scoresheet</h1>
                <div class="muted">{{ $game->started_at ? $game->started_at->format('M d, Y H:i') : now()->format('M d, Y') }}</div>
            </div>

            <div style="width:22%; text-align:right; font-size:12px;">
                <div><strong>Place: </strong>{{ strtoupper($game->venue ?? '---') }}</div>
                <div class="muted">Referee: {{ $game->referee ?? '________' }}</div>
            </div>
        </div>

        <div class="meta">
            <div class="meta-box">
                <strong>Team A:</strong> {{ strtoupper($game->team1->team_name ?? 'Team A') }}<br>
                <strong>Coach:</strong> {{ $game->team1->coach_name ?? '________' }}
            </div>
            <div class="meta-box">
                <strong>Team B:</strong> {{ strtoupper($game->team2->team_name ?? 'Team B') }}<br>
                <strong>Coach:</strong> {{ $game->team2->coach_name ?? '________' }}
            </div>
            <div class="meta-box" style="flex:1;">
                <strong>Initial Server:</strong> {{ $liveData['initial_server'] ?? '____' }} &nbsp; | &nbsp;
                <strong>Match Type:</strong> Best of 5
                <div class="muted" style="margin-top:6px;">(Running scores auto-checked based on recorded rallies)</div>
            </div>
        </div>

        <div class="teams-row">
            <div class="team-panel">
                <div class="team-name">Team A - {{ strtoupper($game->team1->team_name ?? '') }}</div>

                <div style="display:flex; gap:8px;">
                    <div style="flex: 1;">
                        <div style="font-weight:700; font-size:11px; margin-bottom:6px;">Roster</div>
                        <div style="font-size:11px;">
                            @foreach($team1Players as $p)
                                <div>{{ $loop->iteration }}. {{ $p->name }} <span style="float:right">#{{ $p->number ?? '00' }}</span></div>
                            @endforeach
                        </div>
                    </div>
                    <div style="width:220px;">
                        <div style="font-weight:700; font-size:11px; margin-bottom:6px;">Stats / Notes</div>
                        <div style="height:90px; border:1px solid #000; padding:4px;"></div>
                    </div>
                </div>
            </div>

            <div class="team-panel">
                <div class="team-name">Team B - {{ strtoupper($game->team2->team_name ?? '') }}</div>

                <div style="display:flex; gap:8px;">
                    <div style="flex: 1;">
                        <div style="font-weight:700; font-size:11px; margin-bottom:6px;">Roster</div>
                        <div style="font-size:11px;">
                            @foreach($team2Players as $p)
                                <div>{{ $loop->iteration }}. {{ $p->name }} <span style="float:right">#{{ $p->number ?? '00' }}</span></div>
                            @endforeach
                        </div>
                    </div>
                    <div style="width:220px;">
                        <div style="font-weight:700; font-size:11px; margin-bottom:6px;">Stats / Notes</div>
                        <div style="height:90px; border:1px solid #000; padding:4px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SETS GRID --}}
        <div class="sets-grid">
            @for ($set = 1; $set <= 5; $set++)
                <div class="set-card">
                    <h4>Set {{ $set }}</h4>

                    <div class="set-score-row">
                        <div class="set-score">A: {{ $getSetScore($set, 'team1') !== '' ? $getSetScore($set, 'team1') : '—' }}</div>
                        <div class="set-score">B: {{ $getSetScore($set, 'team2') !== '' ? $getSetScore($set, 'team2') : '—' }}</div>
                    </div>

                    <div class="running-grid">
                        <div style="font-size:11px; margin-bottom:4px; font-weight:700; text-align:center;">Running Score (1—50)</div>

                        {{-- Numbers grid --}}
                        <div class="numbers-grid" aria-label="set-{{ $set }}-numbers">
                            @for ($n = 1; $n <= $maxNumberPerSet; $n++)
                                <div class="num-cell">
                                    {{ $n }}
                                </div>
                            @endfor
                        </div>

                        {{-- Checks for team A / B under the numbers grid --}}
                        <div class="checks-row">
                            <div class="checks-col" aria-label="set-{{ $set }}-A">
                                <div class="label">Team A Checks</div>
                                <div style="display:flex; flex-wrap:wrap; gap:3px; justify-content:center;">
                                    @for ($n = 1; $n <= $maxNumberPerSet; $n++)
                                        <div style="width:18px; height:16px; display:flex; align-items:center; justify-content:center; font-size:12px;">
                                            {!! ($teamAChecks[$set][$n] ?? false) ? '&#10003;' : '&nbsp;' !!}
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div class="checks-col" aria-label="set-{{ $set }}-B">
                                <div class="label">Team B Checks</div>
                                <div style="display:flex; flex-wrap:wrap; gap:3px; justify-content:center;">
                                    @for ($n = 1; $n <= $maxNumberPerSet; $n++)
                                        <div style="width:18px; height:16px; display:flex; align-items:center; justify-content:center; font-size:12px;">
                                            {!! ($teamBChecks[$set][$n] ?? false) ? '&#10003;' : '&nbsp;' !!}
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div> {{-- end checks-row --}}

                        {{-- timeouts / substitutions small boxes --}}
                        <div class="timeouts-subs" style="margin-top:6px;">
                            <div class="small-box">
                                <strong>Timeouts (A):</strong> {{ $liveData['team1_timeouts'] ?? '0' }} <br>
                                <strong>Substitutions (A):</strong> {{ $liveData['team1_substitutions'] ?? '0' }}
                            </div>
                            <div class="small-box">
                                <strong>Timeouts (B):</strong> {{ $liveData['team2_timeouts'] ?? '0' }} <br>
                                <strong>Substitutions (B):</strong> {{ $liveData['team2_substitutions'] ?? '0' }}
                            </div>
                        </div>

                    </div> {{-- end running-grid --}}
                </div> {{-- end set-card --}}
            @endfor
        </div> {{-- end sets-grid --}}

        <div class="bottom-row" style="margin-top:10px;">
            <div class="best-player" style="width: 48%;">
                <strong>Best Player / MVP</strong>
                @php
                    $best = null;
                    if (!empty($liveData['best_player_id'])) {
                        // try to find in players collection
                        $bestPlayer = $team1Players->firstWhere('id', $liveData['best_player_id']) ?? $team2Players->firstWhere('id', $liveData['best_player_id']);
                    } else {
                        $bestPlayer = null;
                    }
                @endphp

                @if (!empty($liveData['best_player_stats']) || !empty($bestPlayer))
                    <div style="margin-top:6px;">
                        <div><strong>Name:</strong> {{ $bestPlayer->name ?? ($liveData['best_player_stats']['name'] ?? '—') }}</div>
                        <div><strong>#:</strong> {{ $bestPlayer->number ?? ($liveData['best_player_stats']['number'] ?? '—') }}</div>
                        <div style="margin-top:6px;">
                            <strong>Kills:</strong> {{ $liveData['best_player_stats']['kills'] ?? '0' }}
                            &nbsp; <strong>Aces:</strong> {{ $liveData['best_player_stats']['aces'] ?? '0' }}
                            &nbsp; <strong>Blocks:</strong> {{ $liveData['best_player_stats']['blocks'] ?? '0' }}
                        </div>
                    </div>
                @else
                    <div style="margin-top:6px;">Name: _________________________</div>
                    <div>Stats: Kills ___  Aces ___  Blocks ___</div>
                @endif

                <div style="margin-top:8px;"><strong>Remarks:</strong></div>
                <div style="height:40px; border:1px solid #000; margin-top:4px;"></div>
            </div>

            <div class="signatures" style="width: 48%;">
                <div class="sig"><strong>Scorekeeper:</strong> ____________________________</div>
                <div class="sig"><strong>Referee:</strong> ____________________________</div>
                <div class="sig"><strong>Coach A:</strong> ____________________________ &nbsp; <strong>Coach B:</strong> ____________________________</div>
                <div style="margin-top:6px"><strong>Final Sets:</strong> A <u>{{ $liveData['team1_score'] ?? ($game->team1_score ?? 0) }}</u> &nbsp; - &nbsp; B <u>{{ $liveData['team2_score'] ?? ($game->team2_score ?? 0) }}</u></div>
            </div>
        </div>

    </div> {{-- end sheet --}}
</body>
</html>
