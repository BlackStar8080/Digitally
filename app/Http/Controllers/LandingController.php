<?php
namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function index()
    {
        // Get live/active games (games that are in progress or scheduled for today)
        $liveGames = Game::with(['team1', 'team2', 'bracket.tournament'])
            ->whereHas('bracket', function($query) {
                $query->where('status', 'active');
            })
            ->where(function($query) {
                $query->where('status', 'in-progress')
                      ->orWhere(function($q) {
                          $q->where('status', 'pending')
                            ->whereDate('scheduled_at', Carbon::today());
                      });
            })
            ->orderBy('scheduled_at', 'asc')
            ->limit(6)
            ->get();

        // Get active tournaments
        $activeTournaments = Tournament::with(['teams', 'brackets'])
            ->where(function($query) {
                $query->whereHas('brackets', function($q) {
                    $q->where('status', 'active');
                })->orWhere('start_date', '>=', Carbon::today());
            })
            ->orderBy('start_date', 'asc')
            ->limit(6)
            ->get();

        // Get recent completed games
        $recentResults = Game::with(['team1', 'team2', 'bracket.tournament', 'winner'])
            ->where('status', 'completed')
            ->whereNotNull('winner_id')
            ->orderBy('completed_at', 'desc')
            ->limit(8)
            ->get();

        // Get tournament statistics
        $stats = [
            'total_games' => Game::count(),
            'active_tournaments' => Tournament::whereHas('brackets', function($q) {
                $q->where('status', 'active');
            })->count(),
            'total_teams' => Team::count(),
            'completed_games' => Game::where('status', 'completed')->count()
        ];

        // NEW: Get tournament games for the switching functionality
        $tournamentGames = Tournament::with([
            'brackets.games' => function($query) {
                $query->with(['team1', 'team2'])
                      ->whereIn('status', ['in-progress', 'scheduled', 'pending', 'completed'])
                      ->orderBy('round', 'asc')
                      ->orderBy('match_number', 'asc');
            }
        ])
        ->whereHas('brackets.games', function($query) {
            $query->whereIn('status', ['in-progress', 'scheduled', 'pending', 'completed']);
        })
        ->orderBy('start_date', 'desc')
        ->take(5) // Limit to 5 tournaments for tabs
        ->get();

        // Transform the data to make games accessible at tournament level
        $tournamentGames->transform(function ($tournament) {
            // Flatten all games from all brackets of this tournament
            $tournament->games = collect();
            foreach ($tournament->brackets as $bracket) {
                $tournament->games = $tournament->games->merge($bracket->games);
            }
            
            // Sort games by status priority (in-progress first, then scheduled, then completed)
            $tournament->games = $tournament->games->sortBy([
                function($game) {
                    // Priority: in-progress (0) > scheduled/pending (1) > completed (2)
                    if ($game->status === 'in-progress') return 0;
                    if (in_array($game->status, ['scheduled', 'pending'])) return 1;
                    return 2;
                },
                ['round', 'asc'],
                ['match_number', 'asc']
            ])->values(); // Reset keys after sorting

            return $tournament;
        });

        return view('landing', compact(
            'liveGames', 
            'activeTournaments', 
            'recentResults', 
            'stats',
            'tournamentGames' // Add the new variable
        ));
    }

    public function getLiveScores()
    {
        // API endpoint for real-time score updates
        $liveGames = Game::with(['team1', 'team2', 'bracket.tournament'])
            ->where('status', 'in-progress')
            ->get()
            ->map(function($game) {
                return [
                    'id' => $game->id,
                    'team1' => [
                        'name' => $game->team1->team_name,
                        'score' => $game->team1_score ?? 0
                    ],
                    'team2' => [
                        'name' => $game->team2->team_name,
                        'score' => $game->team2_score ?? 0
                    ],
                    'status' => $this->getGameStatusText($game),
                    'status_display' => $game->status === 'in-progress' ? 
                        '<span class="live-indicator">LIVE</span>' : 
                        ucfirst($game->status),
                    'sport' => $game->bracket->tournament->sport,
                    'tournament' => $game->bracket->tournament->name
                ];
            });

        return response()->json($liveGames);
    }

    private function getGameStatusText($game)
    {
        if ($game->status === 'completed') {
            return 'Final';
        }
        
        if ($game->status === 'in-progress') {
            // You can expand this based on your game tracking needs
            if ($game->bracket->tournament->sport === 'Basketball') {
                return 'Q' . ($game->current_quarter ?? 1) . ' - ' . ($game->time_remaining ?? '12:00');
            } else if ($game->bracket->tournament->sport === 'Volleyball') {
                return 'Set ' . ($game->current_set ?? 1) . ' - ' . ($game->team1_score ?? 0) . ':' . ($game->team2_score ?? 0);
            }
        }
        
        if ($game->scheduled_at) {
            return 'Next - ' . Carbon::parse($game->scheduled_at)->format('g:i A');
        }
        
        return 'Pending';
    }
}