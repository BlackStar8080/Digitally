<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use App\Models\Tallysheet;
use App\Models\PlayerGameStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tournament;
use App\Models\VolleyballPlayerStat;
use App\Models\VolleyballTallysheet;

class GameController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bracket_id' => 'required|exists:brackets,id',
            'team1_id' => 'required|exists:teams,id|different:team2_id',
            'team2_id' => 'required|exists:teams,id|different:team1_id',
        ]);

        Game::create($validated);

        return back()->with('success', 'Matchup added!');
    }

    public function prepare(Game $game)
    {
        $game->load([
            'team1.players', 
            'team2.players', 
        ]);

         $game->load([
        'team1.players', 
        'team2.players', 
    ]);

    // ✅ NEW: Auto-assign first user as scorer
    if (auth()->check()) {
        $assignmentController = new GameAssignmentController();
        $assignmentController->ensureScorer($game, auth()->user());
    }
        
        return view('games.prepare', compact('game'));
    }

    

    public function updateOfficials(Request $request, Game $game)
    {
        $validated = $request->validate([
            'referee' => 'required|string|max:255',
            'assistant_referee_1' => 'nullable|string|max:255',
            'assistant_referee_2' => 'nullable|string|max:255',
        ]);

        $game->update($validated);

        // Return JSON response for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Game officials updated successfully!',
                'data' => [
                    'referee' => $game->referee,
                    'assistant_referee_1' => $game->assistant_referee_1,
                    'assistant_referee_2' => $game->assistant_referee_2,
                ]
            ]);
        }

        return back()->with('success', 'Game officials updated!');
    }

   public function basketballScoresheet(Game $game, Request $request)
{
    // Get live data if passed from the game interface
    $liveData = null;
    if ($request->has('live_data')) {
        $liveData = json_decode(urldecode($request->get('live_data')), true);
    } else {
        // ✅ Load saved tallysheet if game is completed
        if ($game->status === 'completed' && $game->tallysheet) {
            $tallysheet = $game->tallysheet;
            $liveData = [
                'team1_score' => $tallysheet->team1_score,
                'team2_score' => $tallysheet->team2_score,
                'team1_fouls' => $tallysheet->team1_fouls,
                'team2_fouls' => $tallysheet->team2_fouls,
                'team1_timeouts' => $tallysheet->team1_timeouts,
                'team2_timeouts' => $tallysheet->team2_timeouts,
                'period_scores' => $tallysheet->period_scores,
                'events' => $tallysheet->game_events,

                            $mvpPlayer = $game->playerStats()
                ->where('is_mvp', true)
                ->with('player')
                ->first()
            ];
        }
    }

    $team1Data = json_decode($game->team1_selected_players, true) ?? [];
    $team2Data = json_decode($game->team2_selected_players, true) ?? [];

    $game->load(['team1.players', 'team2.players', 'bracket.tournament']);
    
    $team1RosterIds = $team1Data['roster'] ?? [];
    $team2RosterIds = $team2Data['roster'] ?? [];

    $team1Players = $game->team1->players->filter(function($player) use ($team1RosterIds) {
        return in_array($player->id, $team1RosterIds);
    });
    
    $team2Players = $game->team2->players->filter(function($player) use ($team2RosterIds) {
        return in_array($player->id, $team2RosterIds);
    });
    
    return view('games.basketball-scoresheet', compact(
        'game',
        'team1Players',
        'team2Players',
        'liveData',
        'mvpPlayer'
    ));
}



