<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bracket extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'name',
        'type',
        'status',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array', // Automatically convert JSON to array
    ];

    /**
     * A bracket belongs to a tournament.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * A bracket has many games.
     */
    public function games()
    {
        return $this->hasMany(Game::class)->orderBy('round')->orderBy('match_number');
    }

    /**
     * Teams participating in this bracket.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'bracket_team')
                    ->withPivot('seed')
                    ->withTimestamps()
                    ->orderByPivot('seed');
    }

    /**
     * Get games by round.
     */
    public function gamesByRound($round)
    {
        return $this->games()->where('round', $round)->get();
    }

    /**
     * Get round-robin games only
     */
    public function getRoundRobinGames()
    {
        if ($this->type === 'round-robin-playoff') {
            return $this->games()->where('round', 1)->get();
        } elseif ($this->type === 'round-robin') {
            return $this->games()->get();
        }
        
        return collect();
    }

    /**
     * Get playoff games only
     */
    public function getPlayoffGames()
    {
        if ($this->type === 'round-robin-playoff') {
            return $this->games()->where('round', '>', 1)->get();
        }
        
        return collect();
    }

    /**
     * Check if round-robin phase is completed
     */
    public function isRoundRobinPhaseComplete()
    {
        if (!in_array($this->type, ['round-robin', 'round-robin-playoff'])) {
            return false;
        }

        $roundRobinGames = $this->getRoundRobinGames();
        $completedGames = $roundRobinGames->where('status', 'completed');
        
        return $roundRobinGames->count() > 0 && $roundRobinGames->count() === $completedGames->count();
    }

    /**
     * Check if playoffs have started
     */
    public function hasPlayoffsStarted()
    {
        if ($this->type !== 'round-robin-playoff') {
            return false;
        }

        return $this->getPlayoffGames()->where('team1_id', '!=', null)->count() > 0;
    }

    /**
     * Get current tournament phase
     */
    public function getCurrentPhase()
    {
        if ($this->type === 'round-robin-playoff') {
            if (!$this->isRoundRobinPhaseComplete()) {
                return 'round-robin';
            } elseif (!$this->isCompleted()) {
                return 'playoff';
            } else {
                return 'completed';
            }
        } elseif ($this->type === 'round-robin') {
            return $this->isCompleted() ? 'completed' : 'round-robin';
        } else {
            return $this->isCompleted() ? 'completed' : 'elimination';
        }
    }

    /**
     * Get the current active round.
     */
    public function getCurrentRound()
    {
        return $this->games()
                    ->where('status', '!=', 'completed')
                    ->min('round');
    }

    /**
     * Check if bracket is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed' ||
               $this->games()->where('status', '!=', 'completed')->count() === 0;
    }

    /**
     * Get the champion 
     */
    public function getChampion()
    {
        if ($this->type === 'round-robin') {
            return $this->getRoundRobinWinner();
        } elseif ($this->type === 'round-robin-playoff') {
            return $this->getPlayoffChampion();
        }

        // For elimination tournaments
        $finalGame = $this->games()
                          ->orderBy('round', 'desc')
                          ->orderBy('match_number')
                          ->first();

        return $finalGame && $finalGame->winner_id ?
               Team::find($finalGame->winner_id) : null;
    }

    /**
     * Get playoff champion (winner of finals)
     */
    public function getPlayoffChampion()
    {
        if ($this->type !== 'round-robin-playoff') {
            return null;
        }

        $finalsGame = $this->games()
            ->where('round', 3)
            ->where('match_number', 1)
            ->where('status', 'completed')
            ->first();

        return $finalsGame ? Team::find($finalsGame->winner_id) : null;
    }

    /**
     * Get 3rd place team 
     */
    public function getThirdPlace()
    {
        if ($this->type !== 'round-robin-playoff') {
            return null;
        }

        $thirdPlaceGame = $this->games()
            ->where('round', 3)
            ->where('match_number', 2)
            ->where('status', 'completed')
            ->first();

        return $thirdPlaceGame ? Team::find($thirdPlaceGame->winner_id) : null;
    }

    /**
     * Get runner-up (finalist who lost)
     */
    public function getRunnerUp()
    {
        if ($this->type !== 'round-robin-playoff') {
            return null;
        }

        $finalsGame = $this->games()
            ->where('round', 3)
            ->where('match_number', 1)
            ->where('status', 'completed')
            ->first();

        if (!$finalsGame || !$finalsGame->winner_id) {
            return null;
        }

        $runnerId = $finalsGame->winner_id === $finalsGame->team1_id 
            ? $finalsGame->team2_id 
            : $finalsGame->team1_id;

        return Team::find($runnerId);
    }

    /**
     * Get total rounds in this bracket.
     */
    public function getTotalRounds()
    {
        if ($this->type === 'round-robin') {
            return 1; // Round-robin is always 1 "round"
        } elseif ($this->type === 'round-robin-playoff') {
            return 3; // Round-robin (1) + Semifinals (2) + Finals (3)
        }

        return $this->games()->max('round') ?: 0;
    }

    /**
     * Get round-robin standings
     */
    public function getRoundRobinStandings()
    {
        if (!in_array($this->type, ['round-robin', 'round-robin-playoff'])) {
            return collect();
        }

        $teams = $this->teams;
        $standings = [];

        foreach ($teams as $team) {
            $teamGames = $this->getRoundRobinGames()
                ->filter(function($game) use ($team) {
                    return $game->team1_id == $team->id || $game->team2_id == $team->id;
                })
                ->where('status', 'completed');

            $wins = 0;
            $losses = 0;
            $pointsFor = 0;
            $pointsAgainst = 0;
            $gamesPlayed = $teamGames->count();

            foreach ($teamGames as $game) {
                if ($game->team1_id == $team->id) {
                    $pointsFor += $game->team1_score ?? 0;
                    $pointsAgainst += $game->team2_score ?? 0;
                    if ($game->winner_id == $team->id) {
                        $wins++;
                    } else {
                        $losses++;
                    }
                } else {
                    $pointsFor += $game->team2_score ?? 0;
                    $pointsAgainst += $game->team1_score ?? 0;
                    if ($game->winner_id == $team->id) {
                        $wins++;
                    } else {
                        $losses++;
                    }
                }
            }

            $pointDifference = $pointsFor - $pointsAgainst;
            $winPercentage = $gamesPlayed > 0 ? ($wins / $gamesPlayed) * 100 : 0;

            $standings[] = [
                'team' => $team,
                'games_played' => $gamesPlayed,
                'wins' => $wins,
                'losses' => $losses,
                'win_percentage' => $winPercentage,
                'points_for' => $pointsFor,
                'points_against' => $pointsAgainst,
                'point_difference' => $pointDifference,
                'remaining_games' => $this->getRemainingGamesForTeam($team->id),
                'playoff_qualified' => false, // Will be set below
            ];
        }

        // Sort standings: wins desc, then point difference desc, then points for desc
        usort($standings, function($a, $b) {
            if ($a['wins'] != $b['wins']) {
                return $b['wins'] - $a['wins'];
            }
            if ($a['point_difference'] != $b['point_difference']) {
                return $b['point_difference'] - $a['point_difference'];
            }
            return $b['points_for'] - $a['points_for'];
        });

        // Mark playoff qualified teams (top 4 for round-robin-playoff)
        if ($this->type === 'round-robin-playoff' && count($standings) >= 4) {
            for ($i = 0; $i < 4; $i++) {
                if (isset($standings[$i])) {
                    $standings[$i]['playoff_qualified'] = true;
                }
            }
        }

        return collect($standings);
    }

    /**
     * Get the round-robin winner (team with best record)
     */
    public function getRoundRobinWinner()
    {
        if (!in_array($this->type, ['round-robin', 'round-robin-playoff'])) {
            return null;
        }

        $standings = $this->getRoundRobinStandings();
        
        // For pure round-robin, only declare winner if all games are completed
        if ($this->type === 'round-robin' && !$this->isCompleted()) {
            return null;
        }

        return $standings->first()['team'] ?? null;
    }

    /**
     * Get remaining games for a specific team in round-robin
     */
    public function getRemainingGamesForTeam($teamId)
    {
        return $this->getRoundRobinGames()
            ->filter(function($game) use ($teamId) {
                return $game->team1_id == $teamId || $game->team2_id == $teamId;
            })
            ->where('status', '!=', 'completed')
            ->count();
    }

    /**
     * Get total games count
     */
    public function getTotalGamesCount()
    {
        return $this->games()->count();
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentage()
    {
        $totalGames = $this->games()->count();
        if ($totalGames === 0) return 0;

        $completedGames = $this->games()->where('status', 'completed')->count();
        return round(($completedGames / $totalGames) * 100, 1);
    }

    /**
     * Get round-robin completion percentage
     */
    public function getRoundRobinCompletionPercentage()
    {
        $roundRobinGames = $this->getRoundRobinGames();
        if ($roundRobinGames->count() === 0) return 0;

        $completedRoundRobinGames = $roundRobinGames->where('status', 'completed')->count();
        return round(($completedRoundRobinGames / $roundRobinGames->count()) * 100, 1);
    }

    /**
     * Get playoff completion percentage
     */
    public function getPlayoffCompletionPercentage()
    {
        $playoffGames = $this->getPlayoffGames();
        if ($playoffGames->count() === 0) return 0;

        $completedPlayoffGames = $playoffGames->where('status', 'completed')->count();
        return round(($completedPlayoffGames / $playoffGames->count()) * 100, 1);
    }

    /**
     * Check if round-robin tournament
     */
    public function isRoundRobin()
    {
        return $this->type === 'round-robin';
    }

    /**
     * Check if round-robin playoff tournament
     */
    public function isRoundRobinPlayoff()
    {
        return $this->type === 'round-robin-playoff';
    }

    /**
     * Check if elimination tournament
     */
    public function isElimination()
    {
        return in_array($this->type, ['single-elimination', 'double-elimination']);
    }

    /**
     * Get playoff bracket structure for display
     */
    public function getPlayoffStructure()
    {
        if ($this->type !== 'round-robin-playoff') {
            return null;
        }

        $semifinals = $this->games()->where('round', 2)->orderBy('match_number')->get();
        $finals = $this->games()->where('round', 3)->where('match_number', 1)->first();
        $thirdPlace = $this->games()->where('round', 3)->where('match_number', 2)->first();

        return [
            'semifinals' => $semifinals,
            'finals' => $finals,
            'third_place' => $thirdPlace
        ];
    }

    /**
     * Get final tournament results
     */
    public function getFinalResults()
    {
        if ($this->type !== 'round-robin-playoff' || !$this->isCompleted()) {
            return null;
        }

        return [
            'champion' => $this->getChampion(),
            'runner_up' => $this->getRunnerUp(),
            'third_place' => $this->getThirdPlace(),
            'round_robin_winner' => $this->getRoundRobinWinner(),
        ];
    }
}