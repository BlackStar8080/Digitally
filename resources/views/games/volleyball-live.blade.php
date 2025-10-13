@extends('layouts.app')

@section('title', 'Live Volleyball - ' . $game->team1->team_name . ' vs ' . $game->team2->team_name)

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Live Volleyball - {{ $game->team1->team_name }} vs {{ $game->team2->team_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #1a1a1a;
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Scoreboard */
        .scoreboard {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            padding: 12px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .team-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .team-section.left {
            justify-content: flex-start;
        }

        .team-section.right {
            justify-content: flex-end;
            flex-direction: row-reverse;
        }

        .team-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .team-name {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .team-stats {
            display: flex;
            gap: 12px;
            font-size: 12px;
            color: #ccc;
        }

        .score-display {
            font-size: 48px;
            font-weight: bold;
            color: #4CAF50;
            min-width: 80px;
            text-align: center;
            font-family: 'Courier New', monospace;
        }

        .serving-indicator {
            width: 20px;
            height: 20px;
            background: #FF9800;
            border-radius: 50%;
            animation: servePulse 2s infinite;
        }

        @keyframes servePulse {
            0%, 100% { opacity: 1; box-shadow: 0 0 8px rgba(255, 152, 0, 0.6); }
            50% { opacity: 0.6; box-shadow: 0 0 15px rgba(255, 152, 0, 0.8); }
        }

        .center-panel {
            text-align: center;
            flex: 1;
        }

        .set-display {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .sets-won {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            margin-top: 8px;
        }

        .sets-score {
            font-size: 32px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
        }

        .timeout-timer {
            background: #FF9800;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 8px;
            display: none;
            animation: pulse 1s infinite;
        }

        .timeout-timer.active {
            display: inline-block;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Main Layout */
        .container {
            display: grid;
            grid-template-columns: 280px 1fr 280px;
            gap: 1px;
            flex: 1;
            overflow: hidden;
            min-height: 0;
        }

        /* Roster Sections */
        .roster-section {
            background: #2d2d2d;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .roster-header {
            padding: 16px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .roster-header.team-a {
            background: #c33;
        }

        .roster-header.team-b {
            background: #339;
        }

        .players-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 16px;
            overflow-y: auto;
        }

        .player-card {
            background: #3d3d3d;
            border-radius: 10px;
            padding: 16px 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            min-height: 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .player-card:hover {
            background: #4d4d4d;
            transform: translateY(-2px);
        }

        .player-card.team-a {
            border-left: 4px solid #c33;
        }

        .player-card.team-b {
            border-left: 4px solid #339;
        }

        .player-card.selecting {
            animation: selectPulse 1s infinite;
        }

        @keyframes selectPulse {
            0%, 100% { box-shadow: 0 0 0 2px #4CAF50; }
            50% { box-shadow: 0 0 0 4px #4CAF50; }
        }

        .player-number {
            font-size: 20px;
            font-weight: bold;
        }

        .player-position {
            font-size: 12px;
            color: #aaa;
            margin-top: 4px;
        }

        /* Event Log */
        .log-section {
            background: #1e1e1e;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .log-header {
            padding: 15px 20px;
            background: #333;
            border-bottom: 1px solid #444;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            flex-shrink: 0;
        }

        .log-content {
            flex: 1;
            overflow-y: auto;
            padding: 0;
        }

        .log-entry {
            display: grid;
            grid-template-columns: 40px 50px 60px 1fr 60px 80px 40px;
            align-items: center;
            padding: 14px 15px;
            border-bottom: 1px solid #333;
            font-size: 12px;
            transition: background 0.2s;
            gap: 8px;
        }

        .log-entry:hover {
            background: #2a2a2a;
        }

        .log-entry.team-a {
            border-left: 3px solid #c33;
        }

        .log-entry.team-b {
            border-left: 3px solid #339;
        }

        .entry-number {
            color: #888;
            font-size: 11px;
        }

        .entry-team {
            font-weight: bold;
            font-size: 14px;
        }

        .entry-player {
            font-weight: 600;
        }

        .entry-action {
            color: #ddd;
        }

        .entry-set {
            color: #888;
            font-size: 11px;
        }

        .entry-score {
            color: #888;
            font-size: 11px;
            font-family: 'Courier New', monospace;
        }

        .entry-check {
            color: #4CAF50;
            font-size: 16px;
        }

        /* Action Buttons */
        .actions-section {
            background: #2d2d2d;
            padding: 16px;
            border-top: 1px solid #444;
            flex-shrink: 0;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 12px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .action-btn {
            padding: 16px 12px;
            font-size: 13px;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .action-btn.selected {
            outline: 3px solid #4CAF50;
            outline-offset: 2px;
        }

        .btn-kill {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        }

        .btn-ace {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        }

        .btn-block {
            background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);
        }

        .btn-dig {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
        }

        .btn-assist {
            background: linear-gradient(135deg, #3F51B5 0%, #303F9F 100%);
        }

        .btn-error {
            background: linear-gradient(135deg, #F44336 0%, #D32F2F 100%);
        }

        .btn-timeout {
            background: linear-gradient(135deg, #607D8B 0%, #455A64 100%);
        }

        .btn-undo {
            background: linear-gradient(135deg, #666 0%, #555 100%);
        }

        /* Modals */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: #2d2d2d;
            border-radius: 16px;
            padding: 40px;
            max-width: 600px;
            width: 90%;
            text-align: center;
        }

        .modal-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 24px;
        }

        .modal-subtitle {
            font-size: 18px;
            color: #aaa;
            margin-bottom: 32px;
        }

        .modal-score {
            display: flex;
            justify-content: space-around;
            margin: 32px 0;
        }

        .modal-team {
            text-align: center;
        }

        .modal-team-name {
            font-size: 14px;
            color: #888;
            margin-bottom: 8px;
        }

        .modal-team-score {
            font-size: 48px;
            font-weight: bold;
        }

        .modal-btn {
            width: 100%;
            padding: 16px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            margin-top: 12px;
        }

        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .modal-btn-secondary {
            background: #666;
            color: white;
        }

        .modal-btn-warning {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
        }

        /* Team Selection Modal */
        .team-select-btn {
            width: 100%;
            padding: 24px;
            font-size: 20px;
            font-weight: bold;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            color: white;
            margin-bottom: 16px;
        }

        .team-select-btn.team-a {
            background: linear-gradient(135deg, #c33 0%, #a22 100%);
        }

        .team-select-btn.team-b {
            background: linear-gradient(135deg, #339 0%, #227 100%);
        }

        .team-select-btn:hover {
            transform: scale(1.02);
        }

        /* Instruction Banner */
        .instruction-banner {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            background: #4CAF50;
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 14px;
            display: none;
            z-index: 5000;
            animation: fadeIn 0.3s;
        }

        .instruction-banner.show {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(-50%) translateY(10px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }

        /* Set Score Display */
        .set-scores {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 8px;
        }

        .set-score-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-family: 'Courier New', monospace;
        }

        .set-score-box.won-a {
            background: rgba(204, 51, 51, 0.3);
        }

        .set-score-box.won-b {
            background: rgba(51, 51, 153, 0.3);
        }
    </style>
</head>
<body>
    <!-- Instruction Banner -->
    <div class="instruction-banner" id="instructionBanner"></div>

    <!-- Scoreboard -->
    <div class="scoreboard">
        <!-- Team A -->
        <div class="team-section left">
            <div class="team-info">
                <div class="team-name" id="teamAName">{{ strtoupper($game->team1->team_name) }}</div>
                <div class="team-stats">
                    <span>T.O: <span id="timeoutsA">0</span>/2</span>
                    <span>SUB: <span id="substitutionsA">0</span>/6</span>
                </div>
            </div>
            <div class="score-display" id="scoreA">00</div>
            <div class="serving-indicator" id="servingA" style="display: none;"></div>
        </div>

        <!-- Center Panel -->
        <div class="center-panel">
            <div class="set-display">SET <span id="currentSet">1</span></div>
            <div class="sets-won">
                <span class="sets-score" id="setsA">0</span>
                <span style="color: #666;">-</span>
                <span class="sets-score" id="setsB">0</span>
            </div>
            <div class="set-scores" id="setScoresDisplay"></div>
            <div class="timeout-timer" id="timeoutTimer">TIMEOUT: <span id="timeoutTime">30</span>s</div>
        </div>

        <!-- Team B -->
        <div class="team-section right">
            <div class="serving-indicator" id="servingB" style="display: none;"></div>
            <div class="score-display" id="scoreB">00</div>
            <div class="team-info">
                <div class="team-name" id="teamBName">{{ strtoupper($game->team2->team_name) }}</div>
                <div class="team-stats">
                    <span>T.O: <span id="timeoutsB">0</span>/2</span>
                    <span>SUB: <span id="substitutionsB">0</span>/6</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Team A Roster -->
        <div class="roster-section">
            <div class="roster-header team-a">{{ strtoupper($game->team1->team_name) }}</div>
            <div class="players-grid" id="playersA"></div>
        </div>

        <!-- Event Log -->
        <div class="log-section">
            <div class="log-header">GAME EVENTS</div>
            <div class="log-content" id="logContent"></div>
        </div>

        <!-- Team B Roster -->
        <div class="roster-section">
            <div class="roster-header team-b">{{ strtoupper($game->team2->team_name) }}</div>
            <div class="players-grid" id="playersB"></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="actions-section">
        <div class="actions-grid">
            <button class="action-btn btn-kill" data-action="kill">Kill</button>
            <button class="action-btn btn-ace" data-action="ace">Ace</button>
            <button class="action-btn btn-block" data-action="block">Block</button>
            <button class="action-btn btn-dig" data-action="dig">Dig</button>
            <button class="action-btn btn-assist" data-action="assist">Assist</button>
            <button class="action-btn btn-error" data-action="error">Error</button>
            <button class="action-btn btn-timeout" id="timeoutBtn">Timeout</button>
            <button class="action-btn btn-undo" id="undoBtn">↶ Undo</button>
        </div>
    </div>

    <!-- Team Selection Modal -->
    <div class="modal" id="teamSelectModal">
        <div class="modal-content">
            <h2 class="modal-title">Select Team</h2>
            <p class="modal-subtitle" id="teamSelectPrompt">Choose which team</p>
            <button class="team-select-btn team-a" data-team="A" id="selectTeamA">{{ strtoupper($game->team1->team_name) }}</button>
            <button class="team-select-btn team-b" data-team="B" id="selectTeamB">{{ strtoupper($game->team2->team_name) }}</button>
            <button class="modal-btn modal-btn-secondary" onclick="closeTeamSelectModal()">Cancel</button>
        </div>
    </div>

    <!-- Set End Modal -->
    <div class="modal" id="setEndModal">
        <div class="modal-content" style="border: 4px solid #4CAF50;">
            <h2 class="modal-title" style="color: #4CAF50;">SET <span id="endedSetNumber">1</span> ENDED</h2>
            <div class="modal-score">
                <div class="modal-team">
                    <div class="modal-team-name" id="setEndTeamA"></div>
                    <div class="modal-team-score" id="setEndScoreA">00</div>
                </div>
                <div class="modal-team">
                    <div class="modal-team-name" id="setEndTeamB"></div>
                    <div class="modal-team-score" id="setEndScoreB">00</div>
                </div>
            </div>
            <div style="margin: 20px 0;">
                <div style="color: #888; margin-bottom: 8px;">SETS WON</div>
                <div style="font-size: 32px; font-weight: bold;"><span id="modalSetsA">0</span> - <span id="modalSetsB">0</span></div>
            </div>
            <button class="modal-btn modal-btn-primary" onclick="startNextSet()">START SET <span id="nextSetNumber">2</span></button>
        </div>
    </div>

    <!-- Game End Modal -->
    <div class="modal" id="gameEndModal">
        <div class="modal-content" style="border: 4px solid #FF9800;">
            <h2 class="modal-title" style="color: #FF9800;">GAME ENDED</h2>
            <p class="modal-subtitle" style="font-size: 24px; color: white;" id="winnerText"></p>
            <div style="font-size: 40px; font-weight: bold; margin: 20px 0;">
                <span id="finalSetsA">0</span> - <span id="finalSetsB">0</span>
            </div>
            <div style="margin: 20px 0; text-align: left; max-height: 200px; overflow-y: auto;">
                <div style="color: #888; margin-bottom: 12px; text-align: center;">SET SCORES</div>
                <div id="finalSetScores"></div>
            </div>
            <button class="modal-btn modal-btn-warning" onclick="saveGameResults()">SAVE GAME RESULTS</button>
        </div>
    </div>

    <script>
        // Game data from Laravel
        const gameData = {
            id: {{ $game->id }},
            team1: {
                name: '{{ $game->team1->team_name }}',
                players: @json($team1Players->values())
            },
            team2: {
                name: '{{ $game->team2->team_name }}',
                players: @json($team2Players->values())
            }
        };

        // Game state
        let scoreA = 0, scoreB = 0;
        let currentSet = 1;
        let setsA = 0, setsB = 0;
        let serving = 'A'; // Team currently serving
        let timeoutsA = 0, timeoutsB = 0;
        let substitutionsA = 0, substitutionsB = 0;
        let events = [];
        let eventCounter = 1;
        
        // Set scores tracking
        let setScores = {
            A: [0, 0, 0, 0, 0],
            B: [0, 0, 0, 0, 0]
        };

        // UI state
        let selectedAction = null;
        let selectingPlayer = false;
        let selectingTeam = false;
        let teamSelectCallback = null;

        // Timeout timer
        let timeoutActive = false;
        let timeoutTime = 30;
        let timeoutInterval = null;

        // Initialize
        function init() {
            renderPlayers();
            updateScoreboard();
            updateServingIndicator();
            setupEventListeners();
        }

        // Render players
        function renderPlayers() {
            const playersAGrid = document.getElementById('playersA');
            const playersBGrid = document.getElementById('playersB');

            playersAGrid.innerHTML = '';
            playersBGrid.innerHTML = '';

            // Get active players (first 6 for volleyball)
            const activePlayersA = gameData.team1.players.slice(0, 6);
            const activePlayersB = gameData.team2.players.slice(0, 6);

            activePlayersA.forEach(player => {
                const card = createPlayerCard(player, 'A');
                playersAGrid.appendChild(card);
            });

            activePlayersB.forEach(player => {
                const card = createPlayerCard(player, 'B');
                playersBGrid.appendChild(card);
            });
        }

        // Create player card
        function createPlayerCard(player, team) {
            const card = document.createElement('div');
            card.className = `player-card team-${team.toLowerCase()}`;
            card.dataset.team = team;
            card.dataset.number = player.number || '00';
            card.dataset.playerId = player.id;

            card.innerHTML = `
                <div class="player-number">${player.number || '00'}</div>
                <div class="player-position">${player.position || 'P'}</div>
            `;

            card.addEventListener('click', () => handlePlayerClick(team, player));

            return card;
        }

        // Setup event listeners
        function setupEventListeners() {
            // Action buttons
            document.querySelectorAll('.action-btn[data-action]').forEach(btn => {
                btn.addEventListener('click', () => handleActionClick(btn.dataset.action));
            });

            // Timeout button
            document.getElementById('timeoutBtn').addEventListener('click', handleTimeoutClick);

            // Undo button
            document.getElementById('undoBtn').addEventListener('click', handleUndo);

            // Team selection buttons
            document.getElementById('selectTeamA').addEventListener('click', () => handleTeamSelect('A'));
            document.getElementById('selectTeamB').addEventListener('click', () => handleTeamSelect('B'));
        }

        // Handle action click
        function handleActionClick(action) {
            // Deselect previous
            document.querySelectorAll('.action-btn').forEach(btn => btn.classList.remove('selected'));
            
            selectedAction = action;
            
            // Highlight button
            event.target.classList.add('selected');

            if (action === 'error') {
                // Errors need team selection first
                selectingTeam = true;
                teamSelectCallback = handleErrorTeamSelect;
                showTeamSelectModal('Which team committed the error?');
            } else {
                // Other actions need player selection
                selectingPlayer = true;
                document.querySelectorAll('.player-card').forEach(card => card.classList.add('selecting'));
                showInstruction(`Select player for ${action.toUpperCase()}`);
            }
        }

        // Handle player click
        function handlePlayerClick(team, player) {
            if (!selectingPlayer) return;

            const playerNumber = player.number || '00';
            
            if (selectedAction === 'kill' || selectedAction === 'ace' || selectedAction === 'block') {
                // Scoring actions
                handleScore(team, selectedAction, playerNumber);
            } else if (selectedAction === 'dig' || selectedAction === 'assist') {
                // Non-scoring actions
                logEvent(team, playerNumber, selectedAction.charAt(0).toUpperCase() + selectedAction.slice(1), 0);
            }

            // Reset selection
            resetSelection();
        }

        // Handle score
        function handleScore(team, action, playerNumber) {
            const actionLabel = action.charAt(0).toUpperCase() + action.slice(1);
            
            if (team === 'A') {
                scoreA++;
                updateScoreDisplay();
                logEvent('A', playerNumber, actionLabel, 1);
            } else {
                scoreB++;
                updateScoreDisplay();
                logEvent('B', playerNumber, actionLabel, 1);
            }

            // Switch serving team if receiving team scores
            if (team !== serving) {
                serving = team;
                updateServingIndicator();
                logEvent('GAME', 'SYSTEM', `Serve → Team ${team}`, 0);
            }

            // Check for set win
            checkSetWin();
        }

        // Check set win
        function checkSetWin() {
            const maxPoints = currentSet < 5 ? 25 : 15;
            const minLead = 2;

            if (scoreA >= maxPoints && scoreA - scoreB >= minLead) {
                handleSetWin('A');
            } else if (scoreB >= maxPoints && scoreB - scoreA >= minLead) {
                handleSetWin('B');
            }
        }

        // Handle set win
        function handleSetWin(winner) {
            // Save set scores
            setScores.A[currentSet - 1] = scoreA;
            setScores.B[currentSet - 1] = scoreB;

            // Update sets won
            if (winner === 'A') {
                setsA++;
            } else {
                setsB++;
            }

            updateScoreboard();
            updateSetScoresDisplay();

            logEvent('GAME', 'SYSTEM', `Set ${currentSet} Ended - Team ${winner} wins ${winner === 'A' ? scoreA : scoreB}-${winner === 'A' ? scoreB : scoreA}`, 0);

            // Check if match is over (first to 3 sets)
            if (setsA === 3 || setsB === 3) {
                setTimeout(() => showGameEndModal(), 1000);
            } else {
                setTimeout(() => showSetEndModal(), 1000);
            }
        }

        // Handle timeout click
        function handleTimeoutClick() {
            selectingTeam = true;
            teamSelectCallback = handleTimeoutTeamSelect;
            showTeamSelectModal('Which team is taking a timeout?');
        }

        // Handle timeout team selection
        function handleTimeoutTeamSelect(team) {
            if (team === 'A' && timeoutsA >= 2) {
                alert('Team A has no timeouts remaining');
                return;
            }
            if (team === 'B' && timeoutsB >= 2) {
                alert('Team B has no timeouts remaining');
                return;
            }

            if (team === 'A') {
                timeoutsA++;
            } else {
                timeoutsB++;
            }

            updateScoreboard();
            logEvent(team, 'TEAM', 'Timeout', 0);
            startTimeoutTimer();
        }

        // Handle error team selection
        function handleErrorTeamSelect(team) {
            // Error gives point to opponent
            const opponent = team === 'A' ? 'B' : 'A';
            
            if (opponent === 'A') {
                scoreA++;
            } else {
                scoreB++;
            }

            updateScoreDisplay();
            logEvent(team, 'TEAM', 'Error (Opponent scores)', 1);

            // Switch serving if needed
            if (opponent !== serving) {
                serving = opponent;
                updateServingIndicator();
                logEvent('GAME', 'SYSTEM', `Serve → Team ${opponent}`, 0);
            }

            checkSetWin();
        }

        // Start timeout timer
        function startTimeoutTimer() {
            timeoutActive = true;
            timeoutTime = 30;
            document.getElementById('timeoutTimer').classList.add('active');
            updateTimeoutDisplay();

            timeoutInterval = setInterval(() => {
                timeoutTime--;
                updateTimeoutDisplay();

                if (timeoutTime <= 0) {
                    endTimeout();
                }
            }, 1000);
        }

        // End timeout
        function endTimeout() {
            timeoutActive = false;
            clearInterval(timeoutInterval);
            document.getElementById('timeoutTimer').classList.remove('active');
            timeoutTime = 30;
        }

        // Update timeout display
        function updateTimeoutDisplay() {
            document.getElementById('timeoutTime').textContent = timeoutTime;
        }

        // Handle team select
        function handleTeamSelect(team) {
            closeTeamSelectModal();
            if (teamSelectCallback) {
                teamSelectCallback(team);
                teamSelectCallback = null;
            }
            selectingTeam = false;
        }

        // Show team select modal
        function showTeamSelectModal(prompt) {
            document.getElementById('teamSelectPrompt').textContent = prompt;
            document.getElementById('teamSelectModal').classList.add('show');
        }

        // Close team select modal
        function closeTeamSelectModal() {
            document.getElementById('teamSelectModal').classList.remove('show');
            selectingTeam = false;
            resetSelection();
        }

        // Show set end modal
        function showSetEndModal() {
            document.getElementById('endedSetNumber').textContent = currentSet;
            document.getElementById('setEndTeamA').textContent = gameData.team1.name;
            document.getElementById('setEndTeamB').textContent = gameData.team2.name;
            document.getElementById('setEndScoreA').textContent = scoreA.toString().padStart(2, '0');
            document.getElementById('setEndScoreB').textContent = scoreB.toString().padStart(2, '0');
            document.getElementById('modalSetsA').textContent = setsA;
            document.getElementById('modalSetsB').textContent = setsB;
            document.getElementById('nextSetNumber').textContent = currentSet + 1;
            document.getElementById('setEndModal').classList.add('show');
        }

        // Start next set
        function startNextSet() {
            document.getElementById('setEndModal').classList.remove('show');
            
            currentSet++;
            scoreA = 0;
            scoreB = 0;
            timeoutsA = 0;
            timeoutsB = 0;
            substitutionsA = 0;
            substitutionsB = 0;

            // Switch serving team
            serving = serving === 'A' ? 'B' : 'A';

            updateScoreboard();
            updateServingIndicator();
            updateSetScoresDisplay();

            logEvent('GAME', 'SYSTEM', `Set ${currentSet} Started`, 0);
        }

        // Show game end modal
        function showGameEndModal() {
            const winner = setsA > setsB ? gameData.team1.name : gameData.team2.name;
            document.getElementById('winnerText').textContent = `${winner.toUpperCase()} WINS!`;
            document.getElementById('finalSetsA').textContent = setsA;
            document.getElementById('finalSetsB').textContent = setsB;

            // Display all set scores
            let setScoresHtml = '';
            for (let i = 0; i < currentSet; i++) {
                const scoreA = setScores.A[i];
                const scoreB = setScores.B[i];
                const wonClass = scoreA > scoreB ? 'won-a' : 'won-b';
                setScoresHtml += `
                    <div style="display: flex; justify-content: space-between; padding: 10px; background: #3d3d3d; margin-bottom: 8px; border-radius: 6px;" class="${wonClass}">
                        <span>Set ${i + 1}:</span>
                        <span style="font-family: 'Courier New', monospace; font-weight: bold;">${scoreA} - ${scoreB}</span>
                    </div>
                `;
            }
            document.getElementById('finalSetScores').innerHTML = setScoresHtml;

            document.getElementById('gameEndModal').classList.add('show');
        }

        // Collect player statistics from game events
        function collectPlayerStats() {
            const playerStats = {};

            // Helper to get player by number and team
            function findPlayerByNumber(playerNumber, team) {
                const players = team === 'A' ? gameData.team1.players : gameData.team2.players;
                return players.find(p => (p.number || '00').toString() === playerNumber.toString());
            }

            // Initialize stats for all players
            [...gameData.team1.players].forEach(player => {
                const key = `A_${player.id}`;
                playerStats[key] = {
                    player_id: player.id,
                    team_id: player.team_id,
                    kills: 0,
                    aces: 0,
                    blocks: 0,
                    digs: 0,
                    assists: 0,
                    errors: 0,
                    service_errors: 0,
                    attack_attempts: 0,
                    block_assists: 0
                };
            });

            [...gameData.team2.players].forEach(player => {
                const key = `B_${player.id}`;
                playerStats[key] = {
                    player_id: player.id,
                    team_id: player.team_id,
                    kills: 0,
                    aces: 0,
                    blocks: 0,
                    digs: 0,
                    assists: 0,
                    errors: 0,
                    service_errors: 0,
                    attack_attempts: 0,
                    block_assists: 0
                };
            });

            // Process game events to calculate stats
            events.forEach(event => {
                // Skip system/team events
                if (event.player === 'TEAM' || event.player === 'SYSTEM') {
                    return;
                }

                // Find the player
                const player = findPlayerByNumber(event.player, event.team);
                if (!player) return;

                const key = `${event.team}_${player.id}`;
                if (!playerStats[key]) return;

                // Process different action types
                if (event.action === 'Kill') {
                    playerStats[key].kills++;
                    playerStats[key].attack_attempts++;
                } else if (event.action === 'Ace') {
                    playerStats[key].aces++;
                } else if (event.action === 'Block') {
                    playerStats[key].blocks++;
                } else if (event.action === 'Dig') {
                    playerStats[key].digs++;
                } else if (event.action === 'Assist') {
                    playerStats[key].assists++;
                } else if (event.action.includes('Error')) {
                    playerStats[key].errors++;
                    if (event.action.includes('Service')) {
                        playerStats[key].service_errors++;
                    }
                }
            });

            // Convert to array
            return Object.values(playerStats);
        }

        // Save game results
        function saveGameResults() {
            const playerStats = collectPlayerStats();
            
            const finalGameData = {
                game_id: gameData.id,
                team1_score: setsA,
                team2_score: setsB,
                set_scores: Object.keys(setScores.A).slice(0, currentSet).map((key, index) => ({
                    set: index + 1,
                    team1: setScores.A[index],
                    team2: setScores.B[index]
                })),
                winner_id: setsA > setsB ? 1 : 2,
                status: 'completed',
                completed_at: new Date().toISOString(),
                game_events: events,
                player_stats: playerStats
            };

            // Send to backend
            fetch(`/games/${gameData.id}/volleyball-complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(finalGameData)
            })
            .then(response => response.json())
            .then(data => {
                alert('Game saved successfully!');
                window.location.href = data.redirect_url || '/games';
            })
            .catch(error => {
                console.error('Error saving game:', error);
                alert('Error saving game. Please try again.');
            });
        }

        // Log event
        function logEvent(team, player, action, points) {
            const event = {
                id: eventCounter++,
                team,
                player,
                action,
                points,
                set: currentSet,
                score: `${scoreA}-${scoreB}`
            };

            events.unshift(event);
            renderLog();
        }

        // Render log
        function renderLog() {
            const logContent = document.getElementById('logContent');
            logContent.innerHTML = '';

            events.forEach(event => {
                const entry = document.createElement('div');
                entry.className = `log-entry team-${event.team.toLowerCase()}`;
                entry.innerHTML = `
                    <div class="entry-number">#${event.id}</div>
                    <div class="entry-team">${event.team}</div>
                    <div class="entry-player">${event.player}</div>
                    <div class="entry-action">${event.action}</div>
                    <div class="entry-set">S${event.set}</div>
                    <div class="entry-score">${event.score}</div>
                    <div class="entry-check">✓</div>
                `;
                logContent.appendChild(entry);
            });
        }

        // Handle undo
        function handleUndo() {
            if (events.length === 0) return;

            const lastEvent = events.shift();

            // Revert score
            if (lastEvent.points > 0) {
                if (lastEvent.team === 'A') {
                    scoreA = Math.max(0, scoreA - 1);
                } else if (lastEvent.team === 'B') {
                    scoreB = Math.max(0, scoreB - 1);
                } else if (lastEvent.action.includes('Error')) {
                    // Revert error (opponent scored)
                    const errorTeam = lastEvent.team;
                    const opponent = errorTeam === 'A' ? 'B' : 'A';
                    if (opponent === 'A') {
                        scoreA = Math.max(0, scoreA - 1);
                    } else {
                        scoreB = Math.max(0, scoreB - 1);
                    }
                }
                updateScoreDisplay();
            }

            // Revert timeouts
            if (lastEvent.action === 'Timeout') {
                if (lastEvent.team === 'A') {
                    timeoutsA = Math.max(0, timeoutsA - 1);
                } else {
                    timeoutsB = Math.max(0, timeoutsB - 1);
                }
            }

            // Revert substitutions
            if (lastEvent.action === 'Substitution') {
                if (lastEvent.team === 'A') {
                    substitutionsA = Math.max(0, substitutionsA - 1);
                } else {
                    substitutionsB = Math.max(0, substitutionsB - 1);
                }
            }

            updateScoreboard();
            renderLog();
        }

        // Update scoreboard
        function updateScoreboard() {
            document.getElementById('scoreA').textContent = scoreA.toString().padStart(2, '0');
            document.getElementById('scoreB').textContent = scoreB.toString().padStart(2, '0');
            document.getElementById('currentSet').textContent = currentSet;
            document.getElementById('setsA').textContent = setsA;
            document.getElementById('setsB').textContent = setsB;
            document.getElementById('timeoutsA').textContent = timeoutsA;
            document.getElementById('timeoutsB').textContent = timeoutsB;
            document.getElementById('substitutionsA').textContent = substitutionsA;
            document.getElementById('substitutionsB').textContent = substitutionsB;
        }

        // Update score display only
        function updateScoreDisplay() {
            document.getElementById('scoreA').textContent = scoreA.toString().padStart(2, '0');
            document.getElementById('scoreB').textContent = scoreB.toString().padStart(2, '0');
        }

        // Update serving indicator
        function updateServingIndicator() {
            const servingA = document.getElementById('servingA');
            const servingB = document.getElementById('servingB');

            if (serving === 'A') {
                servingA.style.display = 'block';
                servingB.style.display = 'none';
            } else {
                servingA.style.display = 'none';
                servingB.style.display = 'block';
            }
        }

        // Update set scores display
        function updateSetScoresDisplay() {
            const display = document.getElementById('setScoresDisplay');
            display.innerHTML = '';

            for (let i = 0; i < currentSet - 1; i++) {
                const scoreA = setScores.A[i];
                const scoreB = setScores.B[i];
                const wonClass = scoreA > scoreB ? 'won-a' : 'won-b';
                
                const box = document.createElement('div');
                box.className = `set-score-box ${wonClass}`;
                box.textContent = `${scoreA}-${scoreB}`;
                display.appendChild(box);
            }
        }

        // Show instruction
        function showInstruction(message) {
            const banner = document.getElementById('instructionBanner');
            banner.textContent = message;
            banner.classList.add('show');
        }

        // Hide instruction
        function hideInstruction() {
            const banner = document.getElementById('instructionBanner');
            banner.classList.remove('show');
        }

        // Reset selection
        function resetSelection() {
            selectedAction = null;
            selectingPlayer = false;
            document.querySelectorAll('.action-btn').forEach(btn => btn.classList.remove('selected'));
            document.querySelectorAll('.player-card').forEach(card => card.classList.remove('selecting'));
            hideInstruction();
        }

        // Initialize on load
        init();
    </script>
</body>
</html>
@endsection