public function startLive(Request $request, Game $game)
{
    // Validate input
    $validated = $request->validate([
        'team1_roster' => 'required|json',
        'team2_roster' => 'required|json',
        'team1_starters' => 'required|json',
        'team2_starters' => 'required|json',
        'interface_mode' => 'required|in:all_in_one,separated',
    ]);

    try {
        // ✅ CRITICAL: Load relationships BEFORE checking sport type
        $game->load('bracket.tournament.sport');
        
        // Decode JSON arrays
        $team1Roster = json_decode($validated['team1_roster'], true);
        $team2Roster = json_decode($validated['team2_roster'], true);
        $team1Starters = json_decode($validated['team1_starters'], true);
        $team2Starters = json_decode($validated['team2_starters'], true);

        // Validate rosters
        if (empty($team1Roster) || empty($team2Roster)) {
            return back()->with('error', 'Please select rosters for both teams');
        }

        if (count($team1Starters) !== count($team2Starters)) {
            return back()->with('error', 'Both teams must have the same number of starters');
        }

        // Save to game
        $game->update([
            'status' => 'in_progress',
            'team1_selected_players' => json_encode([
                'roster' => $team1Roster,
                'starters' => $team1Starters,
            ]),
            'team2_selected_players' => json_encode([
                'roster' => $team2Roster,
                'starters' => $team2Starters,
            ]),
            'game_data' => [
                'interface_mode' => $validated['interface_mode'],
                'team1_fouls' => 0,
                'team2_fouls' => 0,
                'team1_timeouts' => 0,
                'team2_timeouts' => 0,
                'game_events' => [],
                'last_update' => now()->timestamp,
            ]
        ]);

        // ✅ IMPORTANT: Refresh the game after update
        $game->refresh();

        // ✅ DEBUG LOG (remove this after testing)
        \Log::info('🏐 Game sport check', [
            'game_id' => $game->id,
            'isVolleyball' => $game->isVolleyball(),
            'sport_name' => $game->bracket?->tournament?->sport?->sports_name ?? 'NO SPORT'
        ]);

        // ✅ CHECK SPORT TYPE AND REDIRECT
        if ($game->isVolleyball()) {
            \Log::info('✅ Redirecting to VOLLEYBALL interface');
            return redirect()->route('games.volleyball-live', ['game' => $game->id]);
        } else {
            \Log::info('✅ Redirecting to BASKETBALL interface');
            return redirect()->route('games.live', [
                'game' => $game->id,
                'role' => 'scorer'
            ]);
        }

    } catch (\Exception $e) {
        \Log::error('Error starting live game', [
            'game_id' => $game->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->with('error', 'Failed to start game: ' . $e->getMessage());
    }
}




public function live(Request $request, Game $game)
{
    // Ensure game is in progress
    if ($game->status !== 'in_progress') {
        return redirect()->back()->with('error', 'Game has not been started yet!');
    }

    // ✅ FIX: Check game mode FIRST - if all-in-one, no role restrictions
    $gameData = $game->game_data ?? [];
    $interfaceMode = $gameData['interface_mode'] ?? 'all_in_one';
    
    if ($interfaceMode === 'all_in_one') {
        // All-in-one mode: User has access to ALL actions
        $userRole = 'all_in_one';
        \Log::info("All-in-one mode detected - full access granted for game {$game->id}");
    } else {
        // Separated mode: Check role from URL or database
        $requestedRole = $request->query('role');
        
        if ($requestedRole && in_array($requestedRole, ['scorer', 'stat_keeper'])) {
            $userRole = $requestedRole;
            \Log::info("Separated mode - Using explicit role from URL: $userRole");
        } else {
            $userRole = 'viewer';
            if (auth()->check()) {
                $userRole = GameAssignmentController::getUserRole(auth()->user(), $game) ?? 'viewer';
                \Log::info("Separated mode - Got role from database: $userRole for user " . auth()->id());
            }
        }
    }

    \Log::info("Live method - Final userRole: $userRole for game {$game->id}");

    // Get stored player data
    $team1Data = json_decode($game->team1_selected_players, true) ?? [];
    $team2Data = json_decode($game->team2_selected_players, true) ?? [];

    // Load the actual player data
    $game->load(['team1.players', 'team2.players']);
    
    // Extract roster and starter IDs
    $team1RosterIds = $team1Data['roster'] ?? [];
    $team2RosterIds = $team2Data['roster'] ?? [];
    $team1StarterIds = $team1Data['starters'] ?? [];
    $team2StarterIds = $team2Data['starters'] ?? [];

    // Filter players based on roster selection
    $team1Players = $game->team1->players->filter(function($player) use ($team1RosterIds) {
        return in_array($player->id, $team1RosterIds);
    });
    
    $team2Players = $game->team2->players->filter(function($player) use ($team2RosterIds) {
        return in_array($player->id, $team2RosterIds);
    });

    // Convert IDs to strings for JavaScript comparison
    $team1Roster = array_map('strval', $team1RosterIds);
    $team2Roster = array_map('strval', $team2RosterIds);
    $team1Starters = array_map('strval', $team1StarterIds);
    $team2Starters = array_map('strval', $team2StarterIds);

    return view('games.live-scoresheet', compact(
        'game', 
        'team1Players', 
        'team2Players',
        'team1Roster',
        'team2Roster', 
        'team1Starters', 
        'team2Starters',
        'userRole'
    ));
}

    /**
 * Update game schedule
 */
public function updateSchedule(Request $request, Game $game)
{
    $validated = $request->validate([
        'scheduled_at' => 'required|date|after_or_equal:today',
        'venue' => 'nullable|string|max:255', // ✅ ADD THIS
    ]);

    try {
        $game->update([
            'scheduled_at' => $validated['scheduled_at'],
            'venue' => $validated['venue'] ?? null, // ✅ ADD THIS
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Game schedule and venue updated successfully!',
            'scheduled_at' => $game->scheduled_at->format('M j, Y g:i A'),
            'scheduled_at_iso' => $game->scheduled_at->toIso8601String(),
            'venue' => $game->venue, // ✅ ADD THIS
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update schedule: ' . $e->getMessage()
        ], 500);
    }
}

    // NEW TALLYSHEET METHODS
    public function tallysheet(Game $game, Request $request)
    {
        // Get live data if passed from the game interface
        $liveData = null;
        if ($request->has('live_data')) {
            $liveData = json_decode(urldecode($request->get('live_data')), true);
        }

        // Get stored player data (same as live method)
        $team1Data = json_decode($game->team1_selected_players, true) ?? [];
        $team2Data = json_decode($game->team2_selected_players, true) ?? [];

        // Load the actual player data
        $game->load(['team1.players', 'team2.players']);
        
        // Extract roster IDs
        $team1RosterIds = $team1Data['roster'] ?? [];
        $team2RosterIds = $team2Data['roster'] ?? [];

        // Filter players based on roster selection (only rostered players)
        $team1Players = $game->team1->players->filter(function($player) use ($team1RosterIds) {
            return in_array($player->id, $team1RosterIds);
        });
        
        $team2Players = $game->team2->players->filter(function($player) use ($team2RosterIds) {
            return in_array($player->id, $team2RosterIds);
        });
        
        // Use live data if available, otherwise get from database
        if ($liveData) {
            $gameStats = $this->processLiveGameData($liveData);
        } else {
            // For non-live viewing, get stored game events (if you have them)
            $gameStats = $this->getDefaultGameStats();
        }
        
        return view('games.tallysheet', compact(
            'game', 
            'team1Players', 
            'team2Players', 
            'gameStats'
        ));
    }

    /**
 * Save tallysheet data when completing a game
 */
/**
 * Save tallysheet data when completing a game
 */
private function saveTallysheet(Game $game, array $gameData)
{
    try {
        // Process running scores from game events
        $runningScores = $this->processRunningScores($gameData['game_events'] ?? []);
        
        // Process player fouls from game events
        $playerFouls = $this->processPlayerFouls($gameData['game_events'] ?? []);
        
        // Create or update tallysheet
        Tallysheet::updateOrCreate(
            ['game_id' => $game->id],
            [
                'user_id' => auth()->id(), // ✅ Save the logged-in user
                'team1_score' => $gameData['team1_score'],
                'team2_score' => $gameData['team2_score'],
                'team1_fouls' => $gameData['team1_fouls'] ?? 0,
                'team2_fouls' => $gameData['team2_fouls'] ?? 0,
                'team1_timeouts' => $gameData['team1_timeouts'] ?? 0,
                'team2_timeouts' => $gameData['team2_timeouts'] ?? 0,
                'period_scores' => $gameData['period_scores'] ?? null,
                'running_scores' => $runningScores,
                'game_events' => $gameData['game_events'] ?? [],
                'player_fouls' => $playerFouls,
            ]
        );

        \Log::info("Tallysheet saved for game {$game->id} by user " . auth()->id());
        
    } catch (\Exception $e) {
        \Log::error("Failed to save tallysheet for game {$game->id}", [
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}

/**
 * Process running scores from game events
 */
private function processRunningScores(array $events)
{
    $runningScores = [];
    $scoreA = 0;
    $scoreB = 0;
    
    // Process events in reverse order (they're stored newest first)
    $sortedEvents = array_reverse($events);
    
    foreach ($sortedEvents as $event) {
        if (isset($event['points']) && $event['points'] > 0) {
            if ($event['team'] === 'A') {
                $scoreA += $event['points'];
                if ($scoreA <= 160) { // Max score on tallysheet
                    $runningScores[] = [
                        'team' => 'A',
                        'score' => $scoreA,
                        'sequence' => count($runningScores) + 1
                    ];
                }
            } else {
                $scoreB += $event['points'];
                if ($scoreB <= 160) { // Max score on tallysheet
                    $runningScores[] = [
                        'team' => 'B',
                        'score' => $scoreB,
                        'sequence' => count($runningScores) + 1
                    ];
                }
            }
        }
    }
    
    return $runningScores;
}

/**
 * Process player fouls from game events
 */
private function processPlayerFouls(array $events)
{
    $playerFouls = [];
    
    foreach ($events as $event) {
        if (isset($event['action']) && str_contains($event['action'], 'Foul')) {
            if (isset($event['player']) && $event['player'] !== 'TEAM' && $event['player'] !== 'SYSTEM') {
                $playerKey = $event['team'] . '_' . $event['player'];
                $playerFouls[$playerKey] = ($playerFouls[$playerKey] ?? 0) + 1;
            }
        }
    }
    
    return $playerFouls;
}

    private function processLiveGameData($liveData)
    {
        $stats = [
            'team1_score' => $liveData['team1_score'] ?? 0,
            'team2_score' => $liveData['team2_score'] ?? 0,
            'team1_fouls' => $liveData['team1_fouls'] ?? 0,
            'team2_fouls' => $liveData['team2_fouls'] ?? 0,
            'team1_timeouts' => $liveData['team1_timeouts'] ?? 0,
            'team2_timeouts' => $liveData['team2_timeouts'] ?? 0,
            'current_quarter' => $liveData['current_quarter'] ?? 1,
            'game_time' => $liveData['game_time'] ?? '00:00',
            'running_scores' => [],
            'period_scores' => [
                1 => ['team1' => 0, 'team2' => 0],
                2 => ['team1' => 0, 'team2' => 0],
                3 => ['team1' => 0, 'team2' => 0],
                4 => ['team1' => 0, 'team2' => 0],
            ],
            'player_fouls' => []
        ];

        // Process live events to build running score and statistics
        if (isset($liveData['events']) && is_array($liveData['events'])) {
            $runningScoreA = 0;
            $runningScoreB = 0;
            
            // Process events in reverse order (since they're stored newest first)
            $events = array_reverse($liveData['events']);
            
            foreach ($events as $event) {
                // Process scoring events for running score
                if (isset($event['points']) && $event['points'] > 0) {
                    if ($event['team'] === 'A') {
                        $runningScoreA += $event['points'];
                        $stats['running_scores'][] = [
                            'team' => 'A',
                            'score' => $runningScoreA,
                            'sequence' => count($stats['running_scores']) + 1
                        ];
                    } else {
                        $runningScoreB += $event['points'];
                        $stats['running_scores'][] = [
                            'team' => 'B',
                            'score' => $runningScoreB,
                            'sequence' => count($stats['running_scores']) + 1
                        ];
                    }
                }

                // Process individual player fouls
                if (isset($event['action']) && str_contains($event['action'], 'Foul')) {
                    if (isset($event['player']) && $event['player'] !== 'TEAM' && $event['player'] !== 'SYSTEM') {
                        $playerKey = $event['team'] . '_' . $event['player'];
                        $stats['player_fouls'][$playerKey] = ($stats['player_fouls'][$playerKey] ?? 0) + 1;
                    }
                }
            }
        }

        return $stats;
    }

    private function getDefaultGameStats()
    {
        // Return empty stats for non-live viewing
        return [
            'team1_score' => 0,
            'team2_score' => 0,
            'team1_fouls' => 0,
            'team2_fouls' => 0,
            'team1_timeouts' => 0,
            'team2_timeouts' => 0,
            'current_quarter' => 1,
            'game_time' => '00:00',
            'running_scores' => [],
            'period_scores' => [
                1 => ['team1' => 0, 'team2' => 0],
                2 => ['team1' => 0, 'team2' => 0],
                3 => ['team1' => 0, 'team2' => 0],
                4 => ['team1' => 0, 'team2' => 0],
            ],
            'player_fouls' => []
        ];
    }

    /**
     * Complete a game and save final results from live scoresheet
     */
   public function completeGame(Request $request, Game $game)
{
    $game->load(['bracket.tournament', 'team1.players', 'team2.players']);
   
    try {
        $validated = $request->validate([
            'team1_score' => 'required|integer|min:0',
            'team2_score' => 'required|integer|min:0',
            'team1_fouls' => 'integer|min:0',
            'team2_fouls' => 'integer|min:0',
            'team1_timeouts' => 'integer|min:0',
            'team2_timeouts' => 'integer|min:0',
            'total_quarters' => 'integer|min:1',
            'game_events' => 'array',
            'period_scores' => 'array',
            'winner_id' => 'nullable|integer|in:1,2',
            'status' => 'string|in:completed',
            'completed_at' => 'string',
            'player_stats' => 'array',
        ]);

        DB::beginTransaction();

        // Determine winner
        $winnerId = null;
        if ($validated['team1_score'] > $validated['team2_score']) {
            $winnerId = $game->team1_id;
        } elseif ($validated['team2_score'] > $validated['team1_score']) {
            $winnerId = $game->team2_id;
        }

        // Update game
        $game->update([
            'team1_score' => $validated['team1_score'],
            'team2_score' => $validated['team2_score'],
            'winner_id' => $winnerId,
            'status' => 'completed',
            'completed_at' => now(),
            'game_data' => [
                'team1_fouls' => $validated['team1_fouls'] ?? 0,
                'team2_fouls' => $validated['team2_fouls'] ?? 0,
                'team1_timeouts' => $validated['team1_timeouts'] ?? 0,
                'team2_timeouts' => $validated['team2_timeouts'] ?? 0,
                'total_quarters' => $validated['total_quarters'] ?? 4,
                'game_events' => $validated['game_events'] ?? [],
                'period_scores' => $validated['period_scores'] ?? [],
                'completed_by_scoresheet' => true
            ]
        ]);

        // Save player statistics
        if (isset($validated['player_stats']) && is_array($validated['player_stats'])) {
            $this->savePlayerStats($game, $validated['player_stats']);
        }

        // ✅ NEW: Save tallysheet
        $this->saveTallysheet($game, $validated);

        // Advance bracket if needed
        if ($game->bracket_id && $winnerId) {
            $this->advanceBracket($game, $winnerId);
        }

        if ($game->bracket_id) {
            $this->updateBracketStatus($game->bracket_id);
        }

        \App\Events\GameScoreUpdated::dispatch(
            $game,
            'final',
            $validated['team1_score'],
            $validated['team2_score']
        );

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Game completed and tallysheet saved successfully',
            'game_id' => $game->id,
            'winner_id' => $winnerId,
            'redirect_url' => route('games.box-score', $game->id)
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("Failed to complete game {$game->id}", [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to complete game: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Save player statistics from game events
 */
private function savePlayerStats(Game $game, array $playerStatsData)
{
    $savedCount = 0;
    $errors = [];

    foreach ($playerStatsData as $index => $playerData) {
        try {
            if (!isset($playerData['player_id']) || !isset($playerData['team_id'])) {
                $errors[] = "Player data at index {$index} missing required fields";
                continue;
            }

            $playerId = intval($playerData['player_id']);
            $teamId = intval($playerData['team_id']);

            $player = \App\Models\Player::find($playerId);
            
            if (!$player) {
                $errors[] = "Player {$playerId} not found";
                continue;
            }

            if ($teamId !== $game->team1_id && $teamId !== $game->team2_id) {
                $errors[] = "Team {$teamId} is not part of this game";
                continue;
            }

            PlayerGameStat::updateOrCreate(
                [
                    'game_id' => $game->id,
                    'player_id' => $playerId,
                ],
                [
                    'team_id' => $teamId,
                    'points' => intval($playerData['points'] ?? 0),
                    'fouls' => intval($playerData['fouls'] ?? 0),
                    'free_throws_made' => intval($playerData['free_throws_made'] ?? 0),
                    'free_throws_attempted' => intval($playerData['free_throws_attempted'] ?? 0),
                    'two_points_made' => intval($playerData['two_points_made'] ?? 0),
                    'two_points_attempted' => intval($playerData['two_points_attempted'] ?? 0),
                    'three_points_made' => intval($playerData['three_points_made'] ?? 0),
                    'three_points_attempted' => intval($playerData['three_points_attempted'] ?? 0),
                    'assists' => intval($playerData['assists'] ?? 0),
                    'steals' => intval($playerData['steals'] ?? 0),
                    'rebounds' => intval($playerData['rebounds'] ?? 0),
                    'blocks' => intval($playerData['blocks'] ?? 0),  // ✅ ADD THIS
                ]
            );

            $savedCount++;

        } catch (\Exception $e) {
            $errors[] = "Error saving player {$playerId}: " . $e->getMessage();
            \Log::error("Failed to save player stat", [
                'player_data' => $playerData,
                'error' => $e->getMessage()
            ]);
        }
    }

    \Log::info("Saved player stats for game {$game->id}", [
        'saved_count' => $savedCount,
        'total_attempted' => count($playerStatsData),
        'errors' => $errors
    ]);
}

    /**
     * Advance the bracket by setting the winner in the next round
     */
    private function advanceBracket(Game $completedGame, $winnerId)
    {
        $bracket = $completedGame->bracket;
        if (!$bracket) {
            return;
        }

        // Find the next game that this winner should advance to
        $nextRound = $completedGame->round + 1;
        
        // Calculate which game in the next round this winner goes to
        $nextGameNumber = ceil($completedGame->match_number / 2);

        $nextGame = Game::where('bracket_id', $bracket->id)
            ->where('round', $nextRound)
            ->where('match_number', $nextGameNumber)
            ->first();

        if ($nextGame) {
            // Determine if winner goes to team1 or team2 slot in next game
            if ($completedGame->match_number % 2 == 1) {
                $nextGame->team1_id = $winnerId;
            } else {
                $nextGame->team2_id = $winnerId;
            }

            $nextGame->save();
            \Log::info("Advanced bracket: Winner of Game {$completedGame->match_number} advanced to Game {$nextGame->match_number}");
        }
    }

    /**
     * Update bracket status based on completion of games
     */
    private function updateBracketStatus($bracketId)
    {
        $bracket = \App\Models\Bracket::find($bracketId);
        if (!$bracket) {
            return;
        }

        $totalGames = $bracket->games()->count();
        $completedGames = $bracket->games()->where('status', 'completed')->count();

        if ($completedGames == $totalGames) {
            $bracket->update(['status' => 'completed']);
            \Log::info("Bracket {$bracketId} marked as completed");
        } elseif ($completedGames > 0 && $bracket->status == 'setup') {
            $bracket->update(['status' => 'active']);
            \Log::info("Bracket {$bracketId} marked as active");
        }
    }

    public function index()
{
    // Get all tournaments with their games, brackets, and teams
    $tournamentGames = Tournament::with([
        'brackets.games' => function($query) {
            $query->with(['team1', 'team2'])
                  ->orderBy('round', 'asc')
                  ->orderBy('match_number', 'asc');
        }
    ])
    ->whereHas('brackets.games') // Only tournaments that have games
    ->orderBy('start_date', 'desc')
    ->get();

    // Flatten games collection for statistics
    $allGames = collect();
    foreach ($tournamentGames as $tournament) {
        foreach ($tournament->brackets as $bracket) {
            $allGames = $allGames->merge($bracket->games);
        }
    }

    // Calculate statistics
    $totalGames = $allGames->count();
    $liveGames = $allGames->where('status', 'in-progress')->count();
    $completedGames = $allGames->where('status', 'completed')->count();
    $upcomingGames = $allGames->whereIn('status', ['scheduled', 'pending'])->count();

    // Get all tournaments for filter dropdown
    $tournaments = Tournament::orderBy('name')->get();

    // Transform the data to make games accessible at tournament level
    $tournamentGames->transform(function ($tournament) {
        // Flatten all games from all brackets of this tournament
        $tournament->games = collect();
        foreach ($tournament->brackets as $bracket) {
            $tournament->games = $tournament->games->merge($bracket->games);
        }
        
        // Sort games by round and match number
        $tournament->games = $tournament->games->sortBy([
            ['round', 'asc'],
            ['match_number', 'asc']
        ]);

        return $tournament;
    });

    return view('games', compact(
        'tournamentGames',
        'tournaments',
        'totalGames',
        'liveGames',
        'completedGames',
        'upcomingGames'
    ));
}

/**
 * Show box score for a game
 */
public function boxScore(Game $game)
{
    // Load necessary relationships
    $game->load([
        'team1',
        'team2',
        'playerStats.player',
        'bracket.tournament'
    ]);

    // Get player stats grouped by team
    $team1Stats = $game->playerStats()
        ->where('team_id', $game->team1_id)
        ->with('player')
        ->orderByDesc('points')
        ->get();

    $team2Stats = $game->playerStats()
        ->where('team_id', $game->team2_id)
        ->with('player')
        ->orderByDesc('points')
        ->get();

    // Check if MVP has been selected
    $mvpSelected = $game->playerStats()->where('is_mvp', true)->exists();

    return view('games.box-score', compact(
        'game',
        'team1Stats',
        'team2Stats',
        'mvpSelected'
    ));
}

/**
 * Select MVP for a game
 */
public function selectMVP(Request $request, Game $game)
{
    $validated = $request->validate([
        'player_stat_id' => 'required|exists:player_game_stats,id'
    ]);

    DB::beginTransaction();
    
    try {
        // Clear any existing MVP
        $game->playerStats()->update(['is_mvp' => false]);

        // Set new MVP
        $mvpStat = PlayerGameStat::findOrFail($validated['player_stat_id']);
        $mvpStat->update(['is_mvp' => true]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'MVP selected successfully',
            'mvp' => [
                'player_name' => $mvpStat->player->name,
                'player_number' => $mvpStat->player->number,
                'points' => $mvpStat->points,
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to select MVP: ' . $e->getMessage()
        ], 500);
    }
}

// Add these methods to your existing GameController

/**
 * Show volleyball live scoresheet
 */
public function volleyballLive(Game $game)
{
    // Ensure game is in progress
    if ($game->status !== 'in_progress') {
        return redirect()->back()->with('error', 'Game has not been started yet!');
    }

    // Get stored player data
    $team1Data = json_decode($game->team1_selected_players, true) ?? [];
    $team2Data = json_decode($game->team2_selected_players, true) ?? [];

    // Load the actual player data
    $game->load(['team1.players', 'team2.players']);
    
    // Extract roster IDs (volleyball typically uses 6 starting players)
    $team1RosterIds = $team1Data['roster'] ?? [];
    $team2RosterIds = $team2Data['roster'] ?? [];
    $team1StarterIds = $team1Data['starters'] ?? [];
    $team2StarterIds = $team2Data['starters'] ?? [];

    // Filter players based on roster selection
    $team1Players = $game->team1->players->filter(function($player) use ($team1RosterIds) {
        return in_array($player->id, $team1RosterIds);
    });
    
    $team2Players = $game->team2->players->filter(function($player) use ($team2RosterIds) {
        return in_array($player->id, $team2RosterIds);
    });

    // Convert IDs to strings for JavaScript comparison
    $team1Roster = array_map('strval', $team1RosterIds);
    $team2Roster = array_map('strval', $team2RosterIds);
    $team1Starters = array_map('strval', $team1StarterIds);
    $team2Starters = array_map('strval', $team2StarterIds);

    return view('games.volleyball-live', compact(
        'game', 
        'team1Players', 
        'team2Players',
        'team1Roster',
        'team2Roster', 
        'team1Starters', 
        'team2Starters'
    ));
}

/**
 * Complete volleyball game and save results
 */
public function completeVolleyballGame(Request $request, Game $game)
{
    $game->load(['bracket.tournament', 'team1.players', 'team2.players']);
   
    try {
        $validated = $request->validate([
            'team1_score' => 'required|integer|min:0', // Sets won
            'team2_score' => 'required|integer|min:0', // Sets won
            'set_scores' => 'required|array',
            'game_events' => 'array',
            'winner_id' => 'nullable|integer|in:1,2',
            'status' => 'string|in:completed',
            'completed_at' => 'string',
            'player_stats' => 'array',
        ]);

        DB::beginTransaction();

        // Determine winner (best of 5 sets, first to 3 wins)
        $winnerId = null;
        if ($validated['team1_score'] > $validated['team2_score']) {
            $winnerId = $game->team1_id;
        } elseif ($validated['team2_score'] > $validated['team1_score']) {
            $winnerId = $game->team2_id;
        }

        // Update game
        $game->update([
            'team1_score' => $validated['team1_score'], // Sets won by team 1
            'team2_score' => $validated['team2_score'], // Sets won by team 2
            'winner_id' => $winnerId,
            'status' => 'completed',
            'completed_at' => now(),
            'game_data' => [
                'set_scores' => $validated['set_scores'],
                'game_events' => $validated['game_events'] ?? [],
                'completed_by_scoresheet' => true,
                'sport_type' => 'volleyball'
            ]
        ]);

        // Save volleyball player statistics
        if (isset($validated['player_stats']) && is_array($validated['player_stats'])) {
            $this->saveVolleyballPlayerStats($game, $validated['player_stats']);
        }

        // Save volleyball tallysheet
        $this->saveVolleyballTallysheet($game, $validated);

        // Advance bracket if needed
        if ($game->bracket_id && $winnerId) {
            $this->advanceBracket($game, $winnerId);
        }

        if ($game->bracket_id) {
            $this->updateBracketStatus($game->bracket_id);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Volleyball game completed successfully',
            'game_id' => $game->id,
            'winner_id' => $winnerId,
            'redirect_url' => route('games.volleyball-box-score', $game->id)
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("Failed to complete volleyball game {$game->id}", [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to complete game: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Save volleyball player statistics
 */
private function saveVolleyballPlayerStats(Game $game, array $playerStatsData)
{
    $savedCount = 0;
    $errors = [];

    foreach ($playerStatsData as $index => $playerData) {
        try {
            if (!isset($playerData['player_id']) || !isset($playerData['team_id'])) {
                $errors[] = "Player data at index {$index} missing required fields";
                continue;
            }

            $playerId = intval($playerData['player_id']);
            $teamId = intval($playerData['team_id']);

            $player = \App\Models\Player::find($playerId);
            
            if (!$player) {
                $errors[] = "Player {$playerId} not found";
                continue;
            }

            if ($teamId !== $game->team1_id && $teamId !== $game->team2_id) {
                $errors[] = "Team {$teamId} is not part of this game";
                continue;
            }

            VolleyballPlayerStat::updateOrCreate(
                [
                    'game_id' => $game->id,
                    'player_id' => $playerId,
                ],
                [
                    'team_id' => $teamId,
                    'kills' => intval($playerData['kills'] ?? 0),
                    'aces' => intval($playerData['aces'] ?? 0),
                    'blocks' => intval($playerData['blocks'] ?? 0),
                    'digs' => intval($playerData['digs'] ?? 0),
                    'assists' => intval($playerData['assists'] ?? 0),
                    'errors' => intval($playerData['errors'] ?? 0),
                    'service_errors' => intval($playerData['service_errors'] ?? 0),
                    'attack_attempts' => intval($playerData['attack_attempts'] ?? 0),
                    'block_assists' => intval($playerData['block_assists'] ?? 0),
                ]
            );

            $savedCount++;

        } catch (\Exception $e) {
            $errors[] = "Error saving player {$playerId}: " . $e->getMessage();
            \Log::error("Failed to save volleyball player stat", [
                'player_data' => $playerData,
                'error' => $e->getMessage()
            ]);
        }
    }

    \Log::info("Saved volleyball player stats for game {$game->id}", [
        'saved_count' => $savedCount,
        'total_attempted' => count($playerStatsData),
        'errors' => $errors
    ]);
}

/**
 * Save volleyball tallysheet
 */
/**
 * Save volleyball tallysheet
 */
private function saveVolleyballTallysheet(Game $game, array $gameData)
{
    try {
        // Process running scores from game events
        $runningScores = $this->processVolleyballRunningScores($gameData['game_events'] ?? []);
        
        // Create or update volleyball tallysheet
        VolleyballTallysheet::updateOrCreate(
            ['game_id' => $game->id],
            [
                'user_id' => auth()->id(), // ✅ Save the logged-in user
                'team1_sets_won' => $gameData['team1_score'],
                'team2_sets_won' => $gameData['team2_score'],
                'set_scores' => $gameData['set_scores'],
                'game_events' => $gameData['game_events'] ?? [],
                'running_scores' => $runningScores,
            ]
        );

        \Log::info("Volleyball tallysheet saved for game {$game->id} by user " . auth()->id());
        
    } catch (\Exception $e) {
        \Log::error("Failed to save volleyball tallysheet for game {$game->id}", [
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}

/**
 * Process running scores for volleyball
 */
private function processVolleyballRunningScores(array $events)
{
    $runningScores = [];
    
    // Initialize score tracking for all sets
    $setScores = [
        1 => ['A' => 0, 'B' => 0],
        2 => ['A' => 0, 'B' => 0],
        3 => ['A' => 0, 'B' => 0],
        4 => ['A' => 0, 'B' => 0],
        5 => ['A' => 0, 'B' => 0],
    ];
    
    // Process events in chronological order (reverse since they're stored newest first)
    $sortedEvents = array_reverse($events);
    
    \Log::info('=== Processing Volleyball Running Scores ===');
    \Log::info('Total events to process: ' . count($sortedEvents));
    
    foreach ($sortedEvents as $index => $event) {
        // Skip non-scoring events
        if (!isset($event['points']) || $event['points'] <= 0) {
            continue;
        }
        
        // Check if set number exists
        if (!isset($event['set'])) {
            \Log::warning('Event missing set number at index ' . $index, ['event' => $event]);
            continue;
        }
        
        $set = (int)$event['set'];
        $team = $event['team'] ?? null;
        
        // Validate set and team
        if ($set < 1 || $set > 5) {
            \Log::warning('Invalid set number: ' . $set, ['event' => $event]);
            continue;
        }
        
        if (!in_array($team, ['A', 'B'])) {
            \Log::warning('Invalid team: ' . $team, ['event' => $event]);
            continue;
        }
        
        // Increment score for the team in this set
        $setScores[$set][$team]++;
        
        // Add to running scores with set information
        $runningScores[] = [
            'team' => $team,
            'score' => $setScores[$set][$team],
            'set' => $set,
            'sequence' => count($runningScores) + 1
        ];
    }
    
    // Log summary
    \Log::info('=== Processing Complete ===');
    \Log::info('Total running scores created: ' . count($runningScores));
    
    for ($s = 1; $s <= 5; $s++) {
        $count = count(array_filter($runningScores, fn($rs) => $rs['set'] === $s));
        if ($count > 0) {
            \Log::info("Set $s: $count running scores");
        }
    }
    
    return $runningScores;
}

/**
 * Show volleyball scoresheet (for printing/viewing)
 */
/**
 * Show volleyball scoresheet (for printing/viewing)
 */
public function volleyballScoresheet(Game $game, Request $request)
{
    // Get live data if passed from the game interface
    $liveData = null;
    if ($request->has('live_data')) {
        $liveData = json_decode(urldecode($request->get('live_data')), true);
    } else {
        // ✅ Load saved tallysheet if game is completed
        if ($game->status === 'completed' && $game->volleyballTallysheet) {
            $tallysheet = $game->volleyballTallysheet;
            $liveData = [
                'team1_score' => $tallysheet->team1_sets_won,
                'team2_score' => $tallysheet->team2_sets_won,
                'set_scores' => $tallysheet->set_scores,
                'events' => $tallysheet->game_events,
                'running_scores' => $tallysheet->running_scores,
                'team1_timeouts' => $tallysheet->team1_timeouts ?? 0,
                'team2_timeouts' => $tallysheet->team2_timeouts ?? 0,
                'team1_substitutions' => $tallysheet->team1_substitutions ?? 0,
                'team2_substitutions' => $tallysheet->team2_substitutions ?? 0,
                'initial_server' => $tallysheet->initial_server,
                'best_player_id' => $tallysheet->best_player_id,
                'best_player_stats' => $tallysheet->best_player_stats,
            ];
        }
    }

    $team1Data = json_decode($game->team1_selected_players, true) ?? [];
    $team2Data = json_decode($game->team2_selected_players, true) ?? [];

    $game->load(['team1.players', 'team2.players', 'bracket.tournament', 'volleyballPlayerStats.player']);
    
    $team1RosterIds = $team1Data['roster'] ?? [];
    $team2RosterIds = $team2Data['roster'] ?? [];

    $team1Players = $game->team1->players->filter(function($player) use ($team1RosterIds) {
        return in_array($player->id, $team1RosterIds);
    })->sortBy('number');
    
    $team2Players = $game->team2->players->filter(function($player) use ($team2RosterIds) {
        return in_array($player->id, $team2RosterIds);
    })->sortBy('number');

    // ✅ NEW: Calculate team statistics
    $team1Stats = $this->calculateVolleyballTeamStats($game, $game->team1_id);
    $team2Stats = $this->calculateVolleyballTeamStats($game, $game->team2_id);

    // ✅ NEW: Get individual player stats
    $team1PlayerStats = $game->volleyballPlayerStats()
        ->where('team_id', $game->team1_id)
        ->with('player')
        ->get()
        ->keyBy('player_id');

    $team2PlayerStats = $game->volleyballPlayerStats()
        ->where('team_id', $game->team2_id)
        ->with('player')
        ->get()
        ->keyBy('player_id');

    $isPdf = false; // ✅ For browser view
    
    return view('games.volleyball-scoresheet', compact(
        'game',
        'team1Players',
        'team2Players',
        'liveData',
        'isPdf',
        'team1Stats',
        'team2Stats',
        'team1PlayerStats',
        'team2PlayerStats'
    ));
}

/**
 * Show volleyball box score
 */
public function volleyballBoxScore(Game $game)
{
    // Load necessary relationships
    $game->load([
        'team1',
        'team2',
        'volleyballPlayerStats.player',
        'bracket.tournament',
        'volleyballTallysheet'
    ]);

    // Get player stats grouped by team
    $team1Stats = $game->volleyballPlayerStats()
        ->where('team_id', $game->team1_id)
        ->with('player')
        ->orderByDesc('kills')
        ->get();

    $team2Stats = $game->volleyballPlayerStats()
        ->where('team_id', $game->team2_id)
        ->with('player')
        ->orderByDesc('kills')
        ->get();

    // Check if MVP has been selected
    $mvpSelected = $game->volleyballPlayerStats()->where('is_mvp', true)->exists();

    return view('games.volleyball-box-score', compact(
        'game',
        'team1Stats',
        'team2Stats',
        'mvpSelected'
    ));
}

/**
 * Select MVP for volleyball game
 */
public function selectVolleyballMVP(Request $request, Game $game)
{
    $validated = $request->validate([
        'player_stat_id' => 'required|exists:volleyball_player_stats,id'
    ]);

    DB::beginTransaction();
    
    try {
        // Clear any existing MVP
        $game->volleyballPlayerStats()->update(['is_mvp' => false]);

        // Set new MVP
        $mvpStat = VolleyballPlayerStat::findOrFail($validated['player_stat_id']);
        $mvpStat->update(['is_mvp' => true]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'MVP selected successfully',
            'mvp' => [
                'player_name' => $mvpStat->player->name,
                'player_number' => $mvpStat->player->number,
                'kills' => $mvpStat->kills,
                'aces' => $mvpStat->aces,
                'blocks' => $mvpStat->blocks,
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to select MVP: ' . $e->getMessage()
        ], 500);
    }
}


    public function getGameState(Game $game)
{
    // Check if user has access to this game
    if (!auth()->check() || !GameAssignmentController::hasActiveRole(auth()->user(), $game)) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // ✅ FIX: game_data is already an array, don't json_decode it
    $gameData = $game->game_data ?? [];
    
    return response()->json([
        'gameId' => $game->id,
        'scoreA' => $game->team1_score ?? 0,
        'scoreB' => $game->team2_score ?? 0,
        'foulsA' => $gameData['team1_fouls'] ?? 0,
        'foulsB' => $gameData['team2_fouls'] ?? 0,
        'timeoutsA' => $gameData['team1_timeouts'] ?? 0,
        'timeoutsB' => $gameData['team2_timeouts'] ?? 0,
        'events' => $gameData['game_events'] ?? [],  // ✅ No json_decode needed
        'last_update' => $game->updated_at->timestamp,
    ]);
}

    public function updateGameState(Request $request, Game $game)
{
    if (!GameAssignmentController::canScore(auth()->user(), $game)) {
        return response()->json(['error' => 'Only scorer can update'], 403);
    }

    $validated = $request->validate([
        'scoreA' => 'required|integer|min:0',
        'scoreB' => 'required|integer|min:0',
        'foulsA' => 'integer|min:0',
        'foulsB' => 'integer|min:0',
        'timeoutsA' => 'integer|min:0',
        'timeoutsB' => 'integer|min:0',
        'events' => 'array',
    ]);

    // Get existing game_data and preserve it
    $gameData = $game->game_data ?? [];
    
    // Update only the fields we care about
    $gameData['team1_fouls'] = $validated['foulsA'] ?? 0;
    $gameData['team2_fouls'] = $validated['foulsB'] ?? 0;
    $gameData['team1_timeouts'] = $validated['timeoutsA'] ?? 0;
    $gameData['team2_timeouts'] = $validated['timeoutsB'] ?? 0;
    $gameData['game_events'] = $validated['events'] ?? [];
    $gameData['last_update'] = now()->timestamp;

    $game->update([
        'team1_score' => $validated['scoreA'],
        'team2_score' => $validated['scoreB'],
        'game_data' => $gameData,
    ]);

    return response()->json(['success' => true, 'last_update' => now()->timestamp]);
}

public function recordScore(Request $request, Game $game)
{
    // Validate that only scorers can call this
    if (!GameAssignmentController::canScore(auth()->user(), $game)) {
        return response()->json(['error' => 'Only scorers can record scores'], 403);
    }

    $validated = $request->validate([
        'team' => 'required|in:A,B',
        'player' => 'required|string',
        'action' => 'required|string|in:Free Throw,2 Points,3 Points',
        'points' => 'required|integer|min:1|max:3',
    ]);

    // Get existing game_data
    $gameData = $game->game_data ?? [];
    
    // Add the scoring event
    $event = [
        'team' => $validated['team'],
        'player' => $validated['player'],
        'action' => $validated['action'],
        'points' => $validated['points'],
        'timestamp' => now()->timestamp,
    ];

    if (!isset($gameData['game_events'])) {
        $gameData['game_events'] = [];
    }

    // Add to beginning of array (newest first)
    array_unshift($gameData['game_events'], $event);

    // Update scores
    if ($validated['team'] === 'A') {
        $game->team1_score = ($game->team1_score ?? 0) + $validated['points'];
    } else {
        $game->team2_score = ($game->team2_score ?? 0) + $validated['points'];
    }

    // Save updated game
    $game->update([
        'game_data' => $gameData,
        'team1_score' => $game->team1_score,
        'team2_score' => $game->team2_score,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Score recorded',
        'event' => $event,
    ]);
}

/**
 * Record a foul action
 * Only Scorer can call this
 */
public function recordFoul(Request $request, Game $game)
{
    if (!GameAssignmentController::canScore(auth()->user(), $game)) {
        return response()->json(['error' => 'Only scorers can record fouls'], 403);
    }

    $validated = $request->validate([
        'team' => 'required|in:A,B',
        'player' => 'required|string',
        'foul_type' => 'required|string|in:personal,shooting,technical',
    ]);

    $gameData = $game->game_data ?? [];

    $event = [
        'team' => $validated['team'],
        'player' => $validated['player'],
        'action' => $validated['foul_type'] . '_foul',
        'timestamp' => now()->timestamp,
    ];

    if (!isset($gameData['game_events'])) {
        $gameData['game_events'] = [];
    }

    array_unshift($gameData['game_events'], $event);

    // Update team fouls
    if ($validated['team'] === 'A') {
        $gameData['team1_fouls'] = ($gameData['team1_fouls'] ?? 0) + 1;
    } else {
        $gameData['team2_fouls'] = ($gameData['team2_fouls'] ?? 0) + 1;
    }

    $game->update(['game_data' => $gameData]);

    return response()->json([
        'success' => true,
        'message' => 'Foul recorded',
        'event' => $event,
    ]);
}

/**
 * Record an assist (stat action)
 * Only Stat-Keeper can call this
 */
public function recordAssist(Request $request, Game $game)
{
    if (!GameAssignmentController::canRecordStats(auth()->user(), $game)) {
        return response()->json(['error' => 'Only stat-keepers can record stats'], 403);
    }

    $validated = $request->validate([
        'team' => 'required|in:A,B',
        'player' => 'required|string',
    ]);

    $gameData = $game->game_data ?? [];

    $event = [
        'team' => $validated['team'],
        'player' => $validated['player'],
        'action' => 'Assist',
        'timestamp' => now()->timestamp,
        'recorded_by' => 'stat_keeper',
    ];

    if (!isset($gameData['game_events'])) {
        $gameData['game_events'] = [];
    }

    array_unshift($gameData['game_events'], $event);

    $game->update(['game_data' => $gameData]);

    return response()->json([
        'success' => true,
        'message' => 'Assist recorded',
        'event' => $event,
    ]);
}

/**
 * Record a steal (stat action)
 * Only Stat-Keeper can call this
 */
public function recordSteal(Request $request, Game $game)
{
    if (!GameAssignmentController::canRecordStats(auth()->user(), $game)) {
        return response()->json(['error' => 'Only stat-keepers can record stats'], 403);
    }

    $validated = $request->validate([
        'team' => 'required|in:A,B',
        'player' => 'required|string',
    ]);

    $gameData = $game->game_data ?? [];

    $event = [
        'team' => $validated['team'],
        'player' => $validated['player'],
        'action' => 'Steal',
        'timestamp' => now()->timestamp,
        'recorded_by' => 'stat_keeper',
    ];

    if (!isset($gameData['game_events'])) {
        $gameData['game_events'] = [];
    }

    array_unshift($gameData['game_events'], $event);

    $game->update(['game_data' => $gameData]);

    return response()->json([
        'success' => true,
        'message' => 'Steal recorded',
        'event' => $event,
    ]);
}

/**
 * Record a rebound (stat action)
 * Only Stat-Keeper can call this
 */
public function recordRebound(Request $request, Game $game)
{
    if (!GameAssignmentController::canRecordStats(auth()->user(), $game)) {
        return response()->json(['error' => 'Only stat-keepers can record stats'], 403);
    }

    $validated = $request->validate([
        'team' => 'required|in:A,B',
        'player' => 'required|string',
    ]);

    $gameData = $game->game_data ?? [];

    $event = [
        'team' => $validated['team'],
        'player' => $validated['player'],
        'action' => 'Rebound',
        'timestamp' => now()->timestamp,
        'recorded_by' => 'stat_keeper',
    ];

    if (!isset($gameData['game_events'])) {
        $gameData['game_events'] = [];
    }

    array_unshift($gameData['game_events'], $event);

    $game->update(['game_data' => $gameData]);

    return response()->json([
        'success' => true,
        'message' => 'Rebound recorded',
        'event' => $event,
    ]);
}

/**
 * Record a block (stat action)
 * Only Stat-Keeper can call this
 */
public function recordBlock(Request $request, Game $game)
{
    if (!GameAssignmentController::canRecordStats(auth()->user(), $game)) {
        return response()->json(['error' => 'Only stat-keepers can record stats'], 403);
    }

    $validated = $request->validate([
        'team' => 'required|in:A,B',
        'player' => 'required|string',
    ]);

    $gameData = $game->game_data ?? [];

    $event = [
        'team' => $validated['team'],
        'player' => $validated['player'],
        'action' => 'blocks',
        'timestamp' => now()->timestamp,
        'recorded_by' => 'stat_keeper',
    ];

    if (!isset($gameData['game_events'])) {
        $gameData['game_events'] = [];
    }

    array_unshift($gameData['game_events'], $event);

    $game->update(['game_data' => $gameData]);

    return response()->json([
        'success' => true,
        'message' => 'Block recorded',
        'event' => $event,
    ]);
}

/**
 * Record timeout action
 * Only Scorer can call this
 */
public function recordTimeout(Request $request, Game $game)
{
    if (!GameAssignmentController::canScore(auth()->user(), $game)) {
        return response()->json(['error' => 'Only scorers can record timeouts'], 403);
    }

    $validated = $request->validate([
        'team' => 'required|in:A,B',
    ]);

    $gameData = $game->game_data ?? [];

    $event = [
        'team' => $validated['team'],
        'player' => 'TEAM',
        'action' => 'Timeout',
        'timestamp' => now()->timestamp,
    ];

    if (!isset($gameData['game_events'])) {
        $gameData['game_events'] = [];
    }

    array_unshift($gameData['game_events'], $event);

    // Update timeouts count
    if ($validated['team'] === 'A') {
        $gameData['team1_timeouts'] = ($gameData['team1_timeouts'] ?? 0) + 1;
    } else {
        $gameData['team2_timeouts'] = ($gameData['team2_timeouts'] ?? 0) + 1;
    }

    $game->update(['game_data' => $gameData]);

    return response()->json([
        'success' => true,
        'message' => 'Timeout recorded',
        'event' => $event,
    ]);
}

/**
 * Record substitution
 * Only Scorer can call this
 */
public function recordSubstitution(Request $request, Game $game)
{
    if (!GameAssignmentController::canScore(auth()->user(), $game)) {
        return response()->json(['error' => 'Only scorers can record substitutions'], 403);
    }

    $validated = $request->validate([
        'team' => 'required|in:A,B',
        'player_out' => 'required|string',
        'player_in' => 'required|string',
    ]);

    $gameData = $game->game_data ?? [];

    $event = [
        'team' => $validated['team'],
        'player' => $validated['player_out'] . '→' . $validated['player_in'],
        'action' => 'Substitution',
        'timestamp' => now()->timestamp,
    ];

    if (!isset($gameData['game_events'])) {
        $gameData['game_events'] = [];
    }

    array_unshift($gameData['game_events'], $event);

    $game->update(['game_data' => $gameData]);

    return response()->json([
        'success' => true,
        'message' => 'Substitution recorded',
        'event' => $event,
    ]);
}

/**
 * Calculate volleyball team statistics
 */
public function calculateVolleyballTeamStats(Game $game, $teamId)
{
    $stats = $game->volleyballPlayerStats()
        ->where('team_id', $teamId)
        ->selectRaw('
            SUM(kills) as total_kills,
            SUM(aces) as total_aces,
            SUM(blocks) as total_blocks,
            SUM(digs) as total_digs,
            SUM(assists) as total_assists,
            SUM(errors) as total_errors,
            SUM(service_errors) as total_service_errors,
            SUM(attack_attempts) as total_attack_attempts
        ')
        ->first();

    // Calculate opponent errors (points scored by opponent's errors)
    $events = $game->volleyballTallysheet->game_events ?? [];
    $opponentTeam = $teamId === $game->team1_id ? 'B' : 'A';
    
    $opponentErrors = collect($events)->filter(function($event) use ($opponentTeam) {
        return isset($event['team']) && 
               $event['team'] === $opponentTeam && 
               isset($event['action']) && 
               stripos($event['action'], 'error') !== false;
    })->count();

    // Find best scorer (kills + aces + blocks)
    $bestScorer = $game->volleyballPlayerStats()
        ->where('team_id', $teamId)
        ->with('player')
        ->orderByRaw('(kills + aces + blocks) DESC')
        ->first();

    // Get top performers for each skill
    $topKiller = $game->volleyballPlayerStats()
        ->where('team_id', $teamId)
        ->with('player')
        ->orderBy('kills', 'DESC')
        ->first();

    $topBlocker = $game->volleyballPlayerStats()
        ->where('team_id', $teamId)
        ->with('player')
        ->orderBy('blocks', 'DESC')
        ->first();

    $topServer = $game->volleyballPlayerStats()
        ->where('team_id', $teamId)
        ->with('player')
        ->orderBy('aces', 'DESC')
        ->first();

    $topDigger = $game->volleyballPlayerStats()
        ->where('team_id', $teamId)
        ->with('player')
        ->orderBy('digs', 'DESC')
        ->first();

    $topSetter = $game->volleyballPlayerStats()
        ->where('team_id', $teamId)
        ->with('player')
        ->orderBy('assists', 'DESC')
        ->first();

    return [
        'total_kills' => $stats->total_kills ?? 0,
        'total_aces' => $stats->total_aces ?? 0,
        'total_blocks' => $stats->total_blocks ?? 0,
        'total_digs' => $stats->total_digs ?? 0,
        'total_assists' => $stats->total_assists ?? 0,
        'total_errors' => $stats->total_errors ?? 0,
        'total_service_errors' => $stats->total_service_errors ?? 0,
        'total_attack_attempts' => $stats->total_attack_attempts ?? 0,
        'opponent_errors' => $opponentErrors,
        'best_scorer' => $bestScorer ? [
            'number' => $bestScorer->player->number ?? '00',
            'name' => $bestScorer->player->name ?? 'Unknown',
            'points' => $bestScorer->kills + $bestScorer->aces + $bestScorer->blocks
        ] : null,
        'top_killer' => $topKiller ? [
            'number' => $topKiller->player->number ?? '00',
            'kills' => $topKiller->kills,
            'attempts' => $topKiller->attack_attempts
        ] : null,
        'top_blocker' => $topBlocker ? [
            'number' => $topBlocker->player->number ?? '00',
            'blocks' => $topBlocker->blocks
        ] : null,
        'top_server' => $topServer ? [
            'number' => $topServer->player->number ?? '00',
            'aces' => $topServer->aces
        ] : null,
        'top_digger' => $topDigger ? [
            'number' => $topDigger->player->number ?? '00',
            'digs' => $topDigger->digs,
            'attempts' => $topDigger->digs // You can track this separately if needed
        ] : null,
        'top_setter' => $topSetter ? [
            'number' => $topSetter->player->number ?? '00',
            'assists' => $topSetter->assists,
            'attempts' => $topSetter->assists // You can track this separately if needed
        ] : null,
    ];
}

/**
 * Auto-save game state during live game
 */
public function autoSave(Request $request, Game $game)
{
    try {
        // Get the live state data from the request
        $liveState = $request->all();
        
        // Get existing game_data
        $gameData = $game->game_data ?? [];
        
        // Update the live_state section within game_data
        $gameData['live_state'] = [
            'team1_score' => $liveState['team1_score'] ?? 0,
            'team2_score' => $liveState['team2_score'] ?? 0,
            'team1_fouls' => $liveState['team1_fouls'] ?? 0,
            'team2_fouls' => $liveState['team2_fouls'] ?? 0,
            'team1_timeouts' => $liveState['team1_timeouts'] ?? 0,
            'team2_timeouts' => $liveState['team2_timeouts'] ?? 0,
            'current_quarter' => $liveState['current_quarter'] ?? 1,
            'time_remaining' => $liveState['time_remaining'] ?? 0,
            'shot_clock' => $liveState['shot_clock'] ?? 24,
            'game_events' => $liveState['game_events'] ?? [],
            'period_scores' => $liveState['period_scores'] ?? [],
            'active_players' => $liveState['active_players'] ?? [],
            'bench_players' => $liveState['bench_players'] ?? [],
            'possession' => $liveState['possession'] ?? 'A',
            'is_running' => $liveState['is_running'] ?? false,
            'last_auto_save' => now()->toDateTimeString(),
        ];
        
        // Save to database
        $game->game_data = $gameData;
        $game->save();
        
        \Log::info("Game {$game->id} auto-saved successfully");
        
        return response()->json([
            'success' => true,
            'message' => 'Game state saved',
            'saved_at' => now()->toDateTimeString()
        ]);
        
    } catch (\Exception $e) {
        \Log::error("Auto-save failed for game {$game->id}: " . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to save game state',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Load saved game state
 */
public function loadState(Game $game)
{
    try {
        $gameData = $game->game_data ?? [];
        $liveState = $gameData['live_state'] ?? null;
        
        if ($liveState) {
            \Log::info("Loading saved state for game {$game->id}");
            
            return response()->json([
                'has_saved_state' => true,
                'team1_score' => $liveState['team1_score'] ?? 0,
                'team2_score' => $liveState['team2_score'] ?? 0,
                'team1_fouls' => $liveState['team1_fouls'] ?? 0,
                'team2_fouls' => $liveState['team2_fouls'] ?? 0,
                'team1_timeouts' => $liveState['team1_timeouts'] ?? 0,
                'team2_timeouts' => $liveState['team2_timeouts'] ?? 0,
                'current_quarter' => $liveState['current_quarter'] ?? 1,
                'time_remaining' => $liveState['time_remaining'] ?? 0,
                'shot_clock' => $liveState['shot_clock'] ?? 24,
                'game_events' => $liveState['game_events'] ?? [],
                'period_scores' => $liveState['period_scores'] ?? [],
                'active_players' => $liveState['active_players'] ?? [],
                'bench_players' => $liveState['bench_players'] ?? [],
                'possession' => $liveState['possession'] ?? 'A',
                'is_running' => $liveState['is_running'] ?? false,
                'last_saved' => $liveState['last_auto_save'] ?? null,
            ]);
        }
        
        return response()->json([
            'has_saved_state' => false,
            'message' => 'No saved state found'
        ]);
        
    } catch (\Exception $e) {
        \Log::error("Failed to load state for game {$game->id}: " . $e->getMessage());
        
        return response()->json([
            'has_saved_state' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

}