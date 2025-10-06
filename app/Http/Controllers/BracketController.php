<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Bracket;
use App\Models\Team;
use App\Models\Game;
use Illuminate\Http\Request;

class BracketController extends Controller
{
    /**
     * Show tournament with its brackets
     */
    public function showTournament($id)
    {
        $tournament = Tournament::with(['brackets.games', 'teams'])->findOrFail($id);

        // Get teams not assigned to this tournament (case-insensitive sport matching)
        $availableTeams = Team::whereRaw('LOWER(sport) = LOWER(?)', [$tournament->sport])
            ->where(function ($query) use ($tournament) {
                $query->whereNull('tournament_id')
                    ->orWhere('tournament_id', '!=', $tournament->id);
            })
            ->get();

        return view('tournament_show', compact('tournament', 'availableTeams'));
    }

    /**
     * Create a new bracket for a tournament
     */
    public function store(Request $request, $tournamentId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:single-elimination,double-elimination,round-robin,round-robin-playoff',
        ]);

        $tournament = Tournament::findOrFail($tournamentId);

        $bracket = Bracket::create([
            'tournament_id' => $tournament->id,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'status' => 'setup',
        ]);

        return redirect()
            ->route('tournaments.show', $tournament->id)
            ->with('success', 'Bracket created successfully!');
    }

    /**
     * Generate bracket based on type
     */
    public function generate(Request $request, Bracket $bracket)
    {
        switch ($bracket->type) {
            case 'single-elimination':
                $result = $this->generateSingleElimination($bracket);
                // Set up bye team in Round 2 if needed
                $this->setupByeTeamInRound2($bracket);
                return $result;
            case 'round-robin':
                return $this->generateRoundRobin($bracket);
            case 'round-robin-playoff':
                return $this->generateRoundRobinPlayoff($bracket);
            case 'double-elimination':
                return back()->with('error', 'Double elimination not yet implemented.');
            default:
                return back()->with('error', 'Unknown bracket type.');
        }
    }

    /**
     * Generate single elimination bracket with bye support (ONLY for odd number of teams)
     */
    public function generateSingleElimination(Bracket $bracket)
    {
        $teams = $bracket->tournament->teams;

        if ($teams->count() < 2) {
            return back()->with('error', 'Need at least 2 teams to generate bracket.');
        }

        // Clear existing games
        $bracket->games()->delete();

        $teamCount = $teams->count();
        $isOddTeams = $teamCount % 2 === 1;
        
        // Calculate total rounds needed
        $effectiveTeamCount = $isOddTeams ? $teamCount + 1 : $teamCount;
        $totalRounds = ceil(log($effectiveTeamCount, 2));

        // Attach teams to bracket with seeding
        $bracket->teams()->detach();
        $teams->each(function ($team, $index) use ($bracket) {
            $bracket->teams()->attach($team->id, ['seed' => $index + 1]);
        });

        // Generate first round with bye if odd teams
        $this->generateFirstRoundWithOddTeamBye($bracket, $teams, $totalRounds);

        // Generate subsequent rounds (no byes in these rounds!)
        $this->generateSubsequentRoundsNoBye($bracket, $totalRounds, $teamCount);

        // Update bracket status
        $bracket->update(['status' => 'active']);

        $byeMessage = $isOddTeams ? ' (1 bye in first round for odd team count)' : '';
        return back()->with('success', "Single elimination bracket generated{$byeMessage}!");
    }

    /**
     * Generate first round games with bye support ONLY for odd number of teams
     */
    private function generateFirstRoundWithOddTeamBye($bracket, $teams, $totalRounds)
    {
        $teamArray = $teams->toArray();
        $teamCount = count($teamArray);
        
        $matchNumber = 1;
        $byeTeam = null;

        // Check if we have odd number of teams
        if ($teamCount % 2 === 1) {
            // ODD number of teams: Give ONE bye to the top seed
            $byeTeam = array_shift($teamArray); // Remove first team (top seed)
            
            // Store bye team info in bracket settings
            $bracket->update([
                'settings' => [
                    'bye_team' => $byeTeam['id'],
                    'has_bye' => true
                ]
            ]);

            // Create a special bye game in Round 1
            Game::create([
                'bracket_id' => $bracket->id,
                'round' => 1,
                'match_number' => $matchNumber,
                'team1_id' => $byeTeam['id'],
                'team2_id' => null, // No opponent
                'status' => 'completed', // Bye games are auto-completed
                'winner_id' => $byeTeam['id'], // Bye team auto-wins
                'is_bye' => true,
                'team1_score' => 0,
                'team2_score' => 0,
            ]);
            $matchNumber++;
        }

        // Create Round 1 games for remaining teams (now even number)
        for ($i = 0; $i < count($teamArray); $i += 2) {
            if (isset($teamArray[$i + 1])) {
                Game::create([
                    'bracket_id' => $bracket->id,
                    'round' => 1,
                    'match_number' => $matchNumber,
                    'team1_id' => $teamArray[$i]['id'],
                    'team2_id' => $teamArray[$i + 1]['id'],
                    'status' => 'pending',
                    'is_bye' => false,
                ]);
                $matchNumber++;
            }
        }
    }


   private function generateSubsequentRoundsNoBye($bracket, $totalRounds, $originalTeamCount)
{
    $settings = $bracket->settings ?? [];
    $hasBye = $settings['has_bye'] ?? false;
    
    // Count actual games (not bye games) from Round 1
    $round1Games = $bracket->gamesByRound(1)->where('is_bye', false)->count();
    
    // Start with Round 1 game count
    $previousRoundGames = $round1Games;
    
    // If there's a bye, add 1 to account for bye team joining Round 2
    if ($hasBye) {
        $previousRoundGames = $round1Games + 1;
    }
    
    // Generate all subsequent rounds
    for ($round = 2; $round <= $totalRounds; $round++) {
        // Calculate games for THIS round based on PREVIOUS round
        $currentRoundGames = max(1, ceil($previousRoundGames / 2));
        
        for ($game = 1; $game <= $currentRoundGames; $game++) {
            Game::create([
                'bracket_id' => $bracket->id,
                'round' => $round,
                'match_number' => $game,
                'team1_id' => null,
                'team2_id' => null,
                'status' => 'pending',
                'is_bye' => false,
            ]);
        }
        
        // Update for next iteration
        $previousRoundGames = $currentRoundGames;
    }
}

    /**
     * Special method to handle bye team advancement to Round 2
     * This should be called once when bracket is generated
     */
    private function setupByeTeamInRound2($bracket)
    {
        $settings = $bracket->settings ?? [];
        $byeTeamId = $settings['bye_team'] ?? null;
        
        if ($byeTeamId) {
            // Place bye team in Round 2, Game 1, waiting for opponent
            $round2Game1 = $bracket->games()
                ->where('round', 2)
                ->where('match_number', 1)
                ->first();
                
            if ($round2Game1) {
                $round2Game1->team1_id = $byeTeamId;
                $round2Game1->save();
            }
        }
    }

    /**
     * Advance winner to next round (updated for proper bye handling)
     */
    private function advanceWinner(Game $completedGame)
    {
        if (!$completedGame->winner_id) return;

        $bracket = $completedGame->bracket;

        if ($bracket->type === 'round-robin') {
            return;
        }

        if ($bracket->type === 'round-robin-playoff') {
            $this->handleRoundRobinPlayoffAdvancement($bracket, $completedGame);
            return;
        }

        // Single elimination advancement
        $settings = $bracket->settings ?? [];
        $hasBye = $settings['has_bye'] ?? false;
        $byeTeamId = $settings['bye_team'] ?? null;
        
        // Skip advancement for bye games (they're already marked as complete)
        if ($completedGame->is_bye) {
            return;
        }
        
        $nextRound = $completedGame->round + 1;
        
        // Special handling for Round 1 games when there's a bye team
        if ($completedGame->round === 1 && $hasBye && $byeTeamId) {
            // For Round 1 with bye:
            // - Bye team (from game 1) waits in Round 2, Game 1, position 1
            // - Winner of actual Game 1 (match_number 2) goes to Round 2, Game 1, position 2
            // - Other games advance normally to subsequent Round 2 games
            
            $actualGameNumber = $completedGame->match_number - 1; // Subtract 1 because bye game is #1
            
            if ($actualGameNumber === 1) {
                // First actual game winner faces the bye team
                $nextGame = $bracket->games()
                    ->where('round', 2)
                    ->where('match_number', 1)
                    ->first();
                    
                if ($nextGame) {
                    // Bye team should already be in position 1, winner goes to position 2
                    if (!$nextGame->team1_id) {
                        $nextGame->team1_id = $byeTeamId; // Place bye team
                    }
                    $nextGame->team2_id = $completedGame->winner_id; // Place game winner
                    $nextGame->save();
                }
            } else {
                // Other Round 1 games advance to their respective Round 2 games
                $targetGameNumber = ceil(($actualGameNumber) / 2) + 1;
                
                $nextGame = $bracket->games()
                    ->where('round', 2)
                    ->where('match_number', $targetGameNumber)
                    ->first();
                    
                if ($nextGame) {
                    if ($actualGameNumber % 2 === 0) {
                        $nextGame->team1_id = $completedGame->winner_id;
                    } else {
                        $nextGame->team2_id = $completedGame->winner_id;
                    }
                    $nextGame->save();
                }
            }
        } else {
            // Standard advancement for all other rounds
            $nextGameNumber = ceil($completedGame->match_number / 2);
            
            $nextGame = $bracket->games()
                ->where('round', $nextRound)
                ->where('match_number', $nextGameNumber)
                ->first();
                
            if ($nextGame) {
                if ($completedGame->match_number % 2 === 1) {
                    $nextGame->team1_id = $completedGame->winner_id;
                } else {
                    $nextGame->team2_id = $completedGame->winner_id;
                }
                $nextGame->save();
            }
        }
    }

    /**
     * Generate round-robin bracket with rotating bye support
     */
    public function generateRoundRobin(Bracket $bracket)
    {
        $teams = $bracket->tournament->teams;

        if ($teams->count() < 3) {
            return back()->with('error', 'Need at least 3 teams for round-robin tournament.');
        }

        // Clear existing games
        $bracket->games()->delete();

        // Attach teams to bracket
        $bracket->teams()->detach();
        $teams->each(function ($team, $index) use ($bracket) {
            $bracket->teams()->attach($team->id, ['seed' => $index + 1]);
        });

        $teamArray = $teams->pluck('id')->toArray();

        // Standard round-robin (works for both even and odd teams)
        $this->generateStandardRoundRobin($bracket, $teamArray);

        $bracket->update(['status' => 'active']);

        $totalGames = $bracket->games()->count();
        return back()->with('success', "Round-robin tournament generated! {$totalGames} games created.");
    }

    /**
     * Generate standard round-robin (works for both even and odd teams)
     */
    private function generateStandardRoundRobin($bracket, $teamIds)
    {
        $matchNumber = 1;

        for ($i = 0; $i < count($teamIds); $i++) {
            for ($j = $i + 1; $j < count($teamIds); $j++) {
                Game::create([
                    'bracket_id' => $bracket->id,
                    'round' => 1,
                    'match_number' => $matchNumber,
                    'team1_id' => $teamIds[$i],
                    'team2_id' => $teamIds[$j],
                    'status' => 'pending',
                    'is_bye' => false,
                ]);
                $matchNumber++;
            }
        }
    }

    /**
     * Generate round-robin + playoff bracket with bye support
     */
    public function generateRoundRobinPlayoff(Bracket $bracket)
    {
        $teams = $bracket->tournament->teams;

        if ($teams->count() < 6) {
            return back()->with('error', 'Need at least 6 teams for round-robin playoff tournament.');
        }

        // Clear existing games
        $bracket->games()->delete();

        // Attach teams to bracket
        $bracket->teams()->detach();
        $teams->each(function ($team, $index) use ($bracket) {
            $bracket->teams()->attach($team->id, ['seed' => $index + 1]);
        });

        $teamArray = $teams->pluck('id')->toArray();

        // Phase 1: Round-robin (works with any number of teams)
        $this->generateStandardRoundRobin($bracket, $teamArray);

        // Phase 2: Generate playoff structure
        $this->generatePlayoffStructure($bracket);

        $bracket->update(['status' => 'active']);

        $roundRobinGames = $bracket->games()->where('round', 1)->count();
        return back()->with('success', "Round-robin playoff tournament generated! {$roundRobinGames} group stage games + playoff rounds created.");
    }

    /**
     * Generate playoff structure (semifinals, finals, 3rd place)
     */
    private function generatePlayoffStructure(Bracket $bracket)
    {
        // Semifinals (Round 2): Top 4 teams, 2 games
        for ($i = 1; $i <= 2; $i++) {
            Game::create([
                'bracket_id' => $bracket->id,
                'round' => 2,
                'match_number' => $i,
                'team1_id' => null,
                'team2_id' => null,
                'status' => 'pending',
                'is_bye' => false,
                'game_details' => [
                    'phase' => 'playoff',
                    'playoff_round' => 'semifinals',
                    'description' => 'Semifinal ' . $i
                ]
            ]);
        }

        // Finals (Round 3): Game 1
        Game::create([
            'bracket_id' => $bracket->id,
            'round' => 3,
            'match_number' => 1,
            'team1_id' => null,
            'team2_id' => null,
            'status' => 'pending',
            'is_bye' => false,
            'game_details' => [
                'phase' => 'playoff',
                'playoff_round' => 'finals',
                'description' => 'Championship Final'
            ]
        ]);

        // 3rd Place Game (Round 3): Game 2
        Game::create([
            'bracket_id' => $bracket->id,
            'round' => 3,
            'match_number' => 2,
            'team1_id' => null,
            'team2_id' => null,
            'status' => 'pending',
            'is_bye' => false,
            'game_details' => [
                'phase' => 'playoff',
                'playoff_round' => '3rd-place',
                'description' => '3rd Place Playoff'
            ]
        ]);
    }

    /**
     * Handle advancement from round-robin to playoff stages
     */
    private function handleRoundRobinPlayoffAdvancement(Bracket $bracket, Game $completedGame)
    {
        // Check if round-robin phase is complete
        $roundRobinGames = $bracket->games()->where('round', 1)->get();
        $completedRoundRobinGames = $roundRobinGames->where('status', 'completed');

        if ($roundRobinGames->count() === $completedRoundRobinGames->count()) {
            // Round-robin phase is complete, advance top 4 to playoffs
            $this->advanceToPlayoffs($bracket);
        }
    }

    /**
     * Advance top 4 teams from round-robin to playoff semifinals
     */
    private function advanceToPlayoffs(Bracket $bracket)
    {
        $standings = $bracket->getRoundRobinStandings();

        if ($standings->count() < 4) {
            return;
        }

        // Get top 4 teams
        $top4 = $standings->take(4);

        // Semifinal 1: 1st seed vs 4th seed
        $semifinal1 = $bracket->games()
            ->where('round', 2)
            ->where('match_number', 1)
            ->first();

        if ($semifinal1) {
            $semifinal1->update([
                'team1_id' => $top4[0]['team']->id,
                'team2_id' => $top4[3]['team']->id,
            ]);
        }

        // Semifinal 2: 2nd seed vs 3rd seed  
        $semifinal2 = $bracket->games()
            ->where('round', 2)
            ->where('match_number', 2)
            ->first();

        if ($semifinal2) {
            $semifinal2->update([
                'team1_id' => $top4[1]['team']->id,
                'team2_id' => $top4[2]['team']->id,
            ]);
        }

        \Log::info("Playoff advancement completed for bracket {$bracket->id}");
    }

    /**
     * Handle playoff round advancement (semifinals to finals)
     */
    private function handlePlayoffAdvancement(Game $completedGame)
    {
        $bracket = $completedGame->bracket;

        // Semifinals completed - advance to finals
        if ($completedGame->round === 2) {
            $semifinals = $bracket->games()->where('round', 2)->get();
            $completedSemis = $semifinals->where('status', 'completed');

            if ($semifinals->count() === $completedSemis->count()) {
                // Both semifinals complete, set up finals and 3rd place game
                $winners = $completedSemis->pluck('winner_id');
                $losers = [];

                foreach ($completedSemis as $semi) {
                    $loserId = $semi->winner_id === $semi->team1_id ? $semi->team2_id : $semi->team1_id;
                    $losers[] = $loserId;
                }

                // Finals: winners of semifinals
                $finals = $bracket->games()
                    ->where('round', 3)
                    ->where('match_number', 1)
                    ->first();

                if ($finals && $winners->count() === 2) {
                    $finals->update([
                        'team1_id' => $winners[0],
                        'team2_id' => $winners[1],
                    ]);
                }

                // 3rd place game: losers of semifinals
                $thirdPlaceGame = $bracket->games()
                    ->where('round', 3)
                    ->where('match_number', 2)
                    ->first();

                if ($thirdPlaceGame && count($losers) === 2) {
                    $thirdPlaceGame->update([
                        'team1_id' => $losers[0],
                        'team2_id' => $losers[1],
                    ]);
                }
            }
        }
    }

    /**
     * Update game score and advance winners
     */
    public function updateGame(Request $request, Game $game)
    {
        $validated = $request->validate([
            'team1_score' => 'required|integer|min:0',
            'team2_score' => 'required|integer|min:0',
        ]);

        // Check for tie scores
        if ($validated['team1_score'] === $validated['team2_score']) {
            return back()->with('error', 'Tie scores are not allowed. One team must win.');
        }

        try {
            // Update the game
            $game->updateScore($validated['team1_score'], $validated['team2_score']);

            // Handle advancement based on bracket type
            if ($game->bracket->type === 'single-elimination') {
                $this->advanceWinner($game);
            } elseif ($game->bracket->type === 'round-robin-playoff') {
                if ($game->round === 1) {
                    $this->advanceWinner($game);
                } else {
                    $this->handlePlayoffAdvancement($game);
                }
            }

            return back()->with('success', 'Game updated successfully!');

        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Assign a team to a tournament
     */
    public function assignTeam(Request $request, $tournamentId)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        $tournament = Tournament::findOrFail($tournamentId);
        $team = Team::findOrFail($validated['team_id']);

        if (strtolower($team->sport) !== strtolower($tournament->sport)) {
            return back()->with('error', 'Team sport does not match tournament sport.');
        }

        $team->update(['tournament_id' => $tournament->id]);

        return back()->with('success', "Team '{$team->team_name}' has been assigned to the tournament!");
    }

    /**
     * Remove a team from a tournament
     */
    public function removeTeam($tournamentId, $teamId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $team = Team::findOrFail($teamId);

        if ($tournament->brackets()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot remove teams from tournament with active brackets.');
        }

        $team->update(['tournament_id' => null]);

        return back()->with('success', "Team '{$team->team_name}' has been removed from the tournament!");
    }

    /**
     * Save custom bracket configuration
     */
    public function saveCustomBracket(Request $request, Bracket $bracket)
    {
        if ($bracket->type !== 'single-elimination') {
            return response()->json(['error' => 'Custom brackets only available for single elimination'], 400);
        }

        $matchupsJson = $request->input('matchups');
        $matchups = json_decode($matchupsJson, true);

        if (!$matchups || !is_array($matchups)) {
            return response()->json(['error' => 'Invalid matchups data'], 400);
        }

        foreach ($matchups as $matchup) {
            if (!isset($matchup['team1_id']) || !isset($matchup['team2_id'])) {
                return response()->json(['error' => 'Invalid matchup data'], 400);
            }

            $team1 = Team::find($matchup['team1_id']);
            $team2 = Team::find($matchup['team2_id']);

            if (!$team1 || !$team2) {
                return response()->json(['error' => 'One or more teams not found'], 400);
            }

            if ($team1->tournament_id !== $bracket->tournament_id ||
                $team2->tournament_id !== $bracket->tournament_id) {
                return response()->json(['error' => 'Teams must belong to this tournament'], 400);
            }
        }

        try {
            $bracket->games()->delete();

            foreach ($matchups as $matchup) {
                Game::create([
                    'bracket_id' => $bracket->id,
                    'round' => 1,
                    'match_number' => $matchup['game'],
                    'team1_id' => $matchup['team1_id'],
                    'team2_id' => $matchup['team2_id'],
                    'status' => 'pending',
                    'is_bye' => false,
                ]);
            }

            $teamCount = $bracket->tournament->teams->count();
            $totalRounds = ceil(log($teamCount, 2));

            // Use the same method for subsequent rounds (no special bye logic for custom brackets)
            $this->generateSubsequentRoundsStandard($bracket, $totalRounds);

            $bracket->update(['status' => 'active']);

            return response()->json(['success' => true, 'message' => 'Custom bracket generated successfully!']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate bracket: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Generate standard subsequent rounds without bye logic (for custom brackets)
     */
    private function generateSubsequentRoundsStandard($bracket, $totalRounds)
    {
        $previousRoundGames = $bracket->gamesByRound(1)->count();
        
        for ($round = 2; $round <= $totalRounds; $round++) {
            $currentRoundGames = max(1, ceil($previousRoundGames / 2));
            
            for ($game = 1; $game <= $currentRoundGames; $game++) {
                Game::create([
                    'bracket_id' => $bracket->id,
                    'round' => $round,
                    'match_number' => $game,
                    'team1_id' => null,
                    'team2_id' => null,
                    'status' => 'pending',
                    'is_bye' => false,
                ]);
            }
            
            $previousRoundGames = $currentRoundGames;
        }
    }
}