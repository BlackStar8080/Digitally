<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Game;
use App\Models\Team;
use App\Models\Player;
use App\Models\PlayerGameStat;  // ADD THIS
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all tournaments with active brackets or recent activity
        $tournaments = Tournament::with(['teams', 'brackets'])
            ->whereHas('brackets')
            ->orderBy('start_date', 'desc')
            ->get();

        // Prepare data for each tournament
        $tournamentData = [];
        
        foreach ($tournaments as $tournament) {
            $tournamentData[$tournament->id] = [
                'rankings' => $this->getTeamRankings($tournament->id),
                'mvp' => $this->getRecentMVP($tournament->id),
                'recentGames' => $this->getRecentGames($tournament->id)
            ];
        }

        return view('dashboard', compact('tournaments', 'tournamentData'));
    }

    private function getTeamRankings($tournamentId)
    {
        $tournament = Tournament::find($tournamentId);
        if (!$tournament) {
            return collect();
        }

        $teams = $tournament->teams;
        $rankings = [];
        
        foreach ($teams as $team) {
            $wins = Game::where(function($query) use ($team, $tournament) {
                $query->whereHas('bracket', function($q) use ($tournament) {
                    $q->where('tournament_id', $tournament->id);
                })
                ->where('winner_id', $team->id)
                ->where('status', 'completed');
            })->count();

            $losses = Game::where(function($query) use ($team, $tournament) {
                $query->whereHas('bracket', function($q) use ($tournament) {
                    $q->where('tournament_id', $tournament->id);
                })
                ->where(function($q) use ($team) {
                    $q->where('team1_id', $team->id)
                      ->orWhere('team2_id', $team->id);
                })
                ->where('status', 'completed')
                ->where('winner_id', '!=', $team->id);
            })->count();

            $totalGames = $wins + $losses;
            $winPercentage = $totalGames > 0 ? ($wins / $totalGames) * 100 : 0;

            $rankings[] = [
                'team' => $team,
                'wins' => $wins,
                'losses' => $losses,
                'win_percentage' => $winPercentage,
                'total_games' => $totalGames
            ];
        }

        usort($rankings, function($a, $b) {
            if ($a['wins'] === $b['wins']) {
                return $b['win_percentage'] <=> $a['win_percentage'];
            }
            return $b['wins'] <=> $a['wins'];
        });

        foreach ($rankings as $index => &$ranking) {
            $ranking['position'] = $index + 1;
        }

        return collect($rankings)->take(10);
    }

    private function getRecentMVP($tournamentId)
{
    $tournament = Tournament::find($tournamentId);
    if (!$tournament) {
        return collect();
    }

    // Determine sport type
    $sportName = strtolower($tournament->sport->sports_name ?? '');
    $isVolleyball = $sportName === 'volleyball';

    if ($isVolleyball) {
        // Get volleyball MVPs
        $mvpStats = \App\Models\VolleyballPlayerStat::with(['player.team', 'game'])
            ->where('is_mvp', true)
            ->whereHas('game.bracket', function($query) use ($tournamentId) {
                $query->where('tournament_id', $tournamentId);
            })
            ->whereHas('game', function($query) {
                $query->where('status', 'completed');
            })
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Transform volleyball stats to have consistent structure
        return $mvpStats->map(function($stat) {
            return [
                'player' => $stat->player,
                'game' => $stat->game,
                'points' => $stat->kills ?? 0,  // Use kills as main stat
                'rebounds' => $stat->blocks ?? 0,
                'assists' => $stat->assists ?? 0,
                'steals' => $stat->aces ?? 0,
                'type' => 'volleyball',
                'stats' => $stat // Keep original stats
            ];
        });
    } else {
        // Get basketball MVPs
        $mvpStats = PlayerGameStat::with(['player.team', 'game'])
            ->where('is_mvp', true)
            ->whereHas('game.bracket', function($query) use ($tournamentId) {
                $query->where('tournament_id', $tournamentId);
            })
            ->whereHas('game', function($query) {
                $query->where('status', 'completed');
            })
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Transform basketball stats to have consistent structure
        return $mvpStats->map(function($stat) {
            return [
                'player' => $stat->player,
                'game' => $stat->game,
                'points' => $stat->points ?? 0,
                'rebounds' => $stat->rebounds ?? 0,
                'assists' => $stat->assists ?? 0,
                'steals' => $stat->steals ?? 0,
                'type' => 'basketball',
                'stats' => $stat
            ];
        });
    }
}

    private function getRecentGames($tournamentId)
    {
        return Game::with(['team1', 'team2', 'bracket'])
            ->whereHas('bracket', function($query) use ($tournamentId) {
                $query->where('tournament_id', $tournamentId);
            })
            ->whereIn('status', ['completed', 'in-progress'])
            ->orderBy('completed_at', 'desc')
            ->orderBy('scheduled_at', 'desc')
            ->limit(6)
            ->get();
    }
}