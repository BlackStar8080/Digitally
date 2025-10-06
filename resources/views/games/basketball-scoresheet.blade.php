<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PADAYON CUP - Basketball Scoresheet</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  @page { size: 8.5in 13in portrait; margin: 0.3in; }
  body { 
    font-family: Arial, sans-serif; 
    font-size: 9px; 
    background: #f0f0f0;
    padding: 10px;
  }
  .sheet { 
    width: 8.5in; 
    min-height: 13in; 
    background: white; 
    padding: 0.3in;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    margin: 0 auto;
  }
  .header { 
    text-align: center; 
    background: #FFD700;
    padding: 8px;
    border: 2px solid #000;
    margin-bottom: 6px;
    position: relative;
  }
  .header h1 { 
    font-size: 22px; 
    font-weight: bold; 
    letter-spacing: 2px;
    color: #8B4513;
  }
  .logo { 
    position: absolute;
    width: 45px;
    height: 45px;
    top: 50%;
    transform: translateY(-50%);
    border: 2px solid #000;
    background: white;
    border-radius: 50%;
  }
  .logo-left { left: 15px; }
  .logo-right { right: 15px; }
  .meta-row { 
    display: flex; 
    gap: 8px; 
    margin-bottom: 3px;
    font-size: 8px;
    align-items: center;
    flex-wrap: wrap;
  }
  .meta-item { display: flex; align-items: center; gap: 3px; }
  .meta-item strong { min-width: 60px; font-size: 8px; }
  .meta-value { 
    border-bottom: 1px solid #000; 
    min-width: 70px; 
    padding: 1px 2px;
    font-size: 8px;
  }
  .main-layout { 
    display: grid;
    grid-template-columns: 2.8in 1fr;
    gap: 6px;
    margin-bottom: 6px;
  }
  .teams-section { 
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .box { 
    border: 2px solid #000; 
    padding: 4px; 
    background: white;
  }
  .box-header { 
    font-weight: bold; 
    font-size: 9px; 
    margin-bottom: 3px;
    padding-bottom: 2px;
    border-bottom: 1px solid #000;
  }
  table { 
    width: 100%; 
    border-collapse: collapse; 
    font-size: 7px;
  }
  td, th { 
    border: 1px solid #000; 
    padding: 1px;
    text-align: center;
  }
  .players-table td { 
    height: 13px;
    padding: 1px 2px;
  }
  .players-table td:nth-child(2) { 
    text-align: left;
    font-size: 7px;
  }
  .timeout-grid, .foul-grid {
    display: inline-grid;
    grid-template-columns: repeat(2, 14px);
    gap: 2px;
    vertical-align: middle;
  }
  .timeout-box, .foul-box {
    width: 14px;
    height: 12px;
    border: 1px solid #000;
    display: inline-block;
    text-align: center;
    line-height: 12px;
    font-size: 7px;
  }
  .running-score-table {
    font-size: 6px;
  }
  .running-score-table td {
    padding: 0px;
    height: 10px;
  }
  .period-labels { 
    display: flex; 
    gap: 5px; 
    align-items: center;
    margin-bottom: 3px;
    font-size: 7px;
  }
  .bottom-section { 
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
  }
  .print-btn {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 10px 20px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    z-index: 1000;
  }
  .print-btn:hover {
    background: #45a049;
  }
  @media print {
    body { background: white; padding: 0; }
    .sheet { box-shadow: none; margin: 0; }
    .print-btn { display: none; }
  }
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨️ Print Scoresheet</button>

<div class="sheet">
  <!-- Header -->
  <div class="header">
    <div class="logo logo-left"></div>
    <h1>PADAYON CUP</h1>
    <div class="logo logo-right"></div>
  </div>

  <!-- Meta Information -->
  <div class="meta-row">
    <div class="meta-item">
      <strong>Team A</strong>
      <span class="meta-value">{{ $game->team1->team_name }}</span>
    </div>
    <div class="meta-item">
      <strong>Team B</strong>
      <span class="meta-value">{{ $game->team2->team_name }}</span>
    </div>
    <div class="meta-item">
      <strong>Competition</strong>
      <span class="meta-value">{{ $game->bracket->tournament->name ?? '26 ABOVE' }}</span>
    </div>
  </div>
  
  <div class="meta-row">
    <div class="meta-item">
      <strong>Date</strong>
      <span class="meta-value">{{ $game->started_at ? $game->started_at->format('m/d/Y') : date('m/d/Y') }}</span>
    </div>
    <div class="meta-item">
      <strong>Time</strong>
      <span class="meta-value">{{ $game->started_at ? $game->started_at->format('H:i') : '' }}</span>
    </div>
    <div class="meta-item">
      <strong>Game No.</strong>
      <span class="meta-value">{{ $game->id }}</span>
    </div>
    <div class="meta-item">
      <strong>Place</strong>
      <span class="meta-value">CASINGLOT COURT</span>
    </div>
  </div>

  <div class="meta-row">
    <div class="meta-item">
      <strong>Referee</strong>
      <span class="meta-value">{{ $game->referee ?? '' }}</span>
    </div>
    <div class="meta-item">
      <strong>Umpire 1</strong>
      <span class="meta-value">{{ $game->assistant_referee_1 ?? '' }}</span>
    </div>
    <div class="meta-item">
      <strong>Umpire 2</strong>
      <span class="meta-value">{{ $game->assistant_referee_2 ?? '' }}</span>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-layout">
    <!-- Left: Teams -->
    <div class="teams-section">
      <!-- Team A -->
      <div class="box">
        <div class="box-header">Team A: {{ strtoupper($game->team1->team_name) }}</div>
        
        <div class="period-labels">
          <span><strong>Time-outs</strong></span>
          <div class="timeout-grid">
            @for($i = 0; $i < 2; $i++)
              <div class="timeout-box">{{ isset($liveData['team1_timeouts']) && $liveData['team1_timeouts'] > $i ? '✓' : '' }}</div>
            @endfor
          </div>
          <span style="margin-left: 8px;"><strong>Team fouls</strong></span>
          <span style="font-size: 6px;">Period ①</span>
          <div class="foul-grid">
            @for($i = 0; $i < 4; $i++)
              <div class="foul-box"></div>
            @endfor
          </div>
          <span style="font-size: 6px;">③</span>
          <div class="foul-grid">
            @for($i = 0; $i < 4; $i++)
              <div class="foul-box"></div>
            @endfor
          </div>
        </div>

        <table class="players-table">
          <tr style="background: #f0f0f0;">
            <th style="width:18px; font-size: 6px;">Line</th>
            <th style="font-size: 6px;">Players</th>
            <th style="width:20px; font-size: 6px;">No.</th>
            <th style="width:10px; font-size: 6px;">①</th>
            <th style="width:10px; font-size: 6px;">②</th>
            <th style="width:10px; font-size: 6px;">③</th>
            <th style="width:10px; font-size: 6px;">④</th>
          </tr>
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
          @foreach($team1Players as $index => $player)
          <tr>
            <td style="font-size: 6px;">{{ $index + 1 }}</td>
            <td style="text-align: left;">{{ $player->name }}</td>
            <td><strong style="font-size: 7px;">{{ $player->number ?? '00' }}</strong></td>
            @php $fouls = $team1FoulCounts[$player->number] ?? 0; @endphp
            <td>{{ $fouls >= 1 ? '/' : '' }}</td>
            <td>{{ $fouls >= 2 ? '/' : '' }}</td>
            <td>{{ $fouls >= 3 ? '/' : '' }}</td>
            <td>{{ $fouls >= 4 ? '/' : '' }}</td>
          </tr>
          @endforeach
          @for($i = count($team1Players); $i < 15; $i++)
          <tr>
            <td style="font-size: 6px;">{{ $i + 1 }}</td>
            <td></td>
            <td></td>
            <td></td><td></td><td></td><td></td>
          </tr>
          @endfor
        </table>

        <div style="margin-top: 3px; font-size: 7px;">
          <strong>Coach:</strong> {{ $game->team1->coach_name ?? '' }}
        </div>
      </div>

      <!-- Team B -->
      <div class="box">
        <div class="box-header">Team B: {{ strtoupper($game->team2->team_name) }}</div>
        
        <div class="period-labels">
          <span><strong>Time-outs</strong></span>
          <div class="timeout-grid">
            @for($i = 0; $i < 2; $i++)
              <div class="timeout-box">{{ isset($liveData['team2_timeouts']) && $liveData['team2_timeouts'] > $i ? '✓' : '' }}</div>
            @endfor
          </div>
          <span style="margin-left: 8px;"><strong>Team fouls</strong></span>
          <span style="font-size: 6px;">Period ①</span>
          <div class="foul-grid">
            @for($i = 0; $i < 4; $i++)
              <div class="foul-box"></div>
            @endfor
          </div>
          <span style="font-size: 6px;">③</span>
          <div class="foul-grid">
            @for($i = 0; $i < 4; $i++)
              <div class="foul-box"></div>
            @endfor
          </div>
        </div>

        <table class="players-table">
          <tr style="background: #f0f0f0;">
            <th style="width:18px; font-size: 6px;">Line</th>
            <th style="font-size: 6px;">Players</th>
            <th style="width:20px; font-size: 6px;">No.</th>
            <th style="width:10px; font-size: 6px;">①</th>
            <th style="width:10px; font-size: 6px;">②</th>
            <th style="width:10px; font-size: 6px;">③</th>
            <th style="width:10px; font-size: 6px;">④</th>
          </tr>
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
          @foreach($team2Players as $index => $player)
          <tr>
            <td style="font-size: 6px;">{{ $index + 1 }}</td>
            <td style="text-align: left;">{{ $player->name }}</td>
            <td><strong style="font-size: 7px;">{{ $player->number ?? '00' }}</strong></td>
            @php $fouls = $team2FoulCounts[$player->number] ?? 0; @endphp
            <td>{{ $fouls >= 1 ? '/' : '' }}</td>
            <td>{{ $fouls >= 2 ? '/' : '' }}</td>
            <td>{{ $fouls >= 3 ? '/' : '' }}</td>
            <td>{{ $fouls >= 4 ? '/' : '' }}</td>
          </tr>
          @endforeach
          @for($i = count($team2Players); $i < 15; $i++)
          <tr>
            <td style="font-size: 6px;">{{ $i + 1 }}</td>
            <td></td>
            <td></td>
            <td></td><td></td><td></td><td></td>
          </tr>
          @endfor
        </table>

        <div style="margin-top: 3px; font-size: 7px;">
          <strong>Coach:</strong> {{ $game->team2->coach_name ?? '' }}
        </div>
      </div>
    </div>

    <!-- Right: Running Score -->
    <div class="box" style="height: 100%;">
      <div class="box-header" style="text-align: center;">RUNNING SCORE</div>
      <table class="running-score-table">
        <tr style="background: #f0f0f0; font-weight: bold; font-size: 6px;">
          @for($col = 0; $col < 4; $col++)
            <th style="width:12px;"></th>
            <th style="width:12px;">A</th>
            <th style="width:12px;"></th>
            <th style="width:12px;">B</th>
          @endfor
        </tr>
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
        
        @for($row = 0; $row < 40; $row++)
        <tr>
          @for($col = 0; $col < 4; $col++)
            @php
                $num = $row + 1 + ($col * 40);
            @endphp
            <td style="font-size: 6px;">{{ $num }}</td>
            <td>{{ $teamAChecks[$num] ?? false ? '✓' : '' }}</td>
            <td style="font-size: 6px;">{{ $num }}</td>
            <td>{{ $teamBChecks[$num] ?? false ? '✓' : '' }}</td>
          @endfor
        </tr>
        @endfor
      </table>
    </div>
  </div>

  <!-- Bottom Section -->
  <div class="bottom-section">
    <!-- Scores Summary -->
    <div class="box">
      <div class="box-header">Scores</div>
      @php
          $periodScores = $liveData['period_scores'] ?? ['team1' => [0,0,0,0], 'team2' => [0,0,0,0]];
      @endphp
      <table style="font-size: 7px; margin-bottom: 4px;">
        <tr>
          <td style="text-align: left; padding: 2px;"><strong>Period ①</strong></td>
          <td style="padding: 2px;"><strong>A</strong> {{ $periodScores['team1'][0] ?? 0 }}</td>
          <td style="padding: 2px;"><strong>B</strong> {{ $periodScores['team2'][0] ?? 0 }}</td>
        </tr>
        <tr>
          <td style="text-align: left; padding: 2px;"><strong>Period ②</strong></td>
          <td style="padding: 2px;"><strong>A</strong> {{ $periodScores['team1'][1] ?? 0 }}</td>
          <td style="padding: 2px;"><strong>B</strong> {{ $periodScores['team2'][1] ?? 0 }}</td>
        </tr>
        <tr>
          <td style="text-align: left; padding: 2px;"><strong>Period ③</strong></td>
          <td style="padding: 2px;"><strong>A</strong> {{ $periodScores['team1'][2] ?? 0 }}</td>
          <td style="padding: 2px;"><strong>B</strong> {{ $periodScores['team2'][2] ?? 0 }}</td>
        </tr>
        <tr>
          <td style="text-align: left; padding: 2px;"><strong>Period ④</strong></td>
          <td style="padding: 2px;"><strong>A</strong> {{ $periodScores['team1'][3] ?? 0 }}</td>
          <td style="padding: 2px;"><strong>B</strong> {{ $periodScores['team2'][3] ?? 0 }}</td>
        </tr>
        <tr>
          <td style="text-align: left; padding: 2px;"><strong>Extra</strong></td>
          <td style="padding: 2px;">__</td>
          <td style="padding: 2px;">__</td>
        </tr>
        <tr style="background: #fffacd;">
          <td style="text-align: left; padding: 3px; font-weight: bold;"><strong>Final</strong></td>
          <td style="padding: 3px; font-weight: bold;">{{ $liveData['team1_score'] ?? 0 }}</td>
          <td style="padding: 3px; font-weight: bold;">{{ $liveData['team2_score'] ?? 0 }}</td>
        </tr>
      </table>

      <div style="padding: 2px 0; border-top: 1px solid #000; margin-bottom: 4px; font-size: 7px;">
        <strong>Winning team:</strong>
        @if(isset($liveData['team1_score']) && isset($liveData['team2_score']))
          {{ $liveData['team1_score'] > $liveData['team2_score'] ? $game->team1->team_name : ($liveData['team2_score'] > $liveData['team1_score'] ? $game->team2->team_name : 'TIE') }}
        @endif
      </div>

      <div style="padding-top: 4px; border-top: 1px solid #000;">
        <div style="font-weight: bold; margin-bottom: 3px; font-size: 7px;">BEST PLAYER: _______</div>
        <div style="font-size: 7px; line-height: 1.4;">
          <div><strong>Score:</strong> __ <strong>Assist:</strong> __</div>
          <div><strong>Rebound:</strong> __ <strong>Blocks:</strong> __</div>
          <div><strong>Steal:</strong> __</div>
        </div>
      </div>
    </div>

    <!-- Officials -->
    <div class="box">
      <div class="box-header">Officials</div>
      <div style="font-size: 7px; line-height: 1.8;">
        <div><strong>Scorekeeper:</strong> _____________</div>
        <div><strong>Asst. Scorekeeper:</strong> _____________</div>
        <div><strong>Timekeeper:</strong> _____________</div>
        <div><strong>24" operator:</strong> _____________</div>
        <div style="margin-top: 8px; padding-top: 6px; border-top: 1px dotted #999;">
          <strong>Referee:</strong> {{ $game->referee ?? '___________' }}
        </div>
        <div><strong>Umpire 1:</strong> {{ $game->assistant_referee_1 ?? '___________' }}</div>
        <div><strong>Umpire 2:</strong> {{ $game->assistant_referee_2 ?? '___________' }}</div>
      </div>
    </div>
  </div>
</div>

</body>
</html>