<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use App\Models\PlayerGameStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tournament;

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
    }

    // Get stored player data
    $team1Data = json_decode($game->team1_selected_players, true) ?? [];
    $team2Data = json_decode($game->team2_selected_players, true) ?? [];

    // Load the actual player data
    $game->load(['team1.players', 'team2.players', 'bracket.tournament']);
    
    // Extract roster IDs
    $team1RosterIds = $team1Data['roster'] ?? [];
    $team2RosterIds = $team2Data['roster'] ?? [];

    // Filter players based on roster selection
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
        'liveData'
    ));
}

    public function startLive(Request $request, Game $game)
    {
        // Validate the roster and starter selections (NEW FORMAT)
        $validated = $request->validate([
            'team1_roster' => 'required|json',
            'team2_roster' => 'required|json',
            'team1_starters' => 'required|json', 
            'team2_starters' => 'required|json',
        ]);

        // Decode the selections
        $team1RosterIds = json_decode($validated['team1_roster'], true);
        $team2RosterIds = json_decode($validated['team2_roster'], true);
        $team1StarterIds = json_decode($validated['team1_starters'], true);
        $team2StarterIds = json_decode($validated['team2_starters'], true);

        // Validate roster sizes (minimum 5 players each)
        if (count($team1RosterIds) < 5 || count($team2RosterIds) < 5) {
            return back()->with('error', 'You must select at least 5 players for each team roster!');
        }

        // Validate starter selections (exactly 5 players each)
        if (count($team1StarterIds) !== 5 || count($team2StarterIds) !== 5) {
            return back()->with('error', 'You must select exactly 5 starters for each team!');
        }

        // Validate that starters are from the roster
        if (array_diff($team1StarterIds, $team1RosterIds) || array_diff($team2StarterIds, $team2RosterIds)) {
            return back()->with('error', 'All starters must be selected from the team roster!');
        }

        // Validate that at least one referee is assigned
        if (empty($game->referee)) {
            return back()->with('error', 'You must assign at least one referee before starting the game!');
        }

        // Store combined roster/starter data in existing JSON columns
        $team1Data = [
            'roster' => $team1RosterIds,
            'starters' => $team1StarterIds,
            'all_players' => array_map(function($id) { return "player1_{$id}"; }, $team1RosterIds)
        ];

        $team2Data = [
            'roster' => $team2RosterIds,
            'starters' => $team2StarterIds, 
            'all_players' => array_map(function($id) { return "player2_{$id}"; }, $team2RosterIds)
        ];

        // Update game status and store player selections in existing columns
        $game->update([
            'status' => 'in_progress',
            'started_at' => now(),
            'team1_selected_players' => json_encode($team1Data),
            'team2_selected_players' => json_encode($team2Data),
        ]);

        // Redirect to live scoresheet
        return redirect()->route('games.live', $game)->with('success', 'Game started successfully!');
    }

    public function live(Game $game)
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

        // Debug output (remove this after testing)
        \Log::info('Live Game Data:', [
            'team1Players_count' => $team1Players->count(),
            'team2Players_count' => $team2Players->count(),
            'team1Roster' => $team1Roster,
            'team1Starters' => $team1Starters,
            'team2Roster' => $team2Roster,
            'team2Starters' => $team2Starters,
        ]);

        return view('games.live-scoresheet', compact(
            'game', 
            'team1Players', 
            'team2Players',
            'team1Roster',
            'team2Roster', 
            'team1Starters', 
            'team2Starters'
        ));
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
    // Load relationships needed for redirect
    $game->load(['bracket.tournament', 'team1.players', 'team2.players']);
   
    try {
        // Validate the incoming data
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
            'player_stats' => 'array', // NEW: Accept player stats
        ]);

        DB::beginTransaction();

        // Determine winner based on scores
        $winnerId = null;
        if ($validated['team1_score'] > $validated['team2_score']) {
            $winnerId = $game->team1_id;
        } elseif ($validated['team2_score'] > $validated['team1_score']) {
            $winnerId = $game->team2_id;
        }

        // Update the game with final results
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

        // NEW: Save player statistics
        if (isset($validated['player_stats']) && is_array($validated['player_stats'])) {
            $this->savePlayerStats($game, $validated['player_stats']);
        }

        // If this is a tournament game, advance the bracket
        if ($game->bracket_id && $winnerId) {
            $this->advanceBracket($game, $winnerId);
        }

        // Update bracket status if all games in this round are complete
        if ($game->bracket_id) {
            $this->updateBracketStatus($game->bracket_id);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Game completed successfully',
            'game_id' => $game->id,
            'winner_id' => $winnerId,
            'redirect_url' => route('games.box-score', $game->id) // Changed to redirect to box score
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
                    'assists' => intval($playerData['assists'] ?? 0),     // ADD THIS
                    'steals' => intval($playerData['steals'] ?? 0),       // ADD THIS
                    'rebounds' => intval($playerData['rebounds'] ?? 0),   // ADD THIS
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
